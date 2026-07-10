# Dashboard Quick Reference Guide

## 🎯 Quick Navigation

### Admin Dashboard Access
- **URL**: `/admin/dashboard`
- **Sidebar**: Dashboard (📊)
- **Full Menu**:
  - Dashboard (📊) → `/admin/dashboard`
  - All Requests (📋) → `/admin/requests`
  - All Offers (🤝) → `/admin/offers`
  - Reviews (⭐) → `/admin/reviews`
  - Users (👥) → `/admin/users`
  - Messages (💬) → `/messages`

### Provider Dashboard Access
- **URL**: `/provider/dashboard`
- **Sidebar**: Dashboard (📊)
- **Full Menu**:
  - Dashboard (📊) → `/provider/dashboard`
  - Available Requests (📋) → `/provider/requests`
  - My Offers (🤝) → `/provider/offers`
  - My Reviews (⭐) → `/provider/reviews`
  - Messages (💬) → `/messages`

### Customer Dashboard Access
- **URL**: `/customer/dashboard`
- **Sidebar**: Dashboard (📊)
- **Full Menu**:
  - Dashboard (📊) → `/customer/dashboard`
  - My Requests (📋) → `/customer/requests`
  - Offers Received (🤝) → `/offers`
  - My Reviews (⭐) → `/customer/reviews`
  - Messages (💬) → `/messages`

---

## 🔍 What Each Page Shows

### ADMIN PAGES

#### All Requests (`/admin/requests`)
- Filter by: Status, Category
- Sort: Latest/Oldest
- Table columns: ID, Title, Customer, Category, Budget, Status, Created
- Actions: View, Manage
- Pagination: 15 per page

#### All Offers (`/admin/offers`)
- Filter by: Status
- Sort: Latest/Oldest
- Table columns: ID, Request, Provider, Price, Status, Created
- Actions: View, Manage
- Pagination: 15 per page

#### Reviews (`/admin/reviews`)
- Filter by: Rating
- Sort: Latest/Oldest
- Table columns: ID, Reviewer, Reviewee, Rating, Comment, Created
- Actions: View
- Pagination: 15 per page

#### Users (`/admin/users`)
- Filter by: Role
- Search: Name/Email
- Sort: Latest/Oldest
- Table columns: ID, Name, Email, Phone, Role, Status, Joined
- Actions: View, Manage
- Pagination: 20 per page

---

### PROVIDER PAGES

#### Dashboard (`/provider/dashboard`)
- Stats: Total Offers, Pending, Accepted, Earnings
- Rating: Average rating with stars
- Quick Links: View all offers, requests, reviews
- Recent Offers: Last 5 offers sent
- Available Requests: 10 open requests to bid on

#### Available Requests (`/provider/requests`)
- Filter by: Category, Budget Range (Min/Max)
- Sort: Latest/Oldest
- Display: Card layout per request
- Actions: Send Offer, Message Customer
- Pagination: 15 per page

#### My Offers (`/provider/offers`)
- Filter by: Status (Pending, Accepted, Rejected, Completed)
- Sort: Latest/Oldest
- Table columns: ID, Request, Customer, Price, Status, Created, Message
- Actions: Message, View Details
- Pagination: 15 per page

#### My Reviews (`/provider/reviews`)
- Filter by: Rating (5★ to 1★)
- Sort: Latest/Oldest
- Display: Card layout per review
- Show: Reviewer name, rating, comment, date
- Stats: Average rating, total reviews, reputation status
- Pagination: 15 per page

---

## 🎨 Status Badges & Colors

### Request Status
- 🔵 **Open** - bg-blue-100 text-blue-700
- 🟡 **Assigned** - bg-yellow-100 text-yellow-700
- 🟢 **Completed** - bg-green-100 text-green-700
- 🔴 **Cancelled** - bg-red-100 text-red-700

### Offer Status
- 🟡 **Pending** - bg-yellow-100 text-yellow-700
- 🟢 **Accepted** - bg-green-100 text-green-700
- 🔴 **Rejected** - bg-red-100 text-red-700
- 🔵 **Completed** - bg-blue-100 text-blue-700

### User Status
- 🟢 **Verified** - bg-green-100 text-green-700
- 🟡 **Pending** - bg-yellow-100 text-yellow-700

---

## 💻 Filter & Sort Guide

### How to Filter
1. Find the **Filter** section at top of page
2. Select dropdown/input options
3. Click **Filter** button
4. Results update automatically
5. Click **Clear** to reset

