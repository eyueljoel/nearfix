# Requirements: Admin Dashboard

## Overview

The admin dashboard provides ServiLoc administrators with comprehensive tools to manage users, service requests, offers, and reviews. It enables platform oversight, moderation, and analytics without external tools.

---

## User Stories

### Story 1: View Dashboard Statistics
**As an** admin  
**I want to** see key platform metrics at a glance  
**So that** I can monitor platform health and activity

**Acceptance Criteria:**
- Dashboard displays 6+ key metrics (users, customers, providers, requests, offers, reviews)
- Metrics update in real-time when new data is added
- Display previous period comparison (e.g., "↑ 12% from last week")
- Show trends with small chart sparklines

### Story 2: Manage Users
**As an** admin  
**I want to** view, search, filter, and manage all users  
**So that** I can moderate user accounts and resolve issues

**Acceptance Criteria:**
- List all users with pagination (25 per page)
- Filter by role (customer, provider, admin)
- Filter by status (active, suspended, banned)
- Search by name or email
- View user profile and activity
- Suspend/unsuspend user account
- Ban/unban user account
- Delete user (with confirmation)
- View user's requests/offers/reviews

### Story 3: Manage Service Requests
**As an** admin  
**I want to** view, search, and moderate service requests  
**So that** I can ensure content quality and resolve disputes

**Acceptance Criteria:**
- List all requests with pagination (25 per page)
- Filter by status (open, assigned, completed, cancelled)
- Filter by category
- Search by title or description
- Sort by date, budget, or status
- View request details and conversation history
- Mark request as flagged/reviewed
- Delete request (with confirmation and reason)
- Assign provider to request (if unassigned)
- Pause/unpause request

### Story 4: Manage Offers
**As an** admin  
**I want to** view and moderate offers  
**So that** I can prevent spam and ensure quality

**Acceptance Criteria:**
- List all offers with pagination
- Filter by status (pending, accepted, rejected)
- Filter by request or provider
- Search by title
- View offer details
- Flag offer as suspicious
- Delete offer (with confirmation)
- View provider reputation

### Story 5: Manage Reviews
**As an** admin  
**I want to** view, flag, and moderate reviews  
**So that** I can prevent fake reviews and maintain platform integrity

**Acceptance Criteria:**
- List all reviews with pagination
- Filter by rating (1-5 stars)
- Filter by status (flagged, verified)
- Search by reviewer or reviewee name
- View review text and context
- Flag review as suspicious/fake
- Verify legitimate reviews
- Delete review (with confirmation)
- View reviewer history

### Story 6: View Platform Analytics
**As an** admin  
**I want to** see analytics and trends  
**So that** I can understand platform usage patterns

**Acceptance Criteria:**
- View requests created per day (last 30 days)
- View offers accepted per day (last 30 days)
- View revenue trends (budget spent)
- View user registration trends
- View category popularity
- Export data as CSV

### Story 7: Search & Filter
**As an** admin  
**I want to** quickly find specific data  
**So that** I can resolve issues efficiently

**Acceptance Criteria:**
- Global search box that searches users, requests, offers
- Advanced filters for each section
- Save common filter presets
- Clear all filters button
- Search results show 50 items max

### Story 8: Audit Trail
**As an** admin  
**I want to** see audit logs of admin actions  
**So that** I can track what changes were made and by whom

**Acceptance Criteria:**
- Log all admin actions (view, edit, delete, suspend)
- Show timestamp and admin who performed action
- Show affected user/request/offer details
- Filter audit log by action type
- Filter audit log by date range
- View audit log for specific user/request

---

## Functional Requirements

### FR1: Dashboard Overview
- Display 6 key metrics in stat cards
- Show top 10 recent service requests
- Show top 5 trending categories
- Show recent reviews with ratings
- Show admin activity log (last 20 actions)

### FR2: User Management
- Display user list with columns: Name, Email, Role, Status, Joined, Actions
- Inline quick actions: View, Edit, Suspend, Delete
- User profile modal showing: Profile info, requests, offers, reviews, messages
- Bulk actions: Select multiple users, suspend/delete in batch
- Search and filter capabilities
- Export user list as CSV

