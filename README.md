# Investment Platform - Complete Documentation

> A modern, feature-rich investment platform built with Laravel 12, designed to manage cryptocurrency investments, deposits, withdrawals, and a comprehensive referral system.

## Table of Contents

-   [System Overview](#system-overview)
-   [Tech Stack](#tech-stack)
-   [Installation](#installation)
-   [Project Structure](#project-structure)
-   [Core Features](#core-features)
    -   [User Management](#user-management)
    -   [Referral System](#referral-system)
    -   [Investment Management](#investment-management)
    -   [Financial Operations](#financial-operations)
    -   [Admin Panel](#admin-panel)
-   [Database Schema](#database-schema)
-   [API Routes](#api-routes)
-   [Configuration](#configuration)

---

## System Overview

This investment platform provides a complete ecosystem for managing investment orders, cryptocurrency payments, referral commissions, and user portfolios. It includes:

-   **User Authentication**: Secure registration, login, and profile management
-   **Investment Orders**: Users can purchase investment packages through multiple platforms
-   **Multiple Payment Gateways**: Support for various cryptocurrency payment methods
-   **Referral System**: Multi-level commission structure (3 levels)
-   **Admin Dashboard**: Comprehensive management tools for administrators
-   **Notification System**: Real-time notifications for deposits, withdrawals, and commission credits
-   **Login Tracking**: Detailed login history with geo-location and device info

---

## Tech Stack

| Component      | Version                        |
| -------------- | ------------------------------ |
| **Laravel**    | 12.40.2                        |
| **PHP**        | 8.2.12                         |
| **Database**   | MySQL                          |
| **Frontend**   | Blade Templates + Tailwind CSS |
| **Testing**    | Pest 3.8.4                     |
| **Build Tool** | Vite                           |

### Key Packages

-   **laravel/breeze** (2.3.8) - Authentication scaffolding
-   **alpinejs** (3.15.2) - Lightweight JavaScript framework
-   **tailwindcss** (3.4.18) - Utility-first CSS framework
-   **phpunit** (11.5.33) - Testing framework
-   **pest** (3.8.4) - Modern PHP testing framework

---

## Installation

### Prerequisites

-   PHP 8.2+
-   MySQL 5.7+
-   Composer
-   Node.js & npm

### Setup Steps

```bash
# 1. Clone the repository
git clone <repository-url>
cd investing

# 2. Install dependencies
composer install
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Database configuration
# Update .env with your database credentials
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=investing
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Run migrations
php artisan migrate

# 6. Compile assets
npm run build

# 7. Start the application
php artisan serve
```

---

## Project Structure

```
investing/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Admin panel controllers
│   │   │   ├── Auth/               # Authentication controllers
│   │   │   ├── DepositController.php
│   │   │   ├── MenuController.php
│   │   │   └── ...
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   │   ├── User.php                # User model with referral fields
│   │   ├── Deposit.php
│   │   ├── Withdrawal.php
│   │   ├── ReferralCommission.php
│   │   ├── Order.php
│   │   ├── Platform.php
│   │   ├── Gateway.php
│   │   └── ...
│   ├── Observers/
│   │   ├── UserObserver.php        # Auto-generates referral codes
│   │   ├── DepositObserver.php
│   │   └── WithdrawalObserver.php
│   ├── Services/
│   │   ├── ReferralCommissionService.php   # Handles commission distribution
│   │   └── LoginTrackingService.php
│   └── Helpers/
│       └── SettingHelper.php
├── database/
│   ├── migrations/                 # Database migrations
│   ├── factories/                  # Model factories for testing
│   └── seeders/                    # Database seeders
├── resources/
│   ├── views/
│   │   ├── admin/                  # Admin panel views
│   │   ├── dashboard.blade.php
│   │   └── ...
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php                     # Web routes
│   ├── auth.php                    # Auth routes
│   └── console.php
├── config/
│   ├── app.php
│   ├── database.php
│   └── ...
└── public/
    ├── uploads/
    │   └── avatar/                 # User avatars
    └── admin/
        └── images/
```

---

## Core Features

### User Management

#### User Model Fields

```php
users table:
- id: Primary key
- name: User's full name
- email: Unique email address
- username: Unique username
- password: Hashed password
- avatar: Profile picture filename
- balance: Current balance (decimal)
- freeze_amount: Frozen balance
- daily_order_limit: Max orders per day
- withdrawal_address: Wallet address for withdrawals
- withdrawal_password: Password for withdrawals
- referral_code: Unique 8-char referral code
- referred_by: ID of referrer
- level1_commission: Level 1 commission percentage
- level2_commission: Level 2 commission percentage
- level3_commission: Level 3 commission percentage
- is_admin: Admin flag
- is_banned: Ban flag
- status: active|inactive|pending
- created_at, updated_at: Timestamps
```

#### User Relationships

```php
// User who referred this user
$user->referrer();

// All users referred by this user
$user->referrals();

// Deposits
$user->deposits();

// Withdrawals
$user->withdrawals();

// Orders
$user->userOrderSets();
```

---

### Referral System

The referral system is a complete multi-level commission structure that automatically tracks and credits commissions.

#### Key Features

1. **Unique Referral Codes**

    - 8-character unique codes (auto-generated on user creation)
    - One-click copy functionality
    - Shared via referral links

2. **Referral Links**

    ```
    https://yoursite.com/register?ref=XXXXXXXX
    ```

    - Invitation code auto-filled on registration
    - Field becomes read-only to prevent tampering

3. **Multi-Level Commissions**

    - **Level 1**: Direct referrals (e.g., 10%)
    - **Level 2**: Referrals of Level 1 users (e.g., 5%)
    - **Level 3**: Referrals of Level 2 users (e.g., 2%)

4. **Commission Distribution**
    - Triggered when referred user deposits
    - Commissions automatically added to referrer's balance
    - Notifications sent to referrer
    - Recorded in `referral_commissions` table

#### Database Tables

```php
// referral_commissions table
- id: Primary key
- user_id: Referrer's user ID
- referred_user_id: Who made the deposit
- deposit_id: Associated deposit
- level: Commission level (1, 2, or 3)
- deposit_amount: Amount that triggered commission
- commission_percentage: Commission rate
- commission_amount: Calculated commission
- balance_before: Balance before credit
- balance_after: Balance after credit
- created_at, updated_at
```

#### Referral Dashboard

Users can view on their dashboard:

-   Their referral code (with copy button)
-   Their referral link (with copy button)
-   Total referrals count
-   List of all referred users
-   Commission structure information

#### Commission Calculation Example

```
User A deposits $1000
  → User B (referrer) earns $100 (10% - Level 1)
    → User C (User B's referrer) earns $50 (5% - Level 2)
      → User D (User C's referrer) earns $20 (2% - Level 3)
```

---

### Investment Management

#### Platforms

Platforms represent different investment opportunities:

```php
platforms table:
- id
- name: Platform name
- package_name: Package identifier
- commission: Commission percentage
- start_price: Minimum investment
- end_price: Maximum investment
- image: Platform image
```

#### Order Sets

Order sets are predefined bundles of products:

```php
order_sets table:
- id
- name: Order set name
- platform_id: Associated platform
- is_active: Active flag
```

#### Products

Individual investment products:

```php
products table:
- id
- name: Product name
- price: Product price
- platform_id: Associated platform
- quantity: Available quantity
- image: Product image
- description: Rich text description
- is_active: Active flag
```

#### Product Packages

Combinations of products that users can purchase:

```php
product_packages table:
- id
- order_set_id: Associated order set
- platform_id: Associated platform
- type: Package type
- package_id: Package identifier
- profit_percentage: Expected profit
- is_active: Active flag
```

#### User Orders

When a user purchases products:

```php
user_orders table:
- id
- user_order_set_id: Associated order set for user
- product_package_item_id: Product reference
- order_number: Unique order ID
- type: Order type
- product_name: Product name
- quantity: Quantity purchased
- price: Unit price
- order_amount: Total amount
- profit_amount: Expected profit
- balance_after: Balance after order
- status: pending|completed|cancelled
- manage_crypto: JSON field with payment details + product image
- paid_at: Payment timestamp
```

The `manage_crypto` JSON field stores:

```json
{
    "payment_method": "bitcoin",
    "wallet_address": "1A1z...",
    "amount": 1000,
    "currency": "USD",
    "image": "product-image.jpg"
}
```

---

### Financial Operations

#### Deposits

```php
deposits table:
- id
- user_id
- gateway_id: Payment gateway used
- order_number: Unique deposit ID
- amount: Deposit amount
- approved_amount: Amount approved by admin
- currency: Currency code
- txn_id: Transaction ID (optional)
- screenshot_path: Payment proof screenshot
- status: pending|approved|rejected
- admin_note: Admin notes
- created_at, updated_at
```

**Deposit Flow**:

1. User selects payment gateway
2. User submits payment proof (screenshot/txn ID)
3. Admin reviews and approves/rejects
4. On approval:
    - Balance credited to user
    - Referral commissions calculated
    - Transaction logged
    - Notifications sent

#### Withdrawals

```php
withdrawals table:
- id
- user_id
- order_number: Unique withdrawal ID
- amount: Withdrawal amount
- wallet_address: Crypto wallet address
- currency: Crypto currency
- status: pending|completed|rejected
- admin_note: Admin notes
- created_at, updated_at
```

**Withdrawal Flow**:

1. User requests withdrawal
2. User provides wallet address and amount
3. Admin reviews and completes/rejects
4. On completion:
    - Balance deducted
    - Transaction logged
    - Notifications sent

#### Gateways

Payment gateways configuration:

```php
gateways table:
- id
- type: deposit|withdrawal
- name: Gateway name
- currency: Currency code
- country: Country code
- rate_usdt: USDT conversion rate
- charge_type: fixed|percentage
- charge_value: Charge amount
- min_limit: Minimum deposit
- max_limit: Maximum deposit
- description: Gateway description
- address: Payment address/account
- qr_path: QR code image path
- logo_path: Gateway logo path
- requires_txn_id: Boolean
- requires_screenshot: Boolean
- custom_fields: JSON - custom validation fields
- is_active: Active flag
```

---

### Admin Panel

#### Admin Features

1. **Dashboard**

    - Key metrics and statistics
    - Recent activities
    - System overview

2. **User Management**

    - View all users
    - User profiles and details
    - Assign order sets to users
    - Manage balance (add/subtract)
    - Ban/unban users
    - Commission settings
    - Login history
    - Referral tree visualization

3. **Order Management**

    - View all orders
    - Order details and status
    - Manage order sets
    - Manage product packages
    - Manage products

4. **Financial Management**

    - Deposit approvals
    - Withdrawal processing
    - Gateway management
    - Transaction logs

5. **Settings**

    - General settings
    - SEO configuration
    - Captcha enable/disable
    - Gateway configuration
    - Platform settings

6. **Reports**

    - Transaction logs
    - Referral commission reports
    - Login history with geo-location
    - User statistics

7. **Admin Profile**
    - Update profile information
    - Avatar upload
    - Password change
    - Account settings

#### Admin Routes

Key admin routes (all under `/admin` prefix):

```
GET    /admin/dashboard                    # Dashboard
GET    /admin/users                        # All users
GET    /admin/users/{user}                 # User profile
POST   /admin/users/{user}/add-balance     # Add balance
POST   /admin/users/{user}/subtract-balance # Subtract balance
GET    /admin/users/{user}/tree            # Referral tree
POST   /admin/users/{user}/assign-order-set # Assign orders
POST   /admin/users/{user}/ban             # Ban user
POST   /admin/users/{user}/unban           # Unban user

GET    /admin/deposits                     # All deposits
GET    /admin/deposits/{deposit}           # Deposit details
PATCH  /admin/deposits/{deposit}/status    # Update status

GET    /admin/withdrawals                  # All withdrawals
GET    /admin/withdrawals/{withdrawal}     # Withdrawal details
PATCH  /admin/withdrawals/{withdrawal}/status # Update status

GET    /admin/gateways                     # Payment gateways
POST   /admin/gateways                     # Create gateway
PUT    /admin/gateways/{gateway}           # Update gateway
DELETE /admin/gateways/{gateway}           # Delete gateway

GET    /admin/platforms                    # Investment platforms
POST   /admin/platforms                    # Create platform
PUT    /admin/platforms/{platform}         # Update platform
DELETE /admin/platforms/{platform}         # Delete platform

GET    /admin/products                     # Products
POST   /admin/products                     # Create product
PUT    /admin/products/{product}           # Update product
DELETE /admin/products/{product}           # Delete product

GET    /admin/order-sets                   # Order sets
POST   /admin/order-sets                   # Create order set
PUT    /admin/order-sets/{order_set}       # Update order set

GET    /admin/profile                      # Admin profile
PUT    /admin/profile/update               # Update profile

GET    /admin/notifications                # Notifications
GET    /admin/reports/referral-commissions # Commission reports
GET    /admin/reports/login-history        # Login history
```

---

### Notifications

#### Notification Types

1. **Deposit Notifications**

    - Deposit submitted by user
    - Deposit approved by admin
    - Deposit rejected

2. **Withdrawal Notifications**

    - Withdrawal requested
    - Withdrawal completed

3. **Commission Notifications**

    - Commission credited to balance
    - Includes referrer details and amount

4. **Order Notifications**
    - Order placed
    - Order completed

#### Notification System Features

-   **User vs Admin**: `is_for_admin` flag separates notifications
-   **Read Status**: Track read/unread notifications
-   **Mark as Read**: Single or bulk operations
-   **Real-time Updates**: Updated notification badges

```php
notifications table:
- id
- user_id: User who receives notification
- type: Notification type
- title: Notification title
- message: Notification message
- data: Additional JSON data
- is_read: Read status
- is_for_admin: Admin-specific flag
- created_at, updated_at
```

---

### Login Tracking

Detailed login history with geolocation and device info:

```php
login_histories table:
- id
- user_id
- ip_address
- country, region, city, zip_code
- latitude, longitude
- timezone
- isp
- user_agent
- browser, browser_version
- platform, platform_version
- device, device_model
- status: success|failed
- failure_reason
- created_at, updated_at
```

---

## Database Schema

### Core Tables

**users** - User accounts and profiles
**deposits** - Deposit transactions
**withdrawals** - Withdrawal transactions
**gateways** - Payment gateway configurations
**transactions** - Transaction log

**platforms** - Investment platforms
**products** - Investment products
**order_sets** - Product bundles
**product_packages** - Package combinations
**user_orders** - User purchase orders
**user_order_sets** - User assigned order sets

**referral_commissions** - Commission tracking
**notifications** - User notifications
**login_histories** - Login audit trail

### Relationships Diagram

```
Users
├── Deposits (1:M)
├── Withdrawals (1:M)
├── UserOrderSets (1:M)
│   └── UserOrders (1:M)
├── ReferralCommissions as referrer (1:M)
├── ReferralCommissions as referred (1:M)
├── LoginHistories (1:M)
└── Notifications (1:M)

Platforms (1:M)
├── Products
├── OrderSets
│   └── ProductPackages
│       └── ProductPackageItems
└── UserOrderSets

Gateways (1:M)
└── Deposits
```

---

## API Routes

### User Routes

```
GET    /dashboard                          # User dashboard
GET    /profile                            # Edit profile
PATCH  /profile                            # Update profile
POST   /logout                             # Logout

GET    /deposit                            # Deposit page
POST   /deposit                            # Submit deposit
GET    /deposit/confirm                    # Confirm deposit

GET    /withdrawal                         # Withdrawal page
POST   /withdrawal                         # Request withdrawal
GET    /withdrawal/records                 # Withdrawal history

GET    /menu                               # Order menu
GET    /menu/platform/{platform}           # Platform orders
POST   /menu/platform/{platform}/grab-order # Grab order
POST   /menu/order/{order}/submit          # Submit order

GET    /orders                             # User orders
GET    /commissions                        # Commission history

GET    /notifications                      # Notifications
POST   /notifications/{id}/read            # Mark as read
POST   /notifications/read-all             # Mark all as read

GET    /records                            # Transaction records
GET    /service                            # Services
GET    /platform-rules                     # Platform rules
GET    /platform-rules/{id}                # Rule details
GET    /help                               # Help center
```

### Authentication Routes

```
GET    /register                           # Registration form
POST   /register                           # Submit registration
GET    /login                              # Login form
POST   /login                              # Submit login
POST   /logout                             # Logout
GET    /forgot-password                    # Password reset form
POST   /forgot-password                    # Submit reset
GET    /reset-password/{token}             # Reset form
POST   /reset-password                     # Confirm reset
```

---

## Configuration

### Environment Variables

```env
APP_NAME=Investment
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=investing
DB_USERNAME=root
DB_PASSWORD=

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465

# Pagination
PAGINATION_LINKS=10

# Captcha (reCAPTCHA)
ENABLE_CAPTCHA=true
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key
```

### Settings Management

Global settings are stored in the `settings` table with caching:

```php
// Get setting
setting('site_title')
setting('enable_captcha')
setting('logo_path')

// Set setting
Setting::updateOrCreate(['key' => 'site_title'], ['value' => 'My Site']);
```

### Cache

Settings are cached in `bootstrap/cache/settings.json`. Clear cache after updates:

```php
cache()->forget('settings.all');
```

---

## Development

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AuthTest.php

# Run with coverage
php artisan test --coverage
```

### Database

```bash
# Fresh migration
php artisan migrate:fresh

# Seed database
php artisan db:seed

# Fresh + seed
php artisan migrate:fresh --seed
```

### Assets

```bash
# Development build
npm run dev

# Production build
npm run build

# Watch for changes
npm run watch
```

---

## Troubleshooting

### Common Issues

1. **Avatar Upload Path**

    - Avatars stored in: `public/uploads/avatar/`
    - Create directory if missing: `mkdir -p public/uploads/avatar`

2. **Captcha Not Working**

    - Check `.env` for captcha settings
    - Verify reCAPTCHA keys are valid
    - Enable/disable via admin settings

3. **Commission Not Credited**

    - Check `ReferralCommissionService` is running on deposit approval
    - Verify referral codes are unique
    - Check commission percentages in settings

4. **Login History Missing Geo-data**
    - Some IP addresses may not resolve
    - Consider IP geolocation service integration

---

## Security

### Best Practices Implemented

-   ✅ CSRF protection on all forms
-   ✅ Password hashing (bcrypt)
-   ✅ Email verification
-   ✅ Ban/lock system for users
-   ✅ Admin authentication
-   ✅ Withdrawal password
-   ✅ Login history tracking
-   ✅ Rate limiting (optional)

### Recommendations

1. Enable HTTPS in production
2. Use strong admin passwords
3. Regular backups
4. Monitor login histories
5. Keep dependencies updated
6. Implement IP whitelisting for admin

---

## Support & Contribution

For issues, feature requests, or contributions, please contact the development team.

---

## License

This project is proprietary and confidential.

---

**Last Updated**: December 2025
**Laravel Version**: 12.40.2
**PHP Version**: 8.2.12
