# Requirements Document: Messaging System

## Feature Overview

The messaging system enables one-to-one communication between customers and providers within ServiLoc, providing a simple inbox interface with read/unread status tracking. Messages are contextually linked to service requests, creating organized conversation threads without requiring real-time functionality.

## Acceptance Criteria

### 1. Message Creation

**Criterion 1.1: Users can send messages about service requests**

**Given** a customer or provider viewing a service request detail page  
**When** they fill in and submit a message form with recipient and message body  
**Then** the message is stored in the database and appears in the conversation thread

**Validation:**
- Form accepts plain text messages (1-2000 characters)
- Recipient must be the other party involved in the service request
- Sender cannot message themselves
- Message stored with timestamp and sender/recipient metadata
- Message immediately visible in conversation after sending

**Test Case:**
```php
$customer = User::factory()->customer()->create();
$provider = User::factory()->provider()->create();
$request = ServiceRequest::factory()->create(['user_id' => $customer->id, 'assigned_provider_id' => $provider->id]);

$response = $this->actingAs($customer)->post('/messages', [
    'service_request_id' => $request->id,
    'recipient_id' => $provider->id,
    'body' => 'When can you start?'
]);

$this->assertDatabaseHas('messages', [
    'sender_id' => $customer->id,
    'recipient_id' => $provider->id,
    'service_request_id' => $request->id,
    'body' => 'When can you start?'
]);
```

---

**Criterion 1.2: Only request participants can send messages**

**Given** a user attempting to send a message  
**When** the user is not a participant in the service request  
**Then** the message creation is rejected with 403 Forbidden

**Validation:**
- Customer can only message their own service request's assigned provider
- Provider can only message the customer of their assigned service request
- Non-participants receive authorization error
- Error message is user-friendly but doesn't leak sensitive details

**Test Case:**
```php
$customer1 = User::factory()->customer()->create();
$customer2 = User::factory()->customer()->create();
$provider = User::factory()->provider()->create();
$request = ServiceRequest::factory()->create(['user_id' => $customer1->id]);

$response = $this->actingAs($customer2)->post('/messages', [
    'service_request_id' => $request->id,
    'recipient_id' => $provider->id,
    'body' => 'Text message'
]);

$response->assertStatus(403);
```

---

**Criterion 1.3: Message body is required and length-limited**

**Given** a user attempting to send a message  
**When** the message body is empty or exceeds 2000 characters  
**Then** validation error is displayed and message is not created

**Validation:**
- Empty messages rejected with error "Message cannot be empty"
- Messages over 2000 characters rejected with error "Message too long"
- Form redisplayed with validation errors highlighted
- Other form fields preserved for resubmission

**Test Cases:**
```php
// Empty message
$response = $this->actingAs($user)->post('/messages', [
    'service_request_id' => $request->id,
    'recipient_id' => $recipient->id,
    'body' => ''
]);
$response->assertSessionHasErrors('body');

// Too long
$longText = str_repeat('a', 2001);
$response = $this->actingAs($user)->post('/messages', [
    'service_request_id' => $request->id,
    'recipient_id' => $recipient->id,
    'body' => $longText
]);
$response->assertSessionHasErrors('body');
```

---

### 2. Message Viewing and Organization

**Criterion 2.1: Users can view inbox with conversation list**

**Given** a user viewing the messaging inbox  
**When** they navigate to /messages  
**Then** they see a paginated list of conversations with latest message preview

**Validation:**
- Inbox accessible to authenticated users
- Shows all conversations user is part of
- Each conversation displays:
  - Other party's name and avatar
  - Last message preview (truncated to ~50 chars)
  - Timestamp of last message
  - Unread count badge (if any)
  - Link to open conversation
- Results paginated 15 per page
- Ordered by most recent message first
- Empty state shown when no messages

**Test Case:**
```php
$user = User::factory()->create();
$message1 = Message::factory()->create(['recipient_id' => $user->id]);
$message2 = Message::factory()->create(['recipient_id' => $user->id]);

$response = $this->actingAs($user)->get('/messages');

$response->assertOk();
$response->assertViewIs('messages.inbox');
$response->assertViewHas('conversations');
```

---

**Criterion 2.2: Users can view full message thread for a conversation**

**Given** a user clicking on a conversation from inbox  
**When** they navigate to /messages/{service_request_id}  
**Then** all messages in that conversation are displayed in chronological order

