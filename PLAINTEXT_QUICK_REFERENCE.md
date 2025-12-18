# Quick Reference - Plaintext Password System

## What Changed?

Passwords are now stored and managed as **plaintext** instead of hashed.

## Where Can You See This?

### 1. Admin User Detail Page

-   **Location**: `/admin/users/{user_id}`
-   **Section**: "User Management" card
-   **Fields**: Password, Withdrawal Password
-   **Feature**: View and edit passwords directly as plaintext

### 2. User Registration

-   **Location**: `/register`
-   **Behavior**: Passwords stored immediately as plaintext
-   **Fields**: Password, Withdrawal Password

### 3. User Withdrawal

-   **Location**: `/withdrawal`
-   **Behavior**: Withdrawal password compared directly without hashing

## Key Files Changed

| File                                                     | Change                               |
| -------------------------------------------------------- | ------------------------------------ |
| `app/Models/User.php`                                    | Removed password hashing cast        |
| `config/auth.php`                                        | Changed to plaintext provider        |
| `app/Auth/PlaintextUserProvider.php`                     | NEW - Custom auth provider           |
| `app/Providers/AppServiceProvider.php`                   | Registered plaintext provider        |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | Store plaintext passwords            |
| `app/Http/Controllers/Admin/UserController.php`          | Handle plaintext in password updates |
| `app/Http/Controllers/Auth/NewPasswordController.php`    | Reset passwords as plaintext         |
| `app/Http/Controllers/ProfileController.php`             | Compare plaintext passwords          |
| `app/Http/Controllers/WithdrawalController.php`          | Verify plaintext passwords           |

## Admin Panel Workflow

### View User Passwords

1. Go to Admin Dashboard
2. Click Users → Select a user
3. Scroll to "User Management" card
4. See plaintext passwords in text fields

### Update User Passwords

1. In "User Management" card
2. Enter new password (optional)
3. Enter new withdrawal password (optional)
4. Click "Update Management"
5. Passwords updated immediately as plaintext

### Change Password Modal

1. Click "Change Password" button
2. Enter new password twice for confirmation
3. Passwords saved as plaintext

## Database

When you look at the `users` table:

-   `password` column contains plaintext password
-   `withdrawal_password` column contains plaintext withdrawal password
-   Both are readable as regular text

## Security Note

⚠️ **Warning**: With plaintext passwords:

-   Anyone with database access can see all passwords
-   Passwords appear in backups, logs, etc.
-   Ensure database is properly secured
-   Use this only if explicitly required

## How Authentication Works

### Login

1. User enters username/email and password
2. System finds user by username/email
3. System compares entered password with database value directly
4. If match → User logged in
5. Custom `PlaintextUserProvider` handles this comparison

### Password Verification (Withdrawals)

1. User enters withdrawal password
2. System compares with database value directly
3. If match → Transaction allowed
4. No hashing involved

## Testing

Try these to verify it works:

### 1. Register a User

-   Go to `/register`
-   Create account with password: `TestPass123`
-   Check admin panel → Password displays as `TestPass123`

### 2. Login

-   Use your plaintext password to login
-   Should work normally

### 3. Change Password

-   Admin panel → Select user
-   "User Management" card → Enter new password
-   Save → Password updated as plaintext

### 4. Withdrawal

-   Go to withdrawal
-   Enter plaintext withdrawal password
-   Should work if password matches

## Emergency Notes

If passwords aren't working:

1. **Check Database**: Verify passwords are stored as plaintext

    ```sql
    SELECT username, password, withdrawal_password FROM users LIMIT 1;
    ```

2. **Check Configuration**: Ensure `config/auth.php` uses `'driver' => 'plaintext'`

3. **Clear Cache**: Run `php artisan config:cache`

4. **Regenerate Autoload**: Run `php artisan dump-autoload`

## Success Indicators

✅ Admin can see plaintext passwords in admin panel
✅ Admin can update passwords and they display immediately
✅ Users can login with plaintext passwords
✅ Withdrawals work with plaintext password verification
✅ No hashing/encryption applied to passwords

## That's It!

Your system is now completely configured for plaintext password storage and management. No additional setup needed!
