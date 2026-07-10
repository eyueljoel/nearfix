# Implementation Plan

## Phase 1: Bug Condition Exploration Tests

- [x] 1. Write bug condition exploration tests
  - **Property 1: Bug Condition** - Sidebar Navigation Bugs Surface Counterexamples
  - **CRITICAL**: This test MUST FAIL on unfixed code - failure confirms the bugs exist
  - **DO NOT attempt to fix the test or the code when it fails**
  - **GOAL**: Surface counterexamples that demonstrate the bugs exist
  - **Test Approach**: Write property-based or targeted unit tests that check:
    1. Provider attempting Reviews link: verify it routes to customer.reviews (bug confirmed)
    2. User navigating to profile pages: verify "My Profile" link lacks `.active` class (bug confirmed)
    3. Admin navigation links: verify no active state matching on "All Requests" and "All Offers"
    4. Provider navigation links: verify no active state matching on "Available Requests"
    5. Emoji consistency: verify icons use mixed styles across sections
  - **Test Implementation**: Create tests in `tests/Feature/SidebarNavigationBugsTest.php` or similar
    - Test that provider sidebar includes "Reviews" link pointing to `route('customer.reviews')`
    - Test that profile page (e.g., `/profile/edit`) does NOT have "My Profile" link with `.active` class
    - Test that emoji icons are inconsistent (mix of different styles: 📊, 📝, 💬, ✅, 🚪, ⭐)
    - Test that admin "All Requests" link has no route matching for requests pages
    - Test that provider "Available Requests" link has no route matching for search route
  - **Run test on UNFIXED code**
  - **EXPECTED OUTCOME**: Test FAILS (this is correct - it proves the bugs exist)
  - **Documentation**: Document each counterexample found:
    - "Provider Reviews link routes to: customer.reviews (should be provider.reviews or hidden)"
    - "Profile page active state: missing from My Profile link"
    - "Emoji styles found: 📊, 📝, 💬, ✅, 🚪, ⭐ (inconsistent)"
    - "Admin All Requests active state: not matching requests routes"
    - "Provider Available Requests active state: not matching search routes"
  - Mark task complete when test is written, run, and failures are documented
  - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6_

## Phase 2: Preservation Property Tests

- [x] 2. Write preservation property tests (BEFORE implementing fix)
  - **Property 2: Preservation** - Non-Buggy Sidebar Behavior Preserved
  - **IMPORTANT**: Follow observation-first methodology
  - **Observe behavior on UNFIXED code** for these non-buggy cases:
    - Customer clicks Dashboard → navigates to `customer.dashboard` with active state
    - Customer clicks "My Requests" → navigates to `customer.requests` with active state
    - Admin clicks Dashboard → navigates to `admin.dashboard` with active state
    - Provider clicks Dashboard → navigates to `provider.dashboard` with active state
    - User clicks logout → logout form submitted successfully
    - User accesses profile via navbar → navigates to `profile.edit` correctly
    - Customer clicks "Offers Received" → navigates to `offers.index` with active state
    - Provider clicks "My Offers" → navigates to `offers.index` with active state
  - **Write property-based tests** capturing observed behavior patterns:
    - For customer users: dashboard, requests, offers received links work correctly
    - For admin users: dashboard, all requests, all offers, search links work correctly
    - For provider users: dashboard, available requests, my offers links work correctly
    - For all users: profile access and logout functionality work correctly
    - For all users: all sidebar links are accessible and don't throw errors
  - **Property-based testing generates many test cases** for stronger guarantees across:
    - Different user roles (admin, provider, customer)
    - Different current routes (dashboard, requests, offers, profile, search)
    - Edge cases in route matching and active state application
  - **Run tests on UNFIXED code**
  - **EXPECTED OUTCOME**: Tests PASS (this confirms baseline behavior to preserve)
  - **Test Location**: Add to `tests/Feature/SidebarNavigationBugsTest.php` or create `tests/Feature/SidebarPreservationTest.php`
  - Mark task complete when tests are written, run, and passing on unfixed code
  - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7_

## Phase 3: Implementation

