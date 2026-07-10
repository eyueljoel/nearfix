# ServiLoc Messaging System - Testing Guide

## Overview
This guide walks you through testing the messaging system end-to-end. The system is fully implemented and ready for validation.

---

## ✅ Pre-Test Checklist

Before testing, verify all components are in place:

```bash
# Check all required files exist:
- app/Models/Message.php ✓
- app/Models/MessageRead.php ✓
- app/Http/Controllers/MessageController.php ✓
- app/Http/Requests/StoreMessageRequest.php ✓
- resources/views/messages/inbox.blade.php ✓
- resources/views/messages/show.blade.php ✓
- database/migrations/*_create_messages_table.php ✓
- database/migrations/*_create_message_reads_table.php ✓

# Verify migrations ran:
php artisan migrate:status
# Should show both messages migrations as "Ran"
```

---

## 📋 Test Scenario 1: Basic Message Flow

### Setup
1. Create 2 test users (different roles):
   - **User A**: Customer (email: customer@test.com, password: password)
   - **User B**: Provider (email: provider@test.com, password: password)

2. Create a service request:
   - Login as User A (Customer)
   - Go to `/customer/requests/create`
   - Fill form: Title, Description, Budget, Location, Category
   - Submit to create request

3. Create an offer:
   - Logout, login as User B (Provider)
   - Go to `/search`
   - Find the request created above
   - Click "Create Offer"
   - Submit offer with price and description

### Expected Result After Setup
- Service request shows in User A's "My Requests"
- Service request shows in User B's "My Offers"
- Offer appears in User A's "Offers Received"

---

## 🧪 Test: Send First Message (Customer → Provider)

### Steps
1. **Login as Customer (User A)**
   - Navigate to `/messages`
   - Should see "No conversations yet" (empty state)
   - Click on service request link to view it

2. **Navigate to conversation**
   - Go back to the service request detail page (`/customer/requests/{id}`)
   - Look for a "Message Provider" button or section (or click Messages in sidebar)
   - Click to start messaging

3. **Send message**
   - Type message: "Hi, I'm interested in your offer. Can we discuss details?"
   - Character count should show "X / 2000 characters"
   - Send button should be enabled
   - Click Send

### Expected Results
- ✅ Message appears in conversation thread
- ✅ Message shows as sent (blue bubble on right side)
- ✅ Shows timestamp of message
- ✅ Redirect back to conversation thread
- ✅ Success message displays: "Message sent successfully!"

---

## 🧪 Test: Receive and Read Message (Provider → Customer)

### Steps
1. **Login as Provider (User B)**
   - Navigate to `/messages`
   - Should see 1 conversation in inbox
   - Should show customer name, last message preview, unread badge with "1"

2. **Click conversation**
   - Click on conversation row
   - Should see full message thread
   - Customer's message shows on left (green bubble)
   - Shows customer's name and timestamp
   - Reply form shown at bottom

3. **Auto-mark as read**
   - Message should automatically mark as read when viewing thread
   - Read indicator ("✓ Read") should appear
   - Return to inbox
   - Unread badge should disappear on that conversation (now shows "✓")

### Expected Results
- ✅ Inbox shows conversation with correct other party name
- ✅ Last message preview shows
- ✅ Unread badge displays "1"
- ✅ Clicking conversation opens full thread
- ✅ Message auto-marks as read
- ✅ Badge changes to "✓" read indicator

---

## 🧪 Test: Provider Replies (Provider → Customer)

### Steps
1. **Still logged in as Provider**
   - In conversation thread, type reply: "Yes, I can help! Happy to discuss."
   - Click Send

2. **Verify message created**
   - Should see in thread as blue bubble on right
   - Shows provider's name and timestamp

3. **Check inbox badge**
   - Click Messages in sidebar
   - Go back to inbox
   - Conversation now has provider's message
   - Badge should show "✓" (no new unread for sender)

### Expected Results
- ✅ Reply appears in thread (blue bubble on right)
- ✅ Timestamp is correct
- ✅ Character counter works
- ✅ Send button only enabled if message has content

---

