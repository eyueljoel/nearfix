# ServiLoc Messaging System - Quick Start Guide

## 🚀 What's Ready

The messaging system is **100% implemented and ready to test**:

✅ **Database**: Messages and MessageReads tables  
✅ **Models**: Message, MessageRead with all methods  
✅ **Controller**: MessageController with all actions  
✅ **Validation**: StoreMessageRequest with authorization  
✅ **Views**: Inbox and conversation threads  
✅ **Routes**: All 5 messaging routes  
✅ **Sidebar**: Messages link with unread badge  

---

## 📝 To Start Testing

### Step 1: Prepare Test Data
```bash
# Clear old data if needed (optional)
php artisan tinker
>>> Message::truncate();
>>> MessageRead::truncate();

# Or just create new test users instead
```

### Step 2: Create Test Users
1. Go to `/register`
2. Create first user:
   - Name: "John Customer"
   - Email: john@test.com
   - Password: password
   - Role: Customer (select during registration)

3. Register second user:
   - Name: "Jane Provider"
   - Email: jane@test.com
   - Password: password
   - Role: Provider

4. Optional: Create third user for multiple conversations

### Step 3: Create Service Request
1. **Login as John (Customer)**
2. Go to **My Requests** in sidebar → **Customer Dashboard**
3. Click **Create Request** button
4. Fill form:
   - Title: "Fix Kitchen Sink"
   - Description: "My kitchen sink is leaking"
   - Budget: 500 (ETB)
   - Location: Addis Ababa
   - Category: Plumbing
5. Submit

### Step 4: Create Offer
1. **Logout, then Login as Jane (Provider)**
2. Go to **Available Requests** (or Search)
3. Find "Fix Kitchen Sink" request
4. Click **Create Offer**
5. Fill form:
   - Price: 450 (ETB)
   - Description: "I can fix this quickly"
6. Submit

### Step 5: Start Messaging
1. **Login as John (Customer)**
2. Go to **Messages** in sidebar
3. You should see "No conversations yet"
4. Go back to service request view or navigate to view the offer
5. Look for messaging option or directly go to `/messages/{service_request_id}`
6. Type message: "Hi Jane, can you start tomorrow?"
7. Click **Send Message**

### Step 6: Receive & Reply
1. **Logout, Login as Jane (Provider)**
2. Go to **Messages** in sidebar
3. You should see 1 conversation with unread badge "1"
4. Click on conversation
5. John's message appears with read indicator
6. Type reply: "Yes, I'll be there at 9 AM"
7. Send

### Step 7: Verify Everything Works
- ✅ Can you see messages back and forth?
- ✅ Do unread badges appear/disappear?
- ✅ Do timestamps show correctly?
- ✅ Does sender/recipient styling show (different colors)?
- ✅ Can you navigate back to inbox?

---

## 🔗 Important URLs

| Page | URL | Purpose |
|------|-----|---------|
| Messages Inbox | `/messages` | View all conversations |
| Conversation | `/messages/{service_request_id}` | View thread & reply |
| API - Unread | `/api/messages/unread-count` | Get unread count (for badge) |

---

## 🧪 Quick Test Checklist

- [ ] First message sends successfully
- [ ] Message appears in conversation thread
- [ ] Recipient sees unread badge in sidebar
- [ ] Clicking conversation marks message as read
- [ ] Reply message sends successfully
- [ ] Both can see full conversation history
- [ ] Timestamps display (e.g., "May 10, 2:30 PM")
- [ ] Character counter works (shows X / 2000)
- [ ] Empty message prevented (Send button disabled)
- [ ] Sidebar Messages link shows unread count

---

## 🐛 Troubleshooting

### "Page not found" when clicking Messages
**Fix:** Run migrations
```bash
php artisan migrate
```

### "No conversations yet" always shows
**Check:** Did you actually send a message? Verify:
1. You're logged in as recipient
2. Message was sent from other user
3. Both users are in same service request

### Send button is always disabled
**Check:** Message textarea might not have focus. Click in it first, then type.

### Character counter not showing
**Check:** Look at browser console (F12) for JavaScript errors

### Can't see unread badge
**Check:** 
1. You have unread messages
2. You're logged in as the recipient
3. Sidebar has Messages link (check all roles have it)

---

## 📚 For More Details

See full testing guide: `.kiro/MESSAGING_SYSTEM_TESTING_GUIDE.md`

---

**Happy testing! 🎉**