### Filter Options by Page

**All Requests**:
- Status: open, assigned, completed, cancelled
- Category: Select from list
- Sort: Latest First / Oldest First

**All Offers**:
- Status: pending, accepted, rejected, completed
- Sort: Latest First / Oldest First

**Available Requests (Provider)**:
- Category: Select from list
- Min Budget: Enter amount
- Max Budget: Enter amount
- Sort: Latest First / Oldest First

**My Offers**:
- Status: pending, accepted, rejected, completed
- Sort: Latest First / Oldest First

**Reviews/My Reviews**:
- Rating: 5★, 4★, 3★, 2★, 1★
- Sort: Latest First / Oldest First

**Users**:
- Role: customer, provider, admin
- Search: Type name or email
- Sort: Latest First / Oldest First

---

## 📊 Dashboard Statistics

### Admin Dashboard Stats
- Total Users
- Total Customers
- Total Providers
- Total Requests
- Total Offers
- Total Categories

### Provider Dashboard Stats
- Total Offers Sent
- Pending Offers
- Accepted Offers
- Completed Offers
- **Total Earnings** (sum of completed offers)
- Average Rating
- Total Reviews
- Job Completion Rate
- Active Offers Count

---

## ⚙️ Common Tasks

### Admin Tasks
1. **View all service requests**
   - Go to All Requests page
   - Filter by status if needed
   - Click request to view details

2. **Monitor offers**
   - Go to All Offers page
   - Filter by status
   - Check pending offers

3. **Review customer feedback**
   - Go to Reviews page
   - Filter by rating
   - Read comments

4. **Manage users**
   - Go to Users page
   - Search or filter by role
   - View user details

### Provider Tasks
1. **Find requests to bid on**
   - Go to Available Requests
   - Filter by category/budget
   - Click "Send Offer"

2. **Track your offers**
   - Go to My Offers
   - Filter by status
   - Message customers

3. **Check your reputation**
   - Go to My Reviews
   - View rating & feedback
   - Sort by rating

### Customer Tasks
1. **Create a request**
   - Go to My Requests
   - Click "Create New Request"
   - Fill details & submit

2. **Review offers**
   - Go to Offers Received
   - View provider details
   - Accept or reject

---

## 🔐 Access Control

**Admin Routes** (role: admin only):
- /admin/dashboard
- /admin/requests
- /admin/offers
- /admin/reviews
- /admin/users

**Provider Routes** (role: provider only):
- /provider/dashboard
- /provider/offers
- /provider/requests
- /provider/reviews

**Customer Routes** (role: customer only):
- /customer/dashboard
- /customer/requests
- /offers
- /customer/reviews

**Shared Routes** (all authenticated users):
- /messages
- /profile
- /search

---

## 📱 Mobile Support

All dashboards are **fully responsive**:
- ✅ Mobile (< 768px)
- ✅ Tablet (768px - 1024px)
- ✅ Desktop (> 1024px)

On mobile:
- Tables scroll horizontally
- Filters stack vertically
- Buttons are larger for touch
- Layout adjusts automatically

---

## 🆘 Troubleshooting

### Page Not Loading
1. Check URL is correct
2. Verify you're logged in
3. Check your role (admin/provider/customer)
4. Clear browser cache
5. Try incognito mode

### Filters Not Working
1. Click "Clear" to reset filters
2. Select new filter option
3. Click "Filter" button
4. Wait for page to update

### No Data Showing
1. Check if data exists in system
2. Try clearing filters
3. Check pagination (might be on page 2+)
4. Try different sort order

### Pagination Issues
1. Check current page number
2. Click different page number
3. Try "Clear" button to reset
4. Check if data exists

---

## 📌 Quick Tips

1. **Bookmarks**: Bookmark your dashboard for quick access
2. **Filters**: Combine multiple filters for precise results
3. **Search**: Use search function on Users page
4. **Sort**: Click sort dropdown to reverse order
5. **Refresh**: Press F5 or Ctrl+R to refresh data
6. **Messages**: Click message link to talk with users
7. **Mobile**: Use responsive design on any device
8. **Export**: Data tables are easy to read in any browser

---

## 📞 Support

For issues or questions:
1. Check this guide first
2. Verify your role & permissions
3. Test in incognito mode
4. Check browser console (F12)
5. Contact admin if still issues

---

**Dashboard System Ready!** 🚀

All features are tested and working.
Start using your dashboards now!
