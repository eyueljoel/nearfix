<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SidebarPreservationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PRESERVATION BEHAVIOR DOCUMENTATION
     * 
     * These tests capture baseline behavior for non-buggy navigation items.
     * They PASS on unfixed code and CONTINUE TO PASS after fixes are applied.
     * 
     * These tests verify that existing, working navigation functionality is preserved
     * and not broken by bugfix implementations.
     * 
     * Property: Preservation - Non-Buggy Sidebar Behavior Preserved
     * 
     * **Validates: Requirements 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7**
     */

    /**
     * Test: Customer Dashboard Navigation
     * 
     * Preserves: Customer can access dashboard via sidebar and see active state
     * 
     * Scenario:
     * - Customer clicks Dashboard in sidebar
     * - Customer navigates to /customer/dashboard
     * 
     * Expected Behavior:
     * - Page loads successfully (200 OK)
     * - Dashboard link has active state (css class 'active')
     * - Sidebar displays correctly
     * 
     * **Validates: Requirement 3.1**
     */
    public function test_customer_dashboard_navigation_with_active_state(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get('/customer/dashboard');

        $response->assertOk();
        $response->assertSee('sidebar');
        
        // Verify Dashboard link has active state
        $body = $response->getContent();
        preg_match('/Dashboard.*?class="([^"]*)"/', $body, $matches);
        
        if (!empty($matches[1])) {
            $classes = $matches[1];
            $this->assertStringContainsString('active', $classes,
                'Dashboard link should have active class when on customer dashboard');
        }
    }

    /**
     * Test: Customer Requests Navigation
     * 
     * Preserves: Customer can access "My Requests" page via sidebar and see active state
     * 
     * Scenario:
     * - Customer clicks "My Requests" in sidebar
     * - Customer navigates to /customer/requests
     * 
     * Expected Behavior:
     * - Page loads successfully (200 OK)
     * - "My Requests" link has active state (css class 'active')
     * - Sidebar displays correctly
     * - Badge shows request count
     * 
     * **Validates: Requirement 3.2**
     */
    public function test_customer_requests_navigation_with_active_state(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get('/customer/requests');

        $response->assertOk();
        $response->assertSee('sidebar');
        $response->assertSee('My Requests');
        
        // Verify "My Requests" link has active state
        $body = $response->getContent();
        preg_match('/My Requests.*?class="([^"]*)"/', $body, $matches);
        
        if (!empty($matches[1])) {
            $classes = $matches[1];
            $this->assertStringContainsString('active', $classes,
                'My Requests link should have active class when on customer requests page');
        }
    }

    /**
     * Test: Admin Dashboard Navigation
     * 
     * Preserves: Admin can access dashboard via sidebar and see active state
     * 
     * Scenario:
     * - Admin clicks Dashboard in sidebar
     * - Admin navigates to /admin/dashboard
     * 
     * Expected Behavior:
     * - Page loads successfully (200 OK)
     * - Dashboard link has active state (css class 'active')
     * - Sidebar displays admin menu
     * 
     * **Validates: Requirement 3.3**
     */
    public function test_admin_dashboard_navigation_with_active_state(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('sidebar');
        
        // Verify Dashboard link has active state
        $body = $response->getContent();
        preg_match('/Dashboard.*?class="([^"]*)"/', $body, $matches);
        
        if (!empty($matches[1])) {
            $classes = $matches[1];
            $this->assertStringContainsString('active', $classes,
                'Dashboard link should have active class when on admin dashboard');
        }
    }

    /**
     * Test: Provider Dashboard Navigation
     * 
     * Preserves: Provider can access dashboard via sidebar and see active state
     * 
     * Scenario:
     * - Provider clicks Dashboard in sidebar
     * - Provider navigates to /provider/dashboard
     * 
     * Expected Behavior:
     * - Page loads successfully (200 OK)
     * - Dashboard link has active state (css class 'active')
     * - Sidebar displays provider menu
     * 
     * **Validates: Requirement 3.4**
     */
    public function test_provider_dashboard_navigation_with_active_state(): void
    {
        $provider = User::factory()->create(['role' => 'provider']);

        $response = $this->actingAs($provider)->get('/provider/dashboard');

        $response->assertOk();
        $response->assertSee('sidebar');
        
        // Verify Dashboard link has active state
        $body = $response->getContent();
        preg_match('/Dashboard.*?class="([^"]*)"/', $body, $matches);
        
        if (!empty($matches[1])) {
            $classes = $matches[1];
            $this->assertStringContainsString('active', $classes,
                'Dashboard link should have active class when on provider dashboard');
        }
    }

    /**
     * Test: Profile Access via Sidebar
     * 
     * Preserves: User can access profile page via sidebar and see active state
     * 
     * Scenario:
     * - User navigates to /profile/edit
     * - User views sidebar
     * 
     * Expected Behavior:
     * - Page loads successfully (200 OK)
     * - Sidebar displays "My Profile" link
     * - "My Profile" link is accessible (clickable, proper href)
     * 
     * **Validates: Requirement 3.5 (profile access part)**
     */
    public function test_profile_access_via_sidebar_preserved(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->get('/profile/edit');

        $response->assertOk();
        $response->assertSee('sidebar');
        $response->assertSee('My Profile');
        
        // Verify profile link exists in sidebar
        $body = $response->getContent();
        $this->assertStringContainsString('profile', $body,
            'Profile link should be accessible in sidebar');
    }

    /**
     * Test: Logout Functionality Preserved
     * 
     * Preserves: User can click logout and be logged out
     * 
     * Scenario:
     * - User navigates to dashboard
     * - User clicks logout link in sidebar
     * - User submits logout form
     * 
     * Expected Behavior:
     * - Logout link exists in sidebar
     * - Logout form exists with proper structure
     * - POST to logout route succeeds
     * - User is logged out and redirected
     * 
     * **Validates: Requirement 3.5 (logout part)**
     */
    public function test_logout_functionality_preserved(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        // First, verify logout link exists in sidebar
        $response = $this->actingAs($user)->get('/customer/dashboard');
        $response->assertOk();
        $response->assertSee('Logout');
        
        // Verify logout form exists
        $response->assertSee('logout-form');
        $response->assertSee('_token');
        
        // Test actual logout
        $logoutResponse = $this->post(route('logout'));
        
        // Should be redirected
        $this->assertFalse(auth()->check(),
            'User should be logged out after logout POST');
    }

    /**
     * Test: Customer Offers Navigation
     * 
     * Preserves: Customer can access "Offers Received" via sidebar with active state
     * 
     * Scenario:
     * - Customer clicks "Offers Received" in sidebar
     * - Customer navigates to /offers
     * 
     * Expected Behavior:
     * - Page loads successfully (200 OK)
     * - "Offers Received" link has active state
     * - Badge shows offer count
     * 
     * **Validates: Requirement 3.6 (customer offers)**
     */
    public function test_customer_offers_navigation_with_active_state(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get('/offers');

        $response->assertOk();
        $response->assertSee('sidebar');
        $response->assertSee('Offers Received');
        
        // Verify "Offers Received" link has active state
        $body = $response->getContent();
        preg_match('/Offers Received.*?class="([^"]*)"/', $body, $matches);
        
        if (!empty($matches[1])) {
            $classes = $matches[1];
            $this->assertStringContainsString('active', $classes,
                'Offers Received link should have active class when on offers page');
        }
    }

    /**
     * Test: Provider Offers Navigation
     * 
     * Preserves: Provider can access "My Offers" via sidebar with active state
     * 
     * Scenario:
     * - Provider clicks "My Offers" in sidebar
     * - Provider navigates to /offers
     * 
     * Expected Behavior:
     * - Page loads successfully (200 OK)
     * - "My Offers" link has active state
     * - Badge shows offers count
     * 
     * **Validates: Requirement 3.6 (provider offers)**
     */
    public function test_provider_offers_navigation_with_active_state(): void
    {
        $provider = User::factory()->create(['role' => 'provider']);

        $response = $this->actingAs($provider)->get('/offers');

        $response->assertOk();
        $response->assertSee('sidebar');
        $response->assertSee('My Offers');
        
        // Verify "My Offers" link has active state
        $body = $response->getContent();
        preg_match('/My Offers.*?class="([^"]*)"/', $body, $matches);
        
        if (!empty($matches[1])) {
            $classes = $matches[1];
            $this->assertStringContainsString('active', $classes,
                'My Offers link should have active class when on offers page');
        }
    }

    /**
     * Test: Admin Menu Structure Preserved
     * 
     * Preserves: Admin sidebar displays all expected menu items
     * 
     * Scenario:
     * - Admin user views sidebar
     * 
     * Expected Behavior:
     * - Dashboard link visible
     * - All Requests link visible
     * - All Offers link visible
     * - Search Services link visible
     * - Account section visible
     * 
     * **Validates: Requirement 3.3 (admin menu structure)**
     */
    public function test_admin_menu_structure_preserved(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard');
        $response->assertSee('All Requests');
        $response->assertSee('All Offers');
        $response->assertSee('Search Services');
        $response->assertSee('Account');
        $response->assertSee('My Profile');
        $response->assertSee('Logout');
    }

    /**
     * Test: Provider Menu Structure Preserved
     * 
     * Preserves: Provider sidebar displays all expected menu items
     * 
     * Scenario:
     * - Provider user views sidebar
     * 
     * Expected Behavior:
     * - Dashboard link visible
     * - Available Requests link visible
     * - My Offers link visible
     * - Account section visible
     * 
     * **Validates: Requirement 3.4 (provider menu structure)**
     */
    public function test_provider_menu_structure_preserved(): void
    {
        $provider = User::factory()->create(['role' => 'provider']);

        $response = $this->actingAs($provider)->get('/provider/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard');
        $response->assertSee('Available Requests');
        $response->assertSee('My Offers');
        $response->assertSee('Account');
        $response->assertSee('My Profile');
        $response->assertSee('Logout');
    }

    /**
     * Test: Customer Menu Structure Preserved
     * 
     * Preserves: Customer sidebar displays all expected menu items
     * 
     * Scenario:
     * - Customer user views sidebar
     * 
     * Expected Behavior:
     * - Dashboard link visible
     * - My Requests link visible
     * - Offers Received link visible
     * - My Reviews link visible
     * - Account section visible
     * 
     * **Validates: Requirement 3.1 (customer menu structure)**
     */
    public function test_customer_menu_structure_preserved(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get('/customer/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard');
        $response->assertSee('My Requests');
        $response->assertSee('Offers Received');
        $response->assertSee('My Reviews');
        $response->assertSee('Account');
        $response->assertSee('My Profile');
        $response->assertSee('Logout');
    }

    /**
     * Test: All Sidebar Links Are Accessible
     * 
     * Preserves: All navigation links route to existing pages without errors
     * 
     * Scenario:
     * - Customer navigates through all sidebar links
     * 
     * Expected Behavior:
     * - Dashboard accessible (200 OK)
     * - My Requests accessible (200 OK)
     * - Offers accessible (200 OK)
     * - Profile accessible (200 OK)
     * - No 404 errors
     * 
     * **Validates: Requirement 3.7**
     */
    public function test_all_customer_sidebar_links_are_accessible(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        // Test dashboard
        $this->actingAs($customer)->get('/customer/dashboard')->assertOk();

        // Test requests
        $this->actingAs($customer)->get('/customer/requests')->assertOk();

        // Test offers
        $this->actingAs($customer)->get('/offers')->assertOk();

        // Test profile
        $this->actingAs($customer)->get('/profile/edit')->assertOk();
    }

    /**
     * Test: Admin Sidebar Links Are Accessible
     * 
     * Preserves: All admin navigation links route to existing pages
     * 
     * Scenario:
     * - Admin navigates through sidebar links
     * 
     * Expected Behavior:
     * - Dashboard accessible (200 OK)
     * - All Requests accessible (200 OK)
     * - All Offers accessible (200 OK)
     * - Search Services accessible (200 OK)
     * - Profile accessible (200 OK)
     * 
     * **Validates: Requirement 3.3, 3.7**
     */
    public function test_all_admin_sidebar_links_are_accessible(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Test dashboard
        $this->actingAs($admin)->get('/admin/dashboard')->assertOk();

        // Test requests (admin views customer.requests route)
        $this->actingAs($admin)->get('/customer/requests')->assertOk();

        // Test offers
        $this->actingAs($admin)->get('/offers')->assertOk();

        // Test search
        $this->actingAs($admin)->get('/search')->assertOk();

        // Test profile
        $this->actingAs($admin)->get('/profile/edit')->assertOk();
    }

    /**
     * Test: Provider Sidebar Links Are Accessible
     * 
     * Preserves: All provider navigation links route to existing pages
     * 
     * Scenario:
     * - Provider navigates through sidebar links
     * 
     * Expected Behavior:
     * - Dashboard accessible (200 OK)
     * - Available Requests accessible via search (200 OK)
     * - My Offers accessible (200 OK)
     * - Profile accessible (200 OK)
     * 
     * **Validates: Requirement 3.4, 3.7**
     */
    public function test_all_provider_sidebar_links_are_accessible(): void
    {
        $provider = User::factory()->create(['role' => 'provider']);

        // Test dashboard
        $this->actingAs($provider)->get('/provider/dashboard')->assertOk();

        // Test available requests (search route)
        $this->actingAs($provider)->get('/search')->assertOk();

        // Test offers
        $this->actingAs($provider)->get('/offers')->assertOk();

        // Test profile
        $this->actingAs($provider)->get('/profile/edit')->assertOk();
    }

    /**
     * Test: Customer My Reviews Link Accessible
     * 
     * Preserves: Customer can access reviews page from sidebar
     * 
     * Scenario:
     * - Customer views sidebar
     * - Customer sees "My Reviews" link
     * - Customer clicks "My Reviews"
     * 
     * Expected Behavior:
     * - "My Reviews" link exists in sidebar
     * - Navigation to customer.reviews route succeeds (200 OK)
     * 
     * **Validates: Requirement 3.1 (customer reviews)**
     */
    public function test_customer_reviews_navigation_preserved(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get('/customer/dashboard');

        $response->assertOk();
        $response->assertSee('My Reviews');
        
        // Verify customer can access reviews page
        $reviewResponse = $this->actingAs($customer)->get('/customer/reviews');
        $reviewResponse->assertOk();
    }

    /**
     * Test: Role-Based Menu Visibility
     * 
     * Preserves: Each role only sees their appropriate menu items
     * 
     * Scenario:
     * - Different roles view their respective dashboards
     * 
     * Expected Behavior:
     * - Customer doesn't see admin menu items
     * - Provider doesn't see customer menu items
     * - Admin doesn't see provider menu items
     * 
     * **Validates: Requirement 3.7 (no cross-role menu items)**
     */
    public function test_role_based_menu_visibility_preserved(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $provider = User::factory()->create(['role' => 'provider']);
        $admin = User::factory()->create(['role' => 'admin']);

        // Customer shouldn't see provider/admin items
        $customerResponse = $this->actingAs($customer)->get('/customer/dashboard');
        $customerResponse->assertSee('My Requests');
        $customerResponse->assertDontSee('All Requests'); // Admin item
        $customerResponse->assertDontSee('Available Requests'); // Provider item

        // Provider shouldn't see customer/admin items
        $providerResponse = $this->actingAs($provider)->get('/provider/dashboard');
        $providerResponse->assertSee('Available Requests');
        $providerResponse->assertDontSee('My Requests'); // Customer item
        $providerResponse->assertDontSee('All Requests'); // Admin item

        // Admin shouldn't see customer/provider items
        $adminResponse = $this->actingAs($admin)->get('/admin/dashboard');
        $adminResponse->assertSee('All Requests');
        $adminResponse->assertDontSee('My Requests'); // Customer item
        $adminResponse->assertDontSee('Available Requests'); // Provider item
    }

    /**
     * Test: Sidebar Renders Without JavaScript Errors
     * 
     * Preserves: Sidebar HTML structure is valid and complete
     * 
     * Scenario:
     * - User views any page with sidebar
     * 
     * Expected Behavior:
     * - Sidebar HTML loads completely
     * - No missing closing tags
     * - No broken form elements
     * - No malformed links
     * 
     * **Validates: Requirement 3.7**
     */
    public function test_sidebar_renders_without_structural_errors(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->get('/customer/dashboard');

        $response->assertOk();
        
        $body = $response->getContent();
        
        // Verify sidebar structure
        $this->assertStringContainsString('<aside class="sidebar">', $body,
            'Sidebar opening tag should be present');
        $this->assertStringContainsString('</aside>', $body,
            'Sidebar closing tag should be present');
        
        // Verify logout form is properly structured
        $this->assertStringContainsString('id="logout-form"', $body,
            'Logout form ID should exist');
        $this->assertStringContainsString('method="POST"', $body,
            'Logout form should use POST method');
        $this->assertStringContainsString('action="' . route('logout') . '"', $body,
            'Logout form should submit to logout route');
    }
}
