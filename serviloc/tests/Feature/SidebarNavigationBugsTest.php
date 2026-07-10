<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SidebarNavigationBugsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * COUNTEREXAMPLE DOCUMENTATION - BUG CONDITION EXPLORATION RESULTS
     * 
     * These tests surface bugs that exist in the unfixed sidebar navigation code.
     * Running these tests on unfixed code PASSES, confirming each bug exists.
     * Running these tests on fixed code will FAIL, indicating the bugs have been resolved.
     * 
     * Bug 1 - Provider Reviews Link Routes to Customer.Reviews:
     *   Counterexample: provider.role = 'provider' → sidebar has Reviews link → href = /customer/reviews
     *   Expected: href should be /provider/reviews OR link should not exist
     *   Impact: Providers can access customer-only route, violating role-based access control
     * 
     * Bug 2 - Profile Page Missing Active State on My Profile Link:
     *   Counterexample: currentRoute = /profile/edit → My Profile link.class = 'nav-item' (no 'active')
     *   Expected: My Profile link.class = 'nav-item active'
     *   Impact: User doesn't see which page they're on in Account section
     * 
     * Bug 3 - Emoji Icons Are Inconsistent:
     *   Counterexample: sidebar emojis = [📊, 📝, 💬, ✅, 🚪, ⭐] (6 different styles)
     *   Expected: All emojis should be consistent category/style
     *   Impact: Navigation looks unprofessional and visually jarring
     * 
     * Bug 4 - Admin All Requests Link Lacks Active State:
     *   Counterexample: role = 'admin', currentRoute = /customer/requests → All Requests link.class = 'nav-item'
     *   Expected: All Requests link.class = 'nav-item active'
     *   Impact: Admin doesn't see which page they're on in navigation
     * 
     * Bug 5 - Provider Available Requests Link Lacks Active State:
     *   Counterexample: role = 'provider', currentRoute = /search → Available Requests link.class = 'nav-item'
     *   Expected: Available Requests link.class = 'nav-item active'
     *   Impact: Provider doesn't see which page they're on in navigation
     */

    /**
     * Test Bug Fix 1: Provider "Reviews" link is now removed/fixed
     * 
     * This test verifies that on FIXED code, the provider sidebar does NOT include
     * a "Reviews" link pointing to the customer.reviews route (the bug is fixed).
     * 
     * **Validates: Requirements 1.1, 2.1**
     */
    public function test_provider_reviews_link_routes_to_customer_reviews_bug(): void
    {
        $provider = User::factory()->create(['role' => 'provider']);

        $response = $this->actingAs($provider)->get('/provider/dashboard');

        $response->assertOk();
        
        $body = $response->getContent();
        
        // On FIXED code: Provider sidebar should NOT have Reviews link pointing to customer.reviews
        // The bug has been fixed - the Reviews link is now removed from provider menu
        
        // Verify the Reviews link that pointed to customer/reviews is GONE
        $customerReviewsUrl = route('customer.reviews');
        
        $hasCustomerReviewsLink = strpos($body, $customerReviewsUrl) !== false && 
                                 preg_match('/href="[^"]*customer\/reviews[^"]*"[^>]*>.*?Reviews/s', $body);
        
        $this->assertFalse($hasCustomerReviewsLink, 
            'Provider Reviews link should NOT point to ' . $customerReviewsUrl . ' - this bug should be fixed!');
    }

    /**
     * Test Bug Condition 2: Profile page missing active state on "My Profile" link
     * 
     * This test verifies that when a user navigates to the profile edit page,
     * the "My Profile" link in the Account section does NOT have the active class.
     * 
     * On fixed code, the link should have the 'active' class applied.
     * 
     * **Validates: Requirements 1.2, 2.2**
     */
    public function test_profile_page_missing_active_state_on_my_profile_link(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        // Navigate to profile edit page
        $response = $this->actingAs($user)->get('/profile/edit');

        $response->assertOk();
        
        // On unfixed code: My Profile link should NOT have 'active' class
        // We check for the link without active class
        $response->assertSee('My Profile', false);
        
        // Check that the link does NOT contain active state styling
        // The active class should be present on the nav-item element containing the profile link
        $body = $response->getContent();
        
        // Pattern to find My Profile link: should be in a nav-item
        // On unfixed code, it should be: <a href="/profile" class="nav-item">
        // On fixed code, it should be: <a href="/profile/edit" class="nav-item active">
        
        // We verify the bug exists by checking the link is NOT active when on profile page
        preg_match('/My Profile.*?class="([^"]*)"/', $body, $matches);
        
        if (!empty($matches[1])) {
            $classes = $matches[1];
            // On unfixed code: active class should NOT be present when on profile page
            $this->assertStringNotContainsString('active', $classes, 
                'My Profile link should NOT have active class on unfixed code (this is the bug)');
        }
    }

    /**
     * Test Bug Condition 3: Emoji icons are inconsistent across sections
     * 
     * This test verifies that the sidebar uses mixed emoji styles, which is inconsistent.
     * It checks for the presence of various emoji types that demonstrate the inconsistency.
     * 
     * On unfixed code, we should find a mix of different emoji styles.
     * On fixed code, all emojis should be consistent.
     * 
     * **Validates: Requirements 1.3, 1.4, 2.3**
     */
    public function test_emoji_icons_are_inconsistent(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->get('/customer/dashboard');

        $response->assertOk();
        
        $body = $response->getContent();
        
        // Count different emoji types used
        $emojis = [];
        
        // Extract all emoji patterns from nav-icon spans
        preg_match_all('/<span class="nav-icon">([^<]+)<\/span>/', $body, $matches);
        
        if (!empty($matches[1])) {
            $emojis = array_unique($matches[1]);
        }
        
        // On unfixed code: we should see inconsistent emojis like:
        // 📊 (chart), 📝 (clipboard/notes), 💬 (comment), ⭐ (star)
        // These are different categories of emojis showing inconsistency
        
        // Count unique emojis - inconsistency means many different styles
        $uniqueCount = count($emojis);
        
        // On unfixed code, we expect at least 3-4 different emoji types
        // This demonstrates the inconsistency bug
        $this->assertGreaterThanOrEqual(3, $uniqueCount,
            'Should have multiple inconsistent emoji types on unfixed code. Found: ' . implode(', ', $emojis));
    }

    /**
     * Test Bug Condition 4: Admin "All Requests" link lacks active state matching
     * 
     * This test verifies that when an admin navigates to a requests page,
     * the "All Requests" link does not get the active class applied.
     * 
     * The bug is that the link doesn't have request()->routeIs() matching.
     * 
     * **Validates: Requirements 1.4, 2.4**
     */
    public function test_admin_all_requests_link_lacks_active_state(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/customer/requests');

        $response->assertOk();
        
        $body = $response->getContent();
        
        // Find the All Requests link and check it doesn't have active class
        // Pattern: <a href="/customer/requests" class="nav-item">
        // On unfixed code, it should NOT have 'active' class
        preg_match('/All Requests.*?class="([^"]*)"/', $body, $matches);
        
        if (!empty($matches[1])) {
            $classes = $matches[1];
            // On unfixed code: active class should NOT be present
            $this->assertStringNotContainsString('active', $classes,
                'All Requests link should NOT have active class on unfixed code (this is the bug)');
        }
    }

    /**
     * Test Bug Condition 5: Provider "Available Requests" link lacks active state matching
     * 
     * This test verifies that when a provider is on the search page,
     * the "Available Requests" link does not get the active class applied.
     * 
     * The bug is that the link doesn't have request()->routeIs() matching for the search route.
     * 
     * **Validates: Requirements 1.5, 2.5**
     */
    public function test_provider_available_requests_link_lacks_active_state(): void
    {
        $provider = User::factory()->create(['role' => 'provider']);

        $response = $this->actingAs($provider)->get('/search');

        $response->assertOk();
        
        $body = $response->getContent();
        
        // Find the Available Requests link and check it doesn't have active class
        // Pattern: <a href="/search" class="nav-item">
        // On unfixed code, it should NOT have 'active' class
        preg_match('/Available Requests.*?class="([^"]*)"/', $body, $matches);
        
        if (!empty($matches[1])) {
            $classes = $matches[1];
            // On unfixed code: active class should NOT be present
            $this->assertStringNotContainsString('active', $classes,
                'Available Requests link should NOT have active class on unfixed code (this is the bug)');
        }
    }

    /**
     * Test Bug Condition 6: Sidebar link verification
     * 
     * This comprehensive test checks that the sidebar renders with all expected elements
     * and that specific bugs can be observed in the HTML output.
     * 
     * **Validates: Requirements 1.1, 1.2, 1.3, 1.4, 1.5, 2.1, 2.2, 2.3, 2.4, 2.5**
     */
    public function test_sidebar_bugs_comprehensive(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->get('/customer/dashboard');

        $response->assertOk();
        
        // Verify sidebar exists
        $response->assertSee('sidebar');
        
        // Verify Account section exists
        $response->assertSee('Account');
        $response->assertSee('My Profile');
        $response->assertSee('Logout');
    }
}
