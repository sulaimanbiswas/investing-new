# Referral System - Testing Guide

## Quick Test Steps

### 1. Check Existing Users Have Referral Codes

```bash
php artisan tinker
```

```php
\App\Models\User::all()->pluck('referral_code', 'name');
```

### 2. Test Auto-Generation with New User

```bash
php artisan tinker
```

```php
$user = \App\Models\User::create([
    'name' => 'Test User',
    'username' => 'testuser' . rand(1000, 9999),
    'email' => 'test' . rand(1000, 9999) . '@example.com',
    'password' => bcrypt('password'),
    'withdrawal_password' => bcrypt('password'),
    'invitation_code' => \App\Models\User::first()->referral_code,
    'referred_by' => \App\Models\User::first()->id,
]);

echo "Referral Code: {$user->referral_code}\n";
echo "Referral Link: {$user->referral_link}\n";

// Clean up
$user->delete();
```

### 3. Test Referral Link in Browser

1. Log in as any user
2. Go to Dashboard
3. Copy the referral link (e.g., `http://localhost:8000/register?ref=3B97CDA6`)
4. Open in new incognito window
5. Verify the invitation code field is pre-filled and read-only
6. Complete registration
7. Log in with new account
8. Check dashboard to see who referred you

### 4. Test Invalid Referral Code

1. Go to `/register?ref=INVALID123`
2. Try to register
3. Should see error: "Invalid invitation code. Please enter a valid referral code from an existing user."

### 5. Test Referral Tree

```bash
php artisan tinker
```

```php
// Get a user
$user = \App\Models\User::first();

// See their referrals
$user->referrals;

// See who referred them
$user->referrer;

// Count referrals
$user->referral_count;
```

### 6. Test Copy to Clipboard

1. Log in to dashboard
2. Click "Copy" button next to referral code
3. Should see success notification
4. Paste somewhere to verify it copied correctly
5. Repeat for referral link

### 7. Test Command for Existing Users

```bash
# If you have users without referral codes
php artisan referral:generate
```

## Expected Results

✅ Every user has a unique 8-character referral code  
✅ Referral links auto-fill the invitation code field  
✅ Invalid referral codes show validation error  
✅ Dashboard shows referral information correctly  
✅ Copy buttons work and show success notification  
✅ Referral tree relationships work correctly  
✅ New users automatically get referral codes

## Database Verification

```sql
-- Check all users have referral codes
SELECT COUNT(*) as total_users,
       COUNT(referral_code) as users_with_code
FROM users;

-- Check referral tree
SELECT
    u.name as referred_user,
    r.name as referred_by
FROM users u
LEFT JOIN users r ON u.referred_by = r.id;

-- Get referral statistics
SELECT
    u.name,
    u.referral_code,
    COUNT(r.id) as total_referrals
FROM users u
LEFT JOIN users r ON r.referred_by = u.id
GROUP BY u.id, u.name, u.referral_code;
```

## Troubleshooting

### Issue: Users don't have referral codes

**Solution:** Run `php artisan referral:generate`

### Issue: Referral link doesn't auto-fill

**Solution:** Check that the URL has `?ref=CODE` parameter

### Issue: Can't register with referral code

**Solution:** Verify the referral code exists in database: `User::where('referral_code', 'CODE')->exists()`

### Issue: Observer not working

**Solution:** Clear cache `php artisan cache:clear` and ensure UserObserver is registered in AppServiceProvider

### Issue: Copy button doesn't work

**Solution:** Check browser console for errors, ensure flasher.js is loaded

## API Endpoints

-   `GET /register?ref={code}` - Register page with pre-filled referral code
-   `POST /register` - Register new user (validates invitation code)

## Model Methods

```php
// Generate new unique code
$code = User::generateReferralCode();

// Get user's referral link
$link = $user->referral_link;

// Get referral count
$count = $user->referral_count;

// Get referrals
$referrals = $user->referrals;

// Get referrer
$referrer = $user->referrer;
```

## Next Steps After Testing

1. ✅ Verify all existing users have codes
2. ✅ Test registration flow with referral link
3. ✅ Test validation with invalid codes
4. ✅ Check dashboard displays correctly
5. ✅ Test copy-to-clipboard functionality
6. ✅ Verify referral tree relationships
7. ⚠️ Consider adding referral rewards/incentives
8. ⚠️ Consider email notifications for new referrals
9. ⚠️ Consider referral analytics page