## 🧪 Test: Customer Receives Reply

### Steps
1. **Logout Provider, Login as Customer**

2. **Navigate to messages**
   - Go to `/messages`
   - Should see conversation with provider name
   - Last message preview shows: "Yes, I can help! Happy to..."
   - Should show unread badge "1"

3. **Click conversation**
   - Full thread shows both messages in order
   - Customer's message (left/green)
   - Provider's reply (right/blue) - should be unread
   - Auto-marks as read when viewing

### Expected Results
- ✅ Inbox shows updated last message
- ✅ Unread badge appears "1"
- ✅ Conversation opens with full thread
- ✅ Messages in correct order (chronological)
- ✅ Message auto-marks as read

---

## 🧪 Test: Multiple Conversations

### Steps
1. **Create second service request** (logged in as Customer A)
   - Create new request: "Electrical work"
   - Different from first request

2. **Login as different Provider (User C)**
   - Create offer on second request

3. **Message exchange**
   - Customer sends message about second request to User C
   - User C replies

4. **Check inbox**
   - Login as Customer A
   - Go to `/messages`
   - Should see 2 conversations
   - Each with different provider name, different last messages
   - Pagination shows correctly (if > 15 conversations, should paginate)

### Expected Results
- ✅ Inbox shows both conversations
- ✅ Each grouped by (request + other party)
- ✅ Most recent message first
- ✅ Unread counts correct for each
- ✅ Can navigate between conversations
- ✅ Pagination works if many conversations

---

## 🧪 Test: Unread Badge in Sidebar

### Steps
1. **Create 3 unread messages**
   - User A sends 3 messages to different providers
   - Don't mark them as read

2. **Login as Provider who received messages**
   - Look at sidebar
   - Messages link should show badge: "3"

3. **Read one message**
   - Click Messages
   - Open first conversation
   - Auto-marks as read

