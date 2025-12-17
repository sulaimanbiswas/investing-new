# Quick Reference: Phone Number Implementation

## Quick Start for Developers

### New User Registration

```php
// Required fields
$user = User::create([
    'name' => 'John Doe',
    'username' => 'johndoe',
    'phone' => '+1234567890',      // REQUIRED
    'email' => 'john@example.com', // OPTIONAL
    'password' => Hash::make('password'),
    'withdrawal_password' => Hash::make('withdrawal123'),
    'invitation_code' => 'ABC123',
    'referred_by' => 1,
]);
```

### Login Methods

```php
// Users can login with ANY of these:
- Phone: +1234567890
- Username: johndoe
- Email: john@example.com

// The system auto-detects which one is being used
```

### Finding Users

```php
// Find by phone
$user = User::where('phone', '+1234567890')->first();

// Find by identifier (phone/username/email)
$identifier = $request->identifier;

if (preg_match('/^[0-9+\-\s()]+$/', $identifier)) {
    // It's a phone
    $user = User::where('phone', $identifier)->first();
} elseif (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
    // It's an email
    $user = User::where('email', $identifier)->first();
} else {
    // It's a username
    $user = User::where('username', $identifier)->first();
}
```

### Validation Rules

```php
// Registration
'phone' => ['required', 'string', 'max:20', 'unique:users', 'regex:/^[0-9+\-\s()]+$/'],
'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],

// Update (if allowing phone changes)
'phone' => ['required', 'string', 'max:20', 'unique:users,phone,' . $user->id],
```

### Display User Info

```blade
{{-- Show phone (always available) --}}
<p>Phone: {{ $user->phone }}</p>

{{-- Show email (check if exists) --}}
@if($user->email)
    <p>Email: {{ $user->email }}</p>
@else
    <p>Email: N/A</p>
@endif

{{-- Show best identifier --}}
<p>
    @if($user->username)
        {{ '@' . $user->username }}
    @elseif($user->phone)
        {{ $user->phone }}
    @else
        {{ $user->email }}
    @endif
</p>
```

## API Endpoints (If Applicable)

### Register

```http
POST /register
Content-Type: application/json

{
    "name": "John Doe",
    "username": "johndoe",
    "phone": "+1234567890",
    "email": "john@example.com",  // optional
    "password": "password123",
    "withdrawal_password": "withdrawal123",
    "invitation_code": "ABC123"
}
```

### Login

```http
POST /login
Content-Type: application/json

{
    "identifier": "+1234567890",  // or username or email
    "password": "password123",
    "remember": true
}
```

### Password Reset Request

```http
POST /forgot-password
Content-Type: application/json

{
    "identifier": "+1234567890"  // or username or email
}
```

## Database Queries

### Get all users with phone numbers

```sql
SELECT * FROM users WHERE phone IS NOT NULL;
```

### Get users without email

```sql
SELECT * FROM users WHERE email IS NULL;
```

### Update user phone

```sql
UPDATE users SET phone = '+1987654321' WHERE id = 1;
```

### Find duplicates (should return 0)

```sql
SELECT phone, COUNT(*) as count
FROM users
GROUP BY phone
HAVING count > 1;
```

## Common Patterns

### Check if user has email

```php
if ($user->email) {
    // Send email notification
} else {
    // Use SMS or other notification method
}
```

### Search users

```php
$search = $request->input('search');

$users = User::where(function($query) use ($search) {
    $query->where('username', 'like', "%{$search}%")
          ->orWhere('phone', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%");
})->get();
```

### Create test user

```php
User::factory()->create([
    'phone' => '+1555123' . rand(1000, 9999),
    'email' => null, // Optional: no email
]);
```

## Migration Commands

### Run migration

```bash
php artisan migrate
```

### Check migration status

```bash
php artisan migrate:status
```

### Rollback phone migration

```bash
php artisan migrate:rollback --step=1
```

## Testing

### Feature Test Example

```php
public function test_user_can_register_with_phone()
{
    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'testuser',
        'phone' => '+1234567890',
        'password' => 'password',
        'withdrawal_password' => 'withdrawal',
        'invitation_code' => 'ABC123',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertDatabaseHas('users', [
        'phone' => '+1234567890',
    ]);
}

public function test_user_can_login_with_phone()
{
    $user = User::factory()->create([
        'phone' => '+1234567890',
        'password' => Hash::make('password'),
    ]);

    $response = $this->post('/login', [
        'identifier' => '+1234567890',
        'password' => 'password',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
}
```

## Troubleshooting

### User can't login

-   Check if phone number format matches database
-   Verify phone number is unique
-   Check if user status is 'active'

### Migration fails

-   Check existing users have phone numbers
-   Verify no duplicate phone numbers
-   Check database permissions

### Phone validation fails

-   Ensure format matches regex: `/^[0-9+\-\s()]+$/`
-   Remove extra spaces or special characters
-   Max length is 20 characters

## Environment Variables

No new environment variables required for this feature.

## Dependencies

No new packages installed. Uses existing Laravel authentication and validation.

---

**Last Updated**: December 17, 2025
**Status**: Production Ready ✅