- [-] 3. Fix sidebar navigation bugs

  - [x] 3.1 Implement the fix
    - **File**: `resources/views/layouts/app.blade.php`
    - **Changes**:
      1. **Fix Provider Reviews Route** (Requirement 2.1)
         - Option: Hide "Reviews" link from provider sidebar until `provider.reviews` route exists
         - Use conditional check: `@if(Route::has('provider.reviews'))`
         - Or remove the link entirely from provider menu
         - Prevents providers from accessing `customer.reviews` route
      
      2. **Add Active State Matching for Account Section Links** (Requirement 2.2)
         - "My Profile" link: Add `{{ request()->routeIs('profile.*') ? 'active' : '' }}`
         - Matches both `profile.show` and `profile.edit` routes
         - Note: Logout link is a POST form, doesn't need active state (it's an action)
      
      3. **Standardize Emoji Icons Across All Sections** (Requirement 2.3)
         - Dashboard: 📊 (chart)
         - My Requests/All Requests: 📋 (clipboard)
         - My Offers/All Offers/Offers Received: 🤝 (handshake)
         - Reviews/My Reviews: ⭐ (star)
         - My Profile: 👤 (person)
         - Logout: 🚪 (door)
         - Available Requests/Search: 🔍 (search)
         - Ensure all icons use consistent emoji style (semantic, action-oriented)
      
      4. **Add Active State Matching for Admin Navigation Items** (Requirement 2.4)
         - "All Requests" link: Add `{{ request()->routeIs('admin.requests') ? 'active' : '' }}`
         - "All Offers" link: Add `{{ request()->routeIs('admin.offers') ? 'active' : '' }}`
         - Or adjust route patterns to match actual admin routes
      
      5. **Add Active State Matching for Provider Navigation Items** (Requirement 2.5)
         - "Available Requests" link: Add `{{ request()->routeIs('provider.search*') ? 'active' : '' }}`
         - "My Offers" link: Verify existing route matching works correctly
         - Adjust patterns to match actual provider routes
    
    - _Bug_Condition: From design section - provider accessing customer.reviews, missing active states, inconsistent icons_
    - _Expected_Behavior: From design section - correct routing, active state indicators, consistent styling_
    - _Preservation: All non-buggy navigation items continue to work identically_
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5_

  - [x] 3.2 Verify bug condition exploration test now passes
    - **Property 1: Expected Behavior** - Sidebar Navigation Bugs Fixed
    - **IMPORTANT**: Re-run the SAME test from task 1 - do NOT write a new test
    - The test from task 1 encodes the expected behavior for buggy inputs
    - When this test passes, it confirms all bugs are fixed
    - Run bug condition exploration test from step 1
    - **Test should now verify**:
      - Provider "Reviews" link either hidden or routes to `provider.reviews`
      - Profile page (`/profile/edit`) shows "My Profile" with `.active` class
      - Emoji icons are consistent across all sections
      - Admin "All Requests" and "All Offers" have active state matching
      - Provider "Available Requests" has active state matching
    - **EXPECTED OUTCOME**: Test PASSES (confirms bugs are fixed)
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6_

  - [x] 3.3 Verify preservation tests still pass
    - **Property 2: Preservation** - Non-Buggy Behavior Unchanged
    - **IMPORTANT**: Re-run the SAME tests from task 2 - do NOT write new tests
    - Run preservation property tests from step 2
    - **Tests should still verify**:
      - Customer dashboard, requests, offers links work correctly
      - Admin dashboard, requests, offers, search links work correctly
      - Provider dashboard, requests, offers links work correctly
      - Profile access and logout functionality unchanged
      - All sidebar links remain accessible and functional
    - **EXPECTED OUTCOME**: Tests PASS (confirms no regressions)
    - **If any test fails**: Investigate the regression and adjust the fix
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7_

- [x] 4. Checkpoint - Ensure all tests pass
  - Verify all exploration tests pass (buggy inputs now fixed)
  - Verify all preservation tests pass (non-buggy inputs unchanged)
  - Run full test suite to ensure no other regressions
  - Browser test (if available): Manually verify sidebar displays correctly for each role
  - Browser test: Manually verify active state updates when navigating between pages
  - Confirm all emoji icons are consistent and semantic
  - _Requirements: All (1.1-1.6, 2.1-2.5, 3.1-3.7)_

## Specification References

**Design Document Sections:**
- Bug Condition (isBugCondition): Design § Bug Condition
- Expected Behavior (expectedBehavior): Design § Expected Behavior
- Preservation Requirements: Design § Preservation Requirements
- Fix Implementation Details: Design § Fix Implementation
- Testing Strategy: Design § Testing Strategy

**Requirement Clauses:**
- **1.1**: Bug - Provider Reviews route (currently `customer.reviews`)
- **1.2**: Bug - Profile active state missing from "My Profile" link
- **1.3**: Bug - Emoji icons inconsistent across sections
- **1.4**: Bug - Admin navigation items lack active state matching
- **1.5**: Bug - Provider navigation items lack active state matching
- **1.6**: Bug - No route validation for sidebar links

- **2.1**: Fix - Correct provider "Reviews" link routing or hide it
- **2.2**: Fix - Add active state matching for Account section links
- **2.3**: Fix - Standardize emoji icons across all sections
- **2.4**: Fix - Add active state matching for admin navigation items
- **2.5**: Fix - Add active state matching for provider navigation items

- **3.1**: Preserve - Customer dashboard navigation
- **3.2**: Preserve - Customer requests navigation
- **3.3**: Preserve - Admin dashboard and navigation
- **3.4**: Preserve - Provider dashboard and navigation
- **3.5**: Preserve - Profile access and logout functionality
- **3.6**: Preserve - Offers navigation across roles
- **3.7**: Preserve - All sidebar links remain accessible