4. **Check badge again**
   - Sidebar should still show "3" (it's total unread)
   - This is correct - the badge shows unread for THIS user

### Expected Results
- ✅ Badge shows in sidebar next to Messages
- ✅ Badge counts unread messages correctly
- ✅ Badge updates after marking as read
- ✅ Works for all roles (customer, provider, admin)

---

## 🧪 Test: Message Validation

### Test Case 1: Empty Message
**Steps:**
1. Open conversation
2. Leave message field empty
3. Try to click Send

**Expected:** Send button should be disabled (grayed out)

### Test Case 2: Message Too Long
**Steps:**
1. Open conversation
2. Paste 2500+ character message
3. Try to send

**Expected:** Validation error: "Message cannot exceed 2000 characters."

### Test Case 3: Unauthorized User
**Steps:**
1. Login as User A (not participant in a request)
2. Try to send message via form manipulation/direct URL
3. Submit StoreMessageRequest manually

**Expected:** 403 Forbidden - "You are not authorized to send this message"

### Test Case 4: Invalid Service Request
**Steps:**
1. Try to send message with service_request_id = 99999 (doesn't exist)

**Expected:** Validation error: "Invalid service request"

### Test Case 5: Message to Self
**Steps:**
1. Try to send message with recipient_id = auth()->id()

**Expected:** Validation error: "You cannot message yourself"

---

## 🧪 Test: Pagination

### Setup
1. Create 20+ service requests
2. Create 20+ conversation exchanges

### Test Inbox Pagination
1. Go to `/messages`
2. If > 15 conversations, pagination links should appear
3. Click next page
4. Different conversations load
5. Can navigate back to page 1

### Test Message Thread Pagination
1. Open a conversation with 20+ messages
2. Pagination should appear (if implemented)
3. Can navigate between message pages

### Expected Results
- ✅ Inbox paginated at 15 per page
- ✅ Messages paginated at 20 per page
- ✅ Navigation links work correctly
- ✅ Page indicator shows current page

---

## 🧪 Test: UI/UX Features

### Character Counter
- [ ] Displays "0 / 2000" initially
- [ ] Updates as you type: "45 / 2000"
- [ ] Works in real-time
- [ ] Max 2000 shows as "2000 / 2000"

### Message Styling
- [ ] Own messages: Blue background, right-aligned
- [ ] Other party: Gray background, left-aligned
- [ ] Avatar shows initials
- [ ] Timestamps format: "May 10, 2:30 PM"

### Empty States
- [ ] Empty inbox shows: "No conversations yet" with helpful message
- [ ] Empty message thread: "No messages yet. Start the conversation!"
- [ ] Buttons visible to browse requests/offers

### Responsive Design
- [ ] On desktop: 3-column layout (sidebar, messages, request details)
- [ ] On tablet: 2-column (hide sidebar or stack)
- [ ] On mobile: Full-width message thread
- [ ] All text readable, buttons clickable

---

## 🧪 Test: Authorization & Security

### Test: User Cannot View Other's Conversation
1. User A and User B have conversation
2. User C tries to access: `/messages/{service_request_id}`
3. Should get 403 Forbidden

### Test: User Cannot Send Unauthorized Message
1. User A tries to send message about User B's request (not a participant)
2. Form validation/authorization should reject

### Test: CSRF Token Protection
1. Check form includes `@csrf`
2. Try POST without CSRF token
3. Should get 419 Token Mismatch error

### Test: XSS Prevention
1. Send message with HTML: `<script>alert('xss')</script>`
2. Should display as text, not execute
3. Check page source - script tags should be escaped

---

## 📊 Test Results Checklist

Use this to track your testing:

```
FUNCTIONALITY TESTS
- [ ] Customer can send message to provider
- [ ] Provider can receive message
- [ ] Messages auto-mark as read when viewed
- [ ] Read status displays correctly
- [ ] Unread badge shows correct count
- [ ] Inbox lists all conversations
- [ ] Conversations grouped correctly
- [ ] Most recent message first in list
- [ ] Pagination works (inbox)
- [ ] Pagination works (message thread)

VALIDATION TESTS
- [ ] Empty message rejected
- [ ] Message too long rejected
- [ ] Invalid recipient rejected
- [ ] Non-participant cannot message

AUTHORIZATION TESTS
- [ ] Unauthorized user gets 403
- [ ] Only participants see conversation
- [ ] Only recipient can mark as read

SECURITY TESTS
- [ ] CSRF protection works
- [ ] XSS prevention (HTML escaped)
- [ ] SQL injection prevention

UI/UX TESTS
- [ ] Character counter works
- [ ] Send button disabled when empty
- [ ] Message styling (left/right bubbles)
- [ ] Timestamps display correctly
- [ ] Empty state messages show
- [ ] Responsive on mobile/tablet/desktop
- [ ] Unread badge in sidebar works
```

---

## 🔧 If Tests Fail

### Problem: Page shows 404 or blank
**Solution:** 
- Clear view cache: `php artisan view:clear`
- Clear config cache: `php artisan config:clear`
- Refresh browser (Ctrl+F5)

### Problem: Messages table doesn't exist
**Solution:**
- Run migrations: `php artisan migrate`
- Check: `php artisan migrate:status`

### Problem: Unread badge doesn't show
**Solution:**
- Check Message model has `unreadCountForUser()` method
- Verify sidebar includes: `\App\Models\Message::unreadCountForUser($user)`

### Problem: Authorization fails
**Solution:**
- Verify StoreMessageRequest.php authorize() method
- Check user is actual participant in request
- Verify assigned_provider_id is set on request

### Problem: Character counter not updating
**Solution:**
- Check JavaScript in show.blade.php
- Verify `<textarea id="body">` has correct ID
- Check browser console for JS errors

---

## 📝 Notes for Manual Testing

1. **Create test users** with different roles before starting
2. **Use real-looking data** (complete forms fully)
3. **Test on multiple browsers** if possible (Chrome, Firefox, Safari)
4. **Check mobile** using DevTools responsive design mode
5. **Look at Network tab** to verify no 404s or 500s
6. **Check browser console** for JavaScript errors

---

## ✅ Sign-Off

When all tests pass, the messaging system is production-ready!

**Tested by:** _______________  
**Date:** _______________  
**Notes:** _______________

