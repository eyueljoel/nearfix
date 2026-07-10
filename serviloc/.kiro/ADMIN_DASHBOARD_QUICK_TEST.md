# Admin Dashboard - Quick Test Guide (10 minutes)

## ✅ Test Checklist

### 1. Verify Admin Sidebar is Correct
- [ ] Log in as **admin** user
- [ ] Check sidebar shows:
  - ✅ Dashboard (📊)
  - ✅ All Requests (📋)
  - ✅ All Offers (🤝)
  - ✅ Reviews (⭐)
  - ✅ Users (👥)
  - ✅ Messages (💬)

### 2. Test Requests Management
- [ ] Click "All Requests" in sidebar
- [ ] Should see table with all service requests
- [ ] Verify columns display:
  - ID
  - Title
  - Customer name
  - Category
  - Budget (formatted as ETB)
  - Status badge (colored)
  - Created date
- [ ] Test filters:
  - [ ] Filter by Status (select each: open, assigned, completed, cancelled)
  - [ ] Filter by Category (select a category)
  - [ ] Sort by Latest (default)
  - [ ] Sort by Oldest
  - [ ] Click "Clear" button
- [ ] Test pagination (if > 15 requests)

### 3. Test Offers Management
- [ ] Click "All Offers" in sidebar
- [ ] Should see table with all offers
- [ ] Verify columns display:
  - ID
  - Service Request title
  - Provider name
  - Price (formatted as ETB)
  - Status badge (colored)
  - Created date
- [ ] Test filters:
  - [ ] Filter by Status (pending, accepted, rejected, completed)
  - [ ] Sort by Latest/Oldest
  - [ ] Click "Clear" button
- [ ] Test pagination (if > 15 offers)

### 4. Test Reviews Management
- [ ] Click "Reviews" in sidebar
- [ ] Should see table with all reviews
- [ ] Verify columns display:
  - ID
  - Reviewer name
  - Reviewee name
  - Rating (show stars ⭐)
  - Comment preview
  - Created date
- [ ] Test filters:
  - [ ] Filter by Rating (select 5★, 4★, 3★, etc.)
  - [ ] Sort by Latest/Oldest
  - [ ] Click "Clear" button
- [ ] Test pagination (if > 15 reviews)

### 5. Test User Management
- [ ] Click "Users" in sidebar
- [ ] Should see table with all users
- [ ] Verify columns display:
  - ID
  - Name
  - Email
  - Phone (or dash if empty)
  - Role badge (colored)
  - Status (Verified or Pending)
  - Joined date
- [ ] Test filters:
  - [ ] Filter by Role (customer, provider, admin)
  - [ ] Search by name (type part of a user's name)
  - [ ] Search by email (type part of an email)
  - [ ] Sort by Latest/Oldest
  - [ ] Click "Clear" button
- [ ] Test pagination (if > 20 users)

### 6. Test Messages Badge
- [ ] Messages link in admin sidebar should show unread count
- [ ] Click "Messages" should go to `/messages` route
- [ ] Should see admin's conversations (if any)

### 7. Test Responsive Design
- [ ] Open each admin page on mobile view
- [ ] Tables should scroll horizontally
- [ ] Filters should stack vertically
- [ ] Buttons should be tappable
- [ ] Text should be readable

### 8. Verify Correct Admin-Only Routes
- [ ] Admin should NOT see `/customer/requests` in sidebar
- [ ] Admin should NOT see `/provider/dashboard` in sidebar
- [ ] Admin SHOULD see `/admin/requests` instead of `/customer/requests`
- [ ] Admin SHOULD see `/admin/offers` instead of `/offers`

---

## 🧪 Test Results

### Test Environment
- **Browser**: ___________
- **OS**: ___________
- **Date**: ___________
- **Tester**: ___________

### Results
```
✅ = Working correctly
❌ = Issue found
⏳ = Not tested
```

| Test | Status | Notes |
|------|--------|-------|
| Sidebar displays correctly | [ ] ✅ [ ] ❌ | ________ |
| Requests page loads | [ ] ✅ [ ] ❌ | ________ |
| Requests filters work | [ ] ✅ [ ] ❌ | ________ |
| Offers page loads | [ ] ✅ [ ] ❌ | ________ |
| Offers filters work | [ ] ✅ [ ] ❌ | ________ |
| Reviews page loads | [ ] ✅ [ ] ❌ | ________ |
| Reviews filters work | [ ] ✅ [ ] ❌ | ________ |
| Users page loads | [ ] ✅ [ ] ❌ | ________ |
| Users filters work | [ ] ✅ [ ] ❌ | ________ |
| User search works | [ ] ✅ [ ] ❌ | ________ |
| Pagination works | [ ] ✅ [ ] ❌ | ________ |
| Mobile responsive | [ ] ✅ [ ] ❌ | ________ |
| No console errors | [ ] ✅ [ ] ❌ | ________ |

---

## 📝 Issues Found

**Issue #1**: _______________
- **Page**: _______________
- **Description**: _______________
- **Steps to reproduce**: _______________
- **Expected**: _______________
- **Actual**: _______________

**Issue #2**: _______________
- **Page**: _______________
- **Description**: _______________
- **Steps to reproduce**: _______________
- **Expected**: _______________
- **Actual**: _______________

---

## ✅ Test Sign-Off

- [ ] All basic tests passed
- [ ] No critical issues found
- [ ] Ready for production

**Tested by**: ________________  
**Date**: ________________  
**Signature**: ________________

---

## 🚀 Next Steps

After testing is complete:

1. **If all tests pass** ✅
   - Dashboard is ready to use
   - Can be deployed to production
   - Users can start managing through admin panel

2. **If issues found** ❌
   - Report issues with details
   - I'll fix them
   - Re-test

3. **Optional enhancements**
   - Add admin actions (delete, edit, deactivate)
   - Add analytics/charts
   - Add bulk operations
   - Add email notifications

---

## 💡 Tips for Testing

- **Clear filters button**: Use to reset all filters to "All"
- **Sorting**: Click sort dropdown to see Latest/Oldest options
- **Search**: Works on Users page (name/email)
- **Filters**: Select from dropdowns, then click "Filter"
- **Pagination**: Shows if more than limit (15 requests/offers/reviews, 20 users)
- **Mobile**: Use browser's mobile view (F12 → Device Mode)
- **Console**: Check F12 Console for any JavaScript errors

---

## 📞 Support

If you find any issues:
1. Document the issue with details from the form above
2. Note the exact steps to reproduce
3. Share screenshots if helpful
4. Report back with the issue details

**Admin Dashboard testing complete!** 🎉
