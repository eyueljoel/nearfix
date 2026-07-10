# Tasks: Messaging System Implementation

## Phase 1: Database Foundation

### Task 1.1: Create Database Migration for Messages Table
- [x] Create migration file `create_messages_table.php`
- [x] Define messages table schema with columns:
  - id (bigint, primary key)
  - service_request_id (bigint, foreign key)
  - sender_id (bigint, foreign key)
  - recipient_id (bigint, foreign key)
  - body (text)
  - created_at, updated_at (timestamps)
- [x] Add foreign key constraints with cascade delete
- [x] Create indices on sender_id, recipient_id, service_request_id
- [x] Run migration successfully
- [x] Verify table structure with tinker

### Task 1.2: Create Database Migration for Message Reads Table
- [x] Create migration file `create_message_reads_table.php`
- [x] Define message_reads table schema:
  - id (bigint, primary key)
  - message_id (bigint, foreign key)
  - user_id (bigint, foreign key)
  - read_at (timestamp, nullable)
  - created_at (timestamp)
- [x] Add unique constraint on (message_id, user_id)
- [x] Add index on (user_id, read_at)
- [x] Add foreign key constraints with cascade delete
- [x] Run migration successfully
- [x] Verify table structure

## Phase 2: Model Development

### Task 2.1: Create Message Model
- [x] Create `app/Models/Message.php`
- [x] Define relationships:
  - `serviceRequest()` - BelongsTo ServiceRequest
  - `sender()` - BelongsTo User (as sender)
  - `recipient()` - BelongsTo User (as recipient)
  - `reads()` - HasMany MessageRead
- [x] Add fillable attributes: service_request_id, sender_id, recipient_id, body
- [x] Add hidden attributes as needed
- [x] Add casts for timestamps
- [x] Write unit tests for relationships

### Task 2.2: Implement Message Business Logic Methods
- [x] Implement `isReadBy(User $user): bool` method
- [x] Implement `markAsReadBy(User $user): void` method
- [x] Implement static `unreadCountForUser(User $user): int` method
- [x] Implement static `getConversation(ServiceRequest $request, User $user1, User $user2, int $perPage = 20): Paginator` method
- [x] Implement static `getInboxConversations(User $user, int $perPage = 15): Paginator` method
- [x] Write unit tests for each method
- [x] Test edge cases (no messages, many messages, read/unread scenarios)

### Task 2.3: Create MessageRead Model
- [x] Create `app/Models/MessageRead.php`
- [x] Define relationships:
  - `message()` - BelongsTo Message
  - `user()` - BelongsTo User
- [x] Add fillable attributes: message_id, user_id, read_at
- [x] Add timestamp casts
- [x] Implement `static markAsRead(Message $message, User $user): void` method
- [x] Implement `static isRead(Message $message, User $user): bool` method
- [x] Write unit tests

### Task 2.4: Update User Model
- [x] Add relationship `messages()` - HasMany Message (as sender)
- [x] Add relationship `receivedMessages()` - HasMany Message (as recipient)
- [x] Add method `unreadMessageCount(): int` for convenience
- [x] Test relationships work correctly

### Task 2.5: Update ServiceRequest Model
- [x] Add relationship `messages()` - HasMany Message
- [x] Test relationship and cascade delete behavior

## Phase 3: Forms and Validation

### Task 3.1: Create StoreMessageRequest Form Request
- [x] Create `app/Http/Requests/StoreMessageRequest.php`
- [x] Define validation rules:
  - service_request_id: required|exists:service_requests,id
  - recipient_id: required|exists:users,id|different:auth.id
  - body: required|string|min:1|max:2000
- [x] Implement `authorize()` method:
  - Check user is customer or assigned provider
  - Check recipient is the other party
  - Return false for unauthorized users
- [x] Add custom error messages for better UX
- [x] Write tests for validation and authorization

### Task 3.2: Create UpdateMessageReadRequest Form Request (if needed)
- [ ] Create `app/Http/Requests/MarkMessagesReadRequest.php` (optional)
- [ ] Validate service_request_id exists
- [ ] Implement authorize to check user is participant
- [ ] Write tests

## Phase 4: Controllers

### Task 4.1: Create MessageController
- [x] Create `app/Http/Controllers/MessageController.php`
- [x] Implement `inbox(Request $request): View` method
  - Query inbox conversations for authenticated user
  - Paginate results (15 per page)
  - Include unread counts and other metadata
  - Handle empty state
- [x] Implement `show(ServiceRequest $serviceRequest): View` method
  - Check authorization
  - Get conversation messages
  - Mark messages as read
  - Return view with message thread
  - Include message form and request context
- [x] Implement `store(StoreMessageRequest $request): RedirectResponse` method
  - Create message record from form data
  - Store in database
  - Redirect back to conversation with success message
