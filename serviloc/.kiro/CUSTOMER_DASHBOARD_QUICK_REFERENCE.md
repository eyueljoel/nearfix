# Customer Dashboard Pages - Quick Reference

## Pages Overview

### 1. Dashboard (`/customer/dashboard`)
**Status**: ✅ Previously completed
- Quick stats: Total Requests, Open Requests, Completed Requests
- Total Offers, Pending Offers
- Recent requests and offers
- Links to other pages via sidebar

### 2. My Requests (`/customer/requests`)
**Status**: ✅ Previously completed
- Table view of service requests
- Columns: Title, Category, Budget, Status, Offers Count, Created, Actions
- Sortable columns
- Links to create new request or view details

### 3. Offers Received (`/customer/offers`) 
**Status**: ✅ NOW COMPLETE
- **URL**: `/customer/offers`
- **Route Name**: `customer.offers`
- **Features**:
  - Filter by status: Pending, Accepted, Rejected
  - Sort: Latest, Oldest
  - Pagination: 15 per page
- **Card Layout**: Shows service request, provider, price, message, timestamp
- **Actions**: Accept/Reject buttons for pending offers

### 4. My Reviews (`/customer/reviews`)
**Status**: ✅ NOW COMPLETE
- **URL**: `/customer/reviews`
- **Route Name**: `customer.reviews`
- **Features**:
  - Filter by rating: 1-5 stars
  - Sort: Latest, Oldest
  - Pagination: 15 per page
- **Card Layout**: Shows provider info, rating, service request, review comment
- **Display**: Star ratings in gold, provider avatar with first letter

## Sidebar Navigation

### Customer Menu Items
- 📊 Dashboard → `/customer/dashboard`
- 📋 My Requests → `/customer/requests` (with badge showing count)
- 🤝 Offers Received → `/customer/offers` (with badge showing total offers)
- ⭐ My Reviews → `/customer/reviews`
- 💬 Messages → `/messages` (with unread badge)
- 👤 My Profile → `/profile/edit`
- 🚪 Logout

## Controller Methods

### DashboardController (app/Http/Controllers/Customer/DashboardController.php)

#### index()
- Shows dashboard home page with stats

#### requests()
- Lists customer's service requests with pagination

#### createRequest()
- Shows form to create new service request

#### storeRequest(Request $request)
- Stores new service request

#### showRequest($id)
- Shows details of specific service request

#### offers(Request $request) ✨ NEW
- Lists all offers on customer's requests
- Supports status filter: pending, accepted, rejected
- Supports sort: latest (default), oldest
- Paginates: 15 per page

#### reviews(Request $request) ✨ NEW
- Lists reviews given by customer
- Supports rating filter: 1-5 stars
- Supports sort: latest (default), oldest
- Paginates: 15 per page

## Data Relationships

### Offers Data Flow
```
Customer → ServiceRequests → Offers
         ↓                    ↓
         1                    Provider
```
**Includes**: serviceRequest, provider

### Reviews Data Flow
```
Customer → Reviews (as reviewer)
         ↓
      ServiceRequest
         ↓
      Reviewee (Provider)
```
**Includes**: serviceRequest, reviewee

## Filter & Sort Behavior

### Offers Page
- **Status Filter**: 
  - All (blank/no filter)
  - pending
  - accepted
  - rejected
- **Sort**:
  - latest (default, created_at DESC)
  - oldest (created_at ASC)
- **Clear Button**: Appears when filters are active

### Reviews Page
- **Rating Filter**:
  - All (blank/no filter)
  - 1, 2, 3, 4, 5 (stars)
- **Sort**:
  - latest (default, created_at DESC)
  - oldest (created_at ASC)
- **Clear Button**: Appears when filters are active

## Query Parameters

### Offers Page
- `?status=pending` - Filter by status
- `?sort=oldest` - Sort by oldest first
- `?page=2` - Navigate to page
- Multiple params: `?status=pending&sort=latest&page=1`

### Reviews Page
- `?rating=5` - Filter by 5-star reviews
- `?sort=oldest` - Sort by oldest first
- `?page=2` - Navigate to page
- Multiple params: `?rating=4&sort=latest&page=1`

## Empty States

### Offers Page
- **No offers**: Shows message "No offers yet. Providers will send offers on your requests."

### Reviews Page
- **No reviews**: Shows message "No reviews yet. Complete a request and leave a review for the provider."
- **No matching reviews**: Shows message "No reviews with this rating. Try adjusting your filters."

## Styling Notes

### Color Scheme
- Primary: #e94560 (red) - Used for buttons, prices, highlights
- Secondary: #6c757d (gray) - Used for secondary actions
- Success: #28a745 (green) - Accept buttons
- Danger: #dc3545 (red) - Reject buttons
- Warning: #fff3cd (light yellow) - Pending badges
- Info: #d4edda (light green) - Accepted badges
- Error: #f8d7da (light red) - Rejected badges

### Typography
- Headings: 18-28px, bold
- Body: 14px
- Small: 13px
- Timestamps: 13px, gray color #8895aa

### Layout
- Card-based design with Tailwind CSS
- Responsive: Works on desktop, tablet, mobile
- Spacing: 16px gaps between cards
- Border radius: 8-12px for cards and buttons
- Box shadows: Subtle shadows on cards

## Performance Considerations

- Uses `with()` for eager loading (prevents N+1 queries)
- Pagination limits to 15 items per page
- Indexes on foreign keys for fast filtering
- Consider adding database indexes on:
  - offers.status
  - offers.created_at
  - reviews.rating
  - reviews.created_at

## Testing Checklist

- [ ] Create test customer account
- [ ] Create test service requests
- [ ] Create test offers on those requests
- [ ] Create test reviews for completed requests
- [ ] Test filtering by status on offers
- [ ] Test sorting by latest/oldest on offers
- [ ] Test pagination on offers
- [ ] Test filtering by rating on reviews
- [ ] Test sorting by latest/oldest on reviews
- [ ] Test pagination on reviews
- [ ] Test sidebar navigation to both pages
- [ ] Test responsive design on mobile
- [ ] Test empty states on both pages