**Validation:**
- Only participants can view the conversation
- Messages displayed oldest first (or newest first based on UX choice)
- Each message shows:
  - Sender name/avatar
  - Message body
  - Timestamp
  - Read status indicator (subtle, only sender sees it)
- Request context shown (request title, status)
- Page includes message form to reply
- Messages paginate (load 20 per page, lazy load more)

**Test Case:**
```php
$customer = User::factory()->customer()->create();
$provider = User::factory()->provider()->create();
$request = ServiceRequest::factory()->create(['user_id' => $customer->id, 'assigned_provider_id' => $provider->id]);
$msg1 = Message::factory()->create(['service_request_id' => $request->id, 'sender_id' => $customer->id, 'recipient_id' => $provider->id]);
$msg2 = Message::factory()->create(['service_request_id' => $request->id, 'sender_id' => $provider->id, 'recipient_id' => $customer->id]);

$response = $this->actingAs($customer)->get("/messages/{$request->id}");

$response->assertOk();
$response->assertSee($msg1->body);
$response->assertSee($msg2->body);
```

---

**Criterion 2.3: Conversation isolation - users cannot see others' messages**

**Given** a third-party user  
**When** they attempt to view a conversation they're not part of  
**Then** they receive 403 Forbidden and see no messages

**Validation:**
- Authorization check prevents unauthorized access
- No information leakage about conversation existence
- Redirect to inbox with generic error message
- Access attempt is logged for security

**Test Case:**
```php
$stranger = User::factory()->create();
$customer = User::factory()->customer()->create();
$provider = User::factory()->provider()->create();
$request = ServiceRequest::factory()->create(['user_id' => $customer->id, 'assigned_provider_id' => $provider->id]);

$response = $this->actingAs($stranger)->get("/messages/{$request->id}");

$response->assertStatus(403);
```

---

### 3. Read/Unread Status

**Criterion 3.1: Messages marked unread until recipient views conversation**

**Given** a user receives a message  
**When** the message is first created  
**Then** it displays as unread in the recipient's inbox

**Validation:**
- New messages show unread indicator in inbox
- Unread count badge displayed if > 0
- Read status tracked in message_reads table
- Only recipient can mark message as read

**Test Case:**
```php
$recipient = User::factory()->create();
$msg = Message::factory()->create(['recipient_id' => $recipient->id]);

$this->assertTrue($msg->isUnreadBy($recipient));
$this->assertEquals(1, Message::unreadCountForUser($recipient));
```

---

**Criterion 3.2: Viewing conversation marks messages as read**

**Given** a user viewing a conversation thread  
**When** they load the /messages/{service_request_id} page  
**Then** all unread messages in that conversation are marked as read

**Validation:**
- All unread messages for that conversation marked read
- Unread count updated in UI
- Read status persisted to database
- Subsequent views show messages as read
- Read timestamp recorded

**Test Case:**
```php
$user = User::factory()->create();
$msg1 = Message::factory()->create(['recipient_id' => $user->id]);
$msg2 = Message::factory()->create(['recipient_id' => $user->id, 'service_request_id' => $msg1->service_request_id]);

$this->assertEquals(2, Message::unreadCountForUser($user));

$this->actingAs($user)->get("/messages/{$msg1->service_request_id}");

$this->assertEquals(0, Message::unreadCountForUser($user));
$this->assertTrue($msg1->isReadBy($user));
$this->assertTrue($msg2->isReadBy($user));
```

---

**Criterion 3.3: Unread count displayed in navigation**

**Given** an authenticated user with unread messages  
**When** they view any page  
**Then** a badge in the navigation shows the total unread count

**Validation:**
- Badge shows number of unread messages
- Badge hidden if no unread messages
- Badge updates after marking conversation read
- Count accurate across all conversations
- Accessible via /api/messages/unread-count endpoint for dynamic updates

**Test Case:**
```php
$user = User::factory()->create();
Message::factory()->count(3)->create(['recipient_id' => $user->id]);

$response = $this->actingAs($user)->get('/api/messages/unread-count');

$response->assertJson(['unread_count' => 3]);
```

---

### 4. Inbox Features

**Criterion 4.1: Inbox shows unique conversations, not individual messages**

**Given** multiple messages in a conversation  
**When** viewing the inbox  
**Then** the conversation appears once with the latest message preview