- [x] Implement `markAsRead(ServiceRequest $serviceRequest): RedirectResponse` method
  - Mark all conversation messages as read
  - Redirect back with appropriate response
- [x] Write integration tests for each method
- [x] Test authorization on protected methods

### Task 4.2: Create ConversationController (if separate)
- [ ] Create `app/Http/Controllers/ConversationController.php` (optional)
- [ ] Implement `index(Request $request): View` method (mirrors inbox)
- [ ] Implement `show(ServiceRequest $request, User $other): View` method (mirrors message show)
- [ ] Or consolidate into MessageController
- [ ] Write tests

### Task 4.3: Create API Endpoint for Unread Count
- [x] Add `unreadCount(Request $request): JsonResponse` method to MessageController
- [x] Return JSON with unread_count field
- [x] Route at GET /api/messages/unread-count
- [x] Test response format and accuracy

## Phase 5: Authorization and Policies (Optional)

### Task 5.1: Create Message Policy (Optional)
- [ ] Create `app/Policies/MessagePolicy.php`
- [ ] Implement `view(User $user, Message $message): bool` policy
  - Check user is sender or recipient
  - Check user is participant in service request
- [ ] Implement `create(User $user): bool` policy
- [ ] Test policy methods
- [ ] Apply policy in controllers via authorize() calls

## Phase 6: Views and UI

### Task 6.1: Create Inbox View
- [x] Create `resources/views/messages/inbox.blade.php`
- [x] Display heading "Conversations" with unread count
- [x] Create conversation list showing:
  - Other party avatar (if available) and name
  - Last message preview (truncated)
  - Last message timestamp (formatted, e.g., "2 days ago")
  - Unread badge with count (if > 0)
  - Link to open conversation
- [x] Implement pagination links
- [x] Style with Tailwind CSS
- [x] Add empty state view
- [x] Write tests for view content

### Task 6.2: Create Conversation Detail View
- [x] Create `resources/views/messages/show.blade.php`
- [x] Display header with:
  - Other party name and basic info
  - Service request title and status
  - Link back to inbox
- [x] Display message thread:
  - Messages in chronological order (oldest first or newest first)
  - Sender name/avatar, body, timestamp for each
  - Read status indicator (subtle, only sender sees)
  - Proper message styling (different colors for sender vs recipient)
- [x] Include pagination for message thread (if many messages)
- [x] Include message form at bottom
- [x] Style with Tailwind CSS, responsive for mobile
- [x] Write tests for view content

### Task 6.3: Create Message Form Component
- [ ] Create `resources/views/messages/form.blade.php` partial
- [ ] Include textarea for message body
- [ ] Include character counter (updates on keyup)
- [ ] Include Send button (disabled if empty)
- [ ] Include hidden fields for service_request_id and recipient_id
- [ ] Include @csrf token
- [ ] Display validation errors from session
- [ ] Style with Tailwind CSS

### Task 6.4: Update Navigation/Layout
- [x] Add Messages link to main navigation
- [x] Display unread count badge next to Messages link
- [x] Make badge update dynamically (optional, using Alpine.js)
- [x] Test navigation updates correctly
- [x] Ensure responsive design

### Task 6.5: Create Empty State View
- [ ] Create `resources/views/messages/empty.blade.php`
- [ ] Display helpful message when no conversations
- [ ] Include link to browse requests or offers
- [ ] Style consistently with other views

## Phase 7: Routes

### Task 7.1: Add Message Routes
- [x] Add route GET /messages -> MessageController@inbox (name: messages.inbox)
- [x] Add route GET /messages/{serviceRequest} -> MessageController@show (name: messages.show)
- [x] Add route POST /messages -> MessageController@store (name: messages.store)
- [x] Add route PATCH /messages/{serviceRequest}/mark-read -> MessageController@markAsRead (name: messages.mark-read)
- [x] Add route GET /api/messages/unread-count -> MessageController@unreadCount (name: api.messages.unread-count)
- [x] All routes protected by auth and verified middleware
- [x] Test all routes are accessible and return correct responses

## Phase 8: Testing

### Task 8.1: Write Unit Tests for Models
- [ ] Test Message model relationships
- [ ] Test Message model methods (isReadBy, markAsReadBy, etc.)
- [ ] Test MessageRead model
- [ ] Test unread count calculation
- [ ] Test cascade deletes
- [ ] Achieve > 90% code coverage for models

### Task 8.2: Write Feature Tests for Message Creation
- [ ] Test customer can send message to provider
- [ ] Test provider can send message to customer
- [ ] Test non-participant cannot send message
- [ ] Test self-message prevented
- [ ] Test empty message rejected
- [ ] Test oversized message rejected
- [ ] Test message stored correctly

