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

### 8. Test Teams Page (3-Level Referral Tree)

1. Log in to your account
2. Navigate to Teams page (click Teams icon in bottom navigation)
3. Verify the page displays:
    - Total team size at the top
    - Three level tabs (Level 1, Level 2, Level 3)
    - Statistics for each level (count)
4. Click on each tab to view:
    - Level 1: Your direct referrals
    - Level 2: Referrals of your referrals
    - Level 3: Third-level referrals
5. Each card should show:
    - User name
    - Registration date
    - Referred by (for Level 2 & 3)

### 9. Test Invitation Page

1. Navigate to Invitation page (click Invitation icon on dashboard)
2. Verify the page displays:
    - Referral system overview
    - "How It Works" section (3 steps)
    - "Referral Benefits" section (4 benefits)
    - Referral code and link with copy buttons
    - Your referral statistics
    - List of your direct referrals

### 10. Create Multi-Level Test Data

```bash
php artisan tinker
```

```php
// Create Level 1 user (direct referral)
$level1 = \App\Models\User::create([
    'name' => 'Level 1 User',
    'username' => 'level1_' . rand(1000, 9999),
    'email' => 'level1_' . rand(1000, 9999) . '@example.com',
    'password' => bcrypt('password'),
    'withdrawal_password' => bcrypt('password'),
    'invitation_code' => auth()->user()->referral_code,
    'referred_by' => auth()->id(),
]);

// Create Level 2 user (referral of referral)
$level2 = \App\Models\User::create([
    'name' => 'Level 2 User',
    'username' => 'level2_' . rand(1000, 9999),
    'email' => 'level2_' . rand(1000, 9999) . '@example.com',
    'password' => bcrypt('password'),
    'withdrawal_password' => bcrypt('password'),
    'invitation_code' => $level1->referral_code,
    'referred_by' => $level1->id,
]);

// Create Level 3 user (third-level referral)
$level3 = \App\Models\User::create([
    'name' => 'Level 3 User',
    'username' => 'level3_' . rand(1000, 9999),
    'email' => 'level3_' . rand(1000, 9999) . '@example.com',
    'password' => bcrypt('password'),
    'withdrawal_password' => bcrypt('password'),
    'invitation_code' => $level2->referral_code,
    'referred_by' => $level2->id,
]);

echo "Created 3-level referral tree:\n";
echo "Level 1: {$level1->name} (ID: {$level1->id})\n";
echo "Level 2: {$level2->name} (ID: {$level2->id})\n";
echo "Level 3: {$level3->name} (ID: {$level3->id})\n";
```

### 11. Test Dashboard Referral Section

1. Log in to dashboard
2. Scroll to "Referral System" section
3. Verify it displays:
    - Your referral code (clickable copy button)
    - Your referral link (clickable copy button)
    - Statistics: Total Referrals, Active Referrals, Total Earnings
    - List of recent referrals with names and join dates

### 12. Test Profile Page Invitation Section

1. Navigate to `/me` (profile page)
2. Locate the invitation code in the header section
3. Verify it's displayed prominently with copy functionality
4. Click "Invite" from quick actions menu
5. Should redirect to invitation page

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

## Complete Testing Checklist

### Registration & Code Generation

-   [ ] All existing users have unique referral codes
-   [ ] New users automatically get referral codes on registration
-   [ ] Referral codes are exactly 8 characters uppercase alphanumeric
-   [ ] No duplicate referral codes exist in database

### Referral Link Functionality

-   [ ] Referral link format: `{APP_URL}/register?ref={CODE}`
-   [ ] Clicking referral link opens registration page
-   [ ] Invitation code field is pre-filled with referral code
-   [ ] Invitation code field is read-only when pre-filled
-   [ ] Green border and lock icon appear when field is readonly

### Validation & Error Handling

-   [ ] Invalid referral codes show validation error during registration
-   [ ] Empty invitation code shows required error
-   [ ] Registration succeeds with valid invitation code
-   [ ] Referred_by relationship is created correctly

### Dashboard Display

-   [ ] Referral section shows on dashboard
-   [ ] Referral code displays correctly
-   [ ] Referral link displays correctly
-   [ ] Copy buttons work for both code and link
-   [ ] "Copied" feedback appears for 5 seconds with checkmark icon
-   [ ] Statistics show correct counts
-   [ ] Recent referrals list displays

### Teams Page (3-Level Tree)

-   [ ] Teams page accessible from bottom navigation
-   [ ] Total team size displays correctly at top
-   [ ] Three level tabs (Level 1, 2, 3) are present
-   [ ] Each tab shows correct count
-   [ ] Level 1 shows direct referrals only
-   [ ] Level 2 shows referrals of referrals
-   [ ] Level 3 shows third-level referrals
-   [ ] Each card shows user info and registration date
-   [ ] Level 2 & 3 cards show "Referred by" information
-   [ ] Color coding works (purple → blue → green)

### Invitation Page

-   [ ] Invitation page accessible from dashboard
-   [ ] Page displays referral overview
-   [ ] "How It Works" section shows 3 steps
-   [ ] "Referral Benefits" section shows 4 benefits
-   [ ] Referral code/link with copy functionality
-   [ ] Statistics display correctly
-   [ ] Direct referrals list shows

### Profile Integration

-   [ ] Profile page (`/me`) shows invitation code
-   [ ] Invitation code is copyable from profile
-   [ ] "Invite" quick action works
-   [ ] Profile edit page accessible

### Copy to Clipboard

-   [ ] Copy code button changes to "Copied" with checkmark
-   [ ] Copy link button changes to "Copied" with checkmark
-   [ ] Feedback resets after 5 seconds
-   [ ] Button colors change during copied state (green)
-   [ ] No notification popup appears (removed as per requirement)

### Database Relationships

-   [ ] `referrer()` relationship returns correct user
-   [ ] `referrals()` relationship returns all direct referrals
-   [ ] `referral_count` accessor returns correct count
-   [ ] `referral_link` accessor returns valid URL

### Observer Functionality

-   [ ] UserObserver triggers on user creation
-   [ ] Referral code generates automatically
-   [ ] Observer registered in AppServiceProvider
-   [ ] No errors in logs during user creation

## Next Steps After Testing

1. ✅ Verify all existing users have codes
2. ✅ Test registration flow with referral link
3. ✅ Test validation with invalid codes
4. ✅ Check dashboard displays correctly
5. ✅ Test copy-to-clipboard functionality
6. ✅ Verify referral tree relationships
7. ✅ Test Teams page with 3-level tree
8. ✅ Test Invitation page functionality
9. ✅ Verify profile integration
10. ⚠️ Consider adding referral rewards/incentives
11. ⚠️ Consider email notifications for new referrals
12. ⚠️ Consider referral analytics dashboard for admin

## Performance Considerations

-   Referral tree queries are optimized with eager loading (`with('referrals')`)
-   Consider caching referral counts for users with many referrals
-   Index on `referred_by` column already exists (foreign key)
-   Index on `referral_code` column for faster lookups

## Security Notes

-   Referral codes are generated using `strtoupper(substr(md5(uniqid(rand(), true)), 0, 8))`
-   Validation ensures referral codes exist before allowing registration
-   Readonly invitation field prevents tampering when using referral links
-   User relationships prevent circular referrals