**Validation:**
- One entry per conversation (service_request + other_party pair)
- Latest message shown as preview
- Earlier messages in conversation not listed separately
- Unread count includes ALL unread messages in conversation
- Clicking conversation loads full thread

**Test Case:**
```php
$customer = User::factory()->customer()->create();
$provider = User::factory()->provider()->create();
$request = ServiceRequest::factory()->create(['user_id' => $customer->id, 'assigned_provider_id' => $provider->id]);

Message::factory()->count(5)->create([
    'service_request_id' => $request->id,
    'sender_id' => $customer->id,
    'recipient_id' => $provider->id
]);

$response = $this->actingAs($provider)->get('/messages');

// Should see 1 conversation, not 5
$conversations = $response->viewData('conversations');
$this->assertCount(1, $conversations);
```

---

**Criterion 4.2: Inbox sorted by most recent message**

**Given** a user with multiple conversations  
**When** viewing the inbox  
**Then** conversations are sorted by most recent message timestamp

**Validation:**
- Conversations ordered newest to oldest
- Order updates when new message sent
- Order maintained across page reloads
- Pagination respects sort order

**Test Case:**
```php
$user = User::factory()->create();
$conv1 = Message::factory()->create(['recipient_id' => $user->id, 'created_at' => now()->subDays(3)]);
$conv2 = Message::factory()->create(['recipient_id' => $user->id, 'created_at' => now()->subDays(1)]);
$conv3 = Message::factory()->create(['recipient_id' => $user->id, 'created_at' => now()]);

$response = $this->actingAs($user)->get('/messages');
$conversations = $response->viewData('conversations');

$this->assertEquals($conv3->service_request_id, $conversations[0]['service_request_id']);
$this->assertEquals($conv2->service_request_id, $conversations[1]['service_request_id']);
$this->assertEquals($conv1->service_request_id, $conversations[2]['service_request_id']);
```

---

### 5. Conversation Context

**Criterion 5.1: Messages linked to service requests**

**Given** a message in the system  
**When** querying the message  
**Then** it includes a reference to the associated service request

**Validation:**
- Every message has service_request_id foreign key
- Message deleted if service request deleted (cascade)
- Conversation shows service request details:
  - Request title and description
  - Current status (open/assigned/completed/cancelled)
  - Budget and location
  - Scheduled date if available
- Service request context visible on conversation view

**Test Case:**
```php
$request = ServiceRequest::factory()->create();
$msg = Message::factory()->create(['service_request_id' => $request->id]);

$this->assertEquals($request->id, $msg->service_request_id);
$this->assertEquals($request->id, $msg->serviceRequest->id);

$request->delete();
$this->assertFalse(Message::find($msg->id));
```

---

### 6. User Experience

**Criterion 6.1: Message composition form on conversation view**

**Given** a user viewing a conversation  
**When** they see the message form at bottom of page  
**Then** they can immediately compose and send a reply

**Validation:**
- Form includes textarea for message body
- Form shows character count (updates as they type)
- Send button disabled until message not empty
- Form accessible and properly labeled
- Success message shown after sending
- Page refreshes or updates to show new message
- Form clears after successful submission

**Test Case:**
Manual testing: User views conversation, types message, sees character count, clicks send, sees new message in thread.

---

**Criterion 6.2: Empty state when no messages**

**Given** a user with no messages  
**When** they navigate to /messages  
**Then** they see an empty state with helpful message

**Validation:**
- Empty state message displayed (e.g., "You have no conversations yet")
- Helpful link to browse requests or offers
- Not confusing or alarming
- Proper styling/spacing

**Test Case:**
Manual testing: New user navigates to /messages, sees empty state.

---

### 7. Data Integrity

**Criterion 7.1: Read status accurate across sessions**

**Given** a user marks messages as read  
**When** they log out and log back in  
**Then** messages remain marked as read

**Validation:**
- Read status persisted to database
- Read status survives session/page refreshes
- Read timestamps correct

**Test Case:**
```php
$user = User::factory()->create();
$msg = Message::factory()->create(['recipient_id' => $user->id]);

$this->actingAs($user)->get("/messages/{$msg->service_request_id}");

$this->assertTrue($msg->isReadBy($user));

// Logout and login
auth()->logout();
$this->actingAs($user)->get('/messages');

$msg->refresh();
$this->assertTrue($msg->isReadBy($user));
```