### Task 8.3: Write Feature Tests for Message Viewing
- [ ] Test participant can view conversation
- [ ] Test non-participant cannot view conversation
- [ ] Test messages display in correct order
- [ ] Test unread messages marked as read on view
- [ ] Test unread count updated after viewing

### Task 8.4: Write Feature Tests for Inbox
- [ ] Test inbox shows all conversations
- [ ] Test inbox paginated correctly
- [ ] Test conversations grouped correctly
- [ ] Test sorted by most recent message
- [ ] Test empty inbox shows empty state
- [ ] Test unread badges display

### Task 8.5: Write Feature Tests for Read/Unread Status
- [ ] Test new message unread
- [ ] Test marking message as read
- [ ] Test read status persists
- [ ] Test unread count API endpoint
- [ ] Test no duplicate read records

### Task 8.6: Write Security Tests
- [ ] Test authorization on all protected routes
- [ ] Test CSRF protection on POST/PATCH requests
- [ ] Test input validation on message body
- [ ] Test XSS prevention on message display
- [ ] Test SQL injection prevention

## Phase 9: Documentation and Code Quality

### Task 9.1: Add Code Comments
- [ ] Add PHPDoc comments to all model methods
- [ ] Add comments to complex logic in controllers
- [ ] Add comments to view templates explaining structure
- [ ] Document any non-obvious design decisions

### Task 9.2: Run Code Quality Checks
- [ ] Run PHP linter (or Laravel code style checker)
- [ ] Fix any style violations
- [ ] Run PHPStan or similar static analysis
- [ ] Fix any identified issues
- [ ] Verify tests pass: `php artisan test`

## Phase 10: Performance Optimization

### Task 10.1: Database Query Optimization
- [ ] Verify all indices created correctly
- [ ] Use eager loading in controllers (with/load)
- [ ] Test query performance with large datasets
- [ ] Optimize any slow queries with EXPLAIN ANALYZE

### Task 10.2: Caching Implementation (Optional)
- [ ] Cache unread count with 5-minute TTL
- [ ] Invalidate cache when message marked read
- [ ] Cache inbox conversations if beneficial
- [ ] Test cache invalidation

### Task 10.3: N+1 Query Prevention
- [ ] Audit controllers for N+1 queries
- [ ] Use eager loading for relationships
- [ ] Test with QueryLog in test environment

## Phase 11: Integration and Acceptance

### Task 11.1: Integration Testing
- [ ] Test complete message flow end-to-end
- [ ] Test across different browsers (manual or automation)
- [ ] Test responsive design on mobile devices
- [ ] Test on touch devices (swipe, tap)
- [ ] Test with different message lengths and content

### Task 11.2: Manual Acceptance Testing
- [ ] Customer sends message to provider
- [ ] Provider receives and replies
- [ ] Unread count updates in navigation
- [ ] Conversation history displays correctly
- [ ] Read status marks as read on viewing
- [ ] Inbox shows all conversations
- [ ] Empty inbox state displays
- [ ] Error messages display correctly

### Task 11.3: Verification Against Requirements
- [ ] Verify all acceptance criteria met
- [ ] Verify all non-functional requirements met
- [ ] Performance testing (load times)
- [ ] Security testing (manual + automated)
- [ ] Accessibility testing (keyboard nav, screen readers)

## Phase 12: Documentation

### Task 12.1: Create User Documentation
- [ ] Document how to send a message
- [ ] Document how to view conversations
- [ ] Document how to mark messages as read
- [ ] Add screenshots showing UI
- [ ] Create FAQ for common questions

### Task 12.2: Create Developer Documentation
- [ ] Document model relationships and key methods
- [ ] Document controller actions and their parameters
- [ ] Document validation rules
- [ ] Create example API calls
- [ ] Document extension points for future enhancements

## Phase 13: Deployment Preparation

### Task 13.1: Create Database Backup Plan
- [ ] Document backup strategy before migration
- [ ] Test migration in staging environment
- [ ] Create rollback procedure if needed

### Task 13.2: Prepare Deployment Steps
- [ ] Document deployment checklist
- [ ] Run migrations on production (or staging first)
- [ ] Verify tables created correctly
- [ ] Run tests in production environment
- [ ] Monitor for errors and performance issues

## Summary Statistics

**Estimated Story Points:** 34-40
**Estimated Duration:** 2-3 weeks (assuming 20-25 hours/week development)
**Priority:** Medium (Priority 2 feature)

**Key Dependencies:**
- Laravel 11 running
- User authentication working
- ServiceRequest model existing
- Database migrations support

**Risk Areas:**
- Performance with large message volumes (mitigated by pagination and indexing)
- Concurrent message creation (mitigated by database constraints)
- Authorization edge cases (mitigated by comprehensive testing)

