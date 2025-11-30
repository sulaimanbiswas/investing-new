# Referral System Documentation

## Overview

The application now includes a complete referral/invitation system that allows users to refer new users and build a referral tree.

## Features

### 1. **Unique Referral Codes**

-   Every user automatically gets a unique 8-character referral code upon registration
-   Referral codes are generated using a secure random algorithm (uppercase alphanumeric)
-   The system ensures no duplicate codes exist

### 2. **Referral Links**

-   Users can share their referral link: `https://yoursite.com/register?ref=XXXXXXXX`
-   When someone visits this link, the invitation code field is automatically filled
-   The invitation code field becomes read-only to prevent tampering

### 3. **Referral Validation**

-   During registration, the system validates that the invitation code exists in the database
-   Registration is blocked if an invalid referral code is provided
-   Clear error message: "Invalid invitation code. Please enter a valid referral code from an existing user."

### 4. **Referral Tree**

-   The system tracks who referred whom using the `referred_by` field
-   Each user can see:
    -   Their own referral code
    -   Their referral link
    -   Total number of people they've referred
    -   Who referred them (if anyone)
    -   List of all their direct referrals

### 5. **Dashboard Integration**

-   Beautiful referral card on the dashboard showing:
    -   User's unique referral code with one-click copy
    -   User's referral link with one-click copy
    -   Statistics: total referrals and who referred them
    -   List of all referred users (if any)

## Database Schema

### New Fields Added to `users` Table:

```sql
referral_code VARCHAR(10) UNIQUE NULLABLE    -- User's unique referral code
referred_by   BIGINT UNSIGNED NULLABLE       -- ID of the user who referred this user
```

### Relationships:

-   `referrer()` - BelongsTo relationship to get who referred this user
-   `referrals()` - HasMany relationship to get all users referred by this user

## Implementation Details

### Files Modified/Created:

1. **Migration**: `2025_11_30_161615_add_referral_fields_to_users_table.php`

    - Adds `referral_code` and `referred_by` columns
    - Creates foreign key constraint

2. **User Model**: `app/Models/User.php`

    - Added `referral_code` and `referred_by` to fillable
    - Added `referrer()` and `referrals()` relationships
    - Added `generateReferralCode()` static method

3. **User Observer**: `app/Observers/UserObserver.php`

    - Automatically generates referral code on user creation
    - Ensures every user gets a code even if manually created

4. **RegisteredUserController**: `app/Http/Controllers/Auth/RegisteredUserController.php`

    - Updated `create()` method to accept `ref` query parameter
    - Updated `store()` method to validate invitation code
    - Automatically sets `referred_by` based on invitation code

5. **Register View**: `resources/views/auth/register.blade.php`

    - Auto-fills invitation code from URL parameter
    - Makes field read-only when coming from referral link
    - Shows confirmation message when referral code is applied

6. **Dashboard View**: `resources/views/dashboard.blade.php`

    - Beautiful gradient card showing referral information
    - Copy-to-clipboard functionality for code and link
    - Statistics and referral list

7. **Artisan Command**: `app/Console/Commands/GenerateReferralCodes.php`
    - Command: `php artisan referral:generate`
    - Generates referral codes for existing users who don't have one

## Usage

### For Users:

1. **Get Your Referral Code**

    - Log in to your dashboard
    - Find your referral code in the purple gradient card
    - Click "Copy" to copy your code

2. **Share Your Referral Link**

    - Use the "Copy" button next to your referral link
    - Share this link with friends via email, social media, etc.

3. **View Your Referrals**
    - See your total referral count on the dashboard
    - View a table of all users you've referred (name, username, join date)

### For New Users:

1. **Register with Referral Code**
    - Click on a referral link shared by an existing user
    - The invitation code will be automatically filled
    - Complete the registration form
    - You're now part of the referral tree!

### For Developers:

1. **Generate Codes for Existing Users**

    ```bash
    php artisan referral:generate
    ```

2. **Access Referral Data**

    ```php
    // Get user's referral code
    $code = auth()->user()->referral_code;

    // Get who referred this user
    $referrer = auth()->user()->referrer;

    // Get all users referred by this user
    $referrals = auth()->user()->referrals;

    // Count referrals
    $count = auth()->user()->referrals()->count();

    // Generate a new unique code
    $newCode = User::generateReferralCode();
    ```

3. **Referral Link Format**
    ```php
    route('register', ['ref' => $user->referral_code])
    // Results in: http://yoursite.com/register?ref=XXXXXXXX
    ```

## Security Features

1. **Unique Code Generation**

    - Uses `md5(uniqid(rand(), true))` for randomness
    - Checks database for duplicates before assigning
    - 8-character uppercase alphanumeric format

2. **Database Validation**

    - `exists:users,referral_code` validation rule
    - Foreign key constraint on `referred_by`
    - `onDelete('set null')` to handle user deletions gracefully

3. **Auto-Generation**
    - UserObserver ensures codes are always created
    - No manual intervention needed
    - Works for seeded, factory-created, and manually created users

## Future Enhancements (Optional)

-   Add referral rewards/incentives system
-   Multi-level referral tracking (referrals of referrals)
-   Referral analytics and statistics
-   Email notifications when someone uses your referral
-   Referral leaderboard
-   Referral campaign tracking
-   CSV export of referral data

## Testing

The system has been tested with:

-   ✅ Automatic referral code generation
-   ✅ Referral link auto-fill functionality
-   ✅ Validation of invitation codes
-   ✅ Observer auto-generation on user creation
-   ✅ Copy-to-clipboard functionality
-   ✅ Referral tree relationship queries
-   ✅ Migration for existing users

## Support

For any issues or questions regarding the referral system, please contact the development team.