---

**Criterion 7.2: No duplicate read records**

**Given** a message viewed multiple times  
**When** user views conversation multiple times  
**Then** only one read record exists per message per user

**Validation:**
- Unique constraint on (message_id, user_id) enforced
- Duplicate mark-as-read attempts handled gracefully
- Read timestamp doesn't update on subsequent views

**Test Case:**
```php
$user = User::factory()->create();
$msg = Message::factory()->create(['recipient_id' => $user->id]);

$msg->markAsReadBy($user);
$msg->markAsReadBy($user);

$readCount = \App\Models\MessageRead::where('message_id', $msg->id)
    ->where('user_id', $user->id)
    ->count();

$this->assertEquals(1, $readCount);
```

---

### 8. Pagination and Performance

**Criterion 8.1: Inbox paginated for performance**

**Given** a user with many conversations  
**When** viewing the inbox  
**Then** conversations are paginated with 15 per page

**Validation:**
- Initial request loads only first 15 conversations
- Pagination links appear when > 15 conversations
- Page parameter respected in URL
- Can navigate between pages
- Performance acceptable (query < 100ms)

**Test Case:**
```php
$user = User::factory()->create();
Message::factory()->count(50)->create(['recipient_id' => $user->id]);

$response = $this->actingAs($user)->get('/messages');

$conversations = $response->viewData('conversations');
$this->assertCount(15, $conversations);

$response = $this->actingAs($user)->get('/messages?page=2');
$conversations = $response->viewData('conversations');
// Should have some conversations on page 2
```

---

**Criterion 8.2: Message threads paginated**

**Given** a conversation with many messages  
**When** viewing the conversation  
**Then** messages are paginated with 20 per page

**Validation:**
- Initial request loads only newest 20 messages
- Pagination links appear when > 20 messages
- Loading earlier messages works
- Performance acceptable even with many messages

**Test Case:**
```php
$request = ServiceRequest::factory()->create();
Message::factory()->count(50)->create(['service_request_id' => $request->id]);

$response = $this->actingAs($user)->get("/messages/{$request->id}");

// Verify proper pagination handling
```

---

## Non-Functional Requirements

### Performance

**Requirement NF-1:** Inbox loads in under 200ms for users with up to 100 conversations
- Implement database indices on sender_id, recipient_id, service_request_id
- Use eager loading for relationships
- Paginate results

**Requirement NF-2:** Message thread loads in under 500ms even with 500+ messages
- Index on (service_request_id, created_at)
- Lazy load older messages
- Paginate message results

**Requirement NF-3:** Unread count query executes in under 50ms
- Use efficient SQL with LEFT JOIN on MessageRead
- Cache result with 5-minute TTL
- Invalidate cache on new message

### Security

**Requirement NF-4:** All message access validated with authorization checks
- Implement policy classes for Message model
- Check user is participant in conversation
- Log unauthorized access attempts

**Requirement NF-5:** Input sanitized to prevent XSS attacks
- Use Blade's automatic escaping {{ }}
- Never use unescaped {!! !!} for message display
- Strip any HTML tags from message body

**Requirement NF-6:** CSRF protection on all state-changing requests
- Use @csrf in all forms
- Verify token on POST/PUT/PATCH/DELETE requests

**Requirement NF-7:** Rate limit message sending
- Max 50 messages per user per hour
- Returns 429 Too Many Requests when exceeded

### Scalability

**Requirement NF-8:** System supports 10,000+ messages without degradation
- Proper database indexing strategy
- Consider message archival for very old messages
- Use database replication for read scaling

**Requirement NF-9:** Support concurrent message creation
- Database constraints prevent race conditions
- Unique constraint on message_reads prevents duplicate reads
- Use transactions for complex operations

### Reliability

**Requirement NF-10:** Messages not lost due to application crashes
- All data persisted immediately to database
- No in-memory-only state
- Transactions ensure atomic operations

### Usability

**Requirement NF-11:** Responsive design works on mobile and desktop
- Tailwind CSS responsive classes used
- Touch-friendly button sizes
- Readable text on small screens

**Requirement NF-12:** Accessible to screen readers and keyboard users
- Proper semantic HTML
- ARIA labels on dynamic content
- Keyboard navigation support
- Form labels properly associated