### FR3: Request Management
- Display request list with columns: Title, Customer, Status, Budget, Category, Created, Actions
- View request detail page with: Description, location, budget, timeline, offers, messages
- Inline quick actions: View, Flag, Assign provider, Delete
- Edit request details if needed
- View all offers for request
- View conversation history
- Assign provider if unassigned

### FR4: Offer Management
- Display offer list with columns: Provider, Request, Amount, Status, Created, Actions
- View offer detail page
- Accept/Reject offer on behalf of system (if needed)
- Delete suspicious offers
- Flag for review

### FR5: Review Management
- Display review list with columns: Reviewer, Reviewee, Rating, Text, Status, Created, Actions
- View review detail page
- Flag as suspicious/fake
- Verify legitimate reviews
- Delete reviews

### FR6: Analytics Dashboard
- Charts showing: Requests over time, Offers over time, User growth, Category popularity
- Export capability for all data
- Date range filters for all charts

---

## Non-Functional Requirements

### NFR1: Performance
- Admin dashboard loads in < 2 seconds
- Data tables paginate at 25 items per page
- Search returns results in < 500ms
- No N+1 queries

### NFR2: Security
- Admin access only (role check on every page)
- CSRF protection on all forms
- Audit logging for all admin actions
- Confirm destructive actions (delete, suspend)
- Rate limit admin searches (prevent data scraping)

### NFR3: Usability
- Consistent design with rest of platform
- Clear navigation between sections
- Responsive design (mobile admin access)
- Keyboard shortcuts for power users (optional)
- Real-time notifications for critical events

### NFR4: Data Integrity
- No accidental data loss (confirmation on delete)
- Soft deletes for user/request data (optionally preserve)
- Preserve audit trail of all changes
- Referential integrity enforcement

---

## Technical Specifications

### Database Considerations
- Add `admin_logs` table to track admin actions
- Add `flagged_content` table to track flagged reviews/offers
- Add `user_status` column (active/suspended/banned)
- Add timestamps to track user/request creation dates

### Views Required
- `admin/dashboard` - Main dashboard with stats
- `admin/users/index` - User list
- `admin/users/show` - User detail
- `admin/users/edit` - Edit user
- `admin/requests/index` - Request list
- `admin/requests/show` - Request detail
- `admin/offers/index` - Offer list
- `admin/offers/show` - Offer detail
- `admin/reviews/index` - Review list
- `admin/reviews/show` - Review detail
- `admin/analytics` - Analytics dashboard
- `admin/audit-log` - Audit log

### Controllers Required
- `AdminDashboardController` - Dashboard stats
- `AdminUserController` - User management
- `AdminRequestController` - Request management
- `AdminOfferController` - Offer management
- `AdminReviewController` - Review management
- `AdminAnalyticsController` - Analytics
- `AdminAuditLogController` - Audit logging

### Routes Required
- `/admin/dashboard` - Dashboard
- `/admin/users` - User list
- `/admin/users/{id}` - User detail
- `/admin/users/{id}/edit` - Edit user
- `/admin/requests` - Request list
- `/admin/requests/{id}` - Request detail
- `/admin/offers` - Offer list
- `/admin/offers/{id}` - Offer detail
- `/admin/reviews` - Review list
- `/admin/reviews/{id}` - Review detail
- `/admin/analytics` - Analytics
- `/admin/audit-log` - Audit log

---

## Success Criteria

✅ Admin can view comprehensive dashboard with key metrics  
✅ Admin can search and filter all platform data  
✅ Admin can view user profiles and activity  
✅ Admin can moderate content (flag, delete, suspend)  
✅ Admin can view analytics and trends  
✅ All admin actions are logged for audit trail  
✅ Performance is optimal (< 2s page loads)  
✅ Security is enforced (role checks, confirmations)  
✅ Design is consistent with platform  
✅ Responsive on mobile devices  

---

## Out of Scope (Future Enhancements)

- Bulk email to users
- Advanced reporting with custom queries
- Message templating for suspensions
- Two-factor authentication for admin accounts
- Real-time notifications dashboard
- Mobile app for admin
- Scheduled reports
- Integration with external analytics tools
