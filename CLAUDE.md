# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Application Overview

This is the **Eilinger Stiftung** application - a Laravel 10 web application for managing foundation applications and scholarships. The system supports both individual and institutional users applying for different types of financial assistance (Stipendium, Darlehen, Spende).

## Key Architecture Patterns

### Multi-Language Support
- All routes are prefixed with `{locale}` parameter (e.g., `/de/dashboard`, `/en/dashboard`)
- Use `getLocalizedRoute()` helper in tests and views for proper URL generation
- Language files in `lang/de/` and `lang/en/` directories

### Authentication & Security
- **Two-Factor Authentication**: Custom implementation with time-limited codes
- **Session Security**: Session encryption enabled, secure cookies, CSRF protection
- **Admin Authorization**: Custom `IsAdmin` middleware with `admin` route group
- **Middleware Stack**: `auth`, `admin`, `twofactor` for admin routes; `auth`, `verified`, `twofactor` for user routes

### Application Domain Model
- **Application**: Central entity for scholarship/loan requests
- **Related Models**: Account, Cost, Education, Financing, Enclosure, etc.
- **Enums**: Strong typing with backed enums (ApplStatus, Form, Education, etc.)
- **User Types**: `nat` (natural person) vs `jur` (legal entity/institution)

### Livewire Components
- Frontend built primarily with Livewire 3 components
- Form components for application steps: `UserNatForm`, `AddressForm`, `CostForm`, etc.
- Admin components: `Applications`, `Users`, `ReportGenerator`, etc.
- Component naming: `App\Livewire\Admin\*` and `App\Livewire\User\*`

## Essential Commands

### Development
```bash
# Install dependencies
composer install
npm install

# Run development server
php artisan serve
npm run dev

# Build assets for production  
npm run build
```

### Database
```bash
# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/Auth/LoginTest.php

# Run single test method
php artisan test --filter="user_can_login_with_correct_credentials"

# Run tests with coverage (if configured)
php artisan test --coverage
```

### Code Quality
```bash
# Format code with Laravel Pint
./vendor/bin/pint

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Model Relationships & Database

### Core Application Flow
1. **User** creates **Application** (with form type: Stipendium/Darlehen/Spende)
2. **Application** has related models: Account, Cost, Education, Financing
3. **Messages** system for admin-user communication per application
4. **Enclosures** for file uploads per application

### Important Model Constraints
- Most models require both `user_id` and `application_id` (except User and Application)
- All form-related models have `is_draft` boolean field
- Use proper enum values when creating/updating (check `app/Enums/` directory)
- **Critical**: `is_admin` field must be in User model's `$fillable` array for admin tests

### Factory Usage
- User: Always include `is_admin` field (0 or 1)
- Application: Requires `user_id` relationship
- Related models: Require both `user_id` and `application_id`

## Testing Guidelines

### Model Tests
- Create User first, then Application with `user_id`
- For related models (Cost, Account, etc.), always include both `user_id` and `application_id`
- Use correct database field names (e.g., `semester_fees` not `semester_costs`)
- Use proper enum values from `app/Enums/`

### Authentication Tests
- Admin tests require both `is_admin = 1` AND `session(['auth.2fa' => true])`
- When updating user admin status in tests, use fresh database queries and re-authenticate
- TwoFactor middleware only redirects users with active `two_factor_code`

### Route Testing
- Always use `getLocalizedRoute()` for route generation in tests
- Admin routes require: `auth`, `admin`, `twofactor` middleware
- User routes require: `auth`, `verified`, `twofactor` middleware

## Security Considerations

### Two-Factor Authentication
- Uses `random_int()` for cryptographically secure code generation
- 10-minute expiration window
- Rate limiting: 5 verification attempts per minute, 3 resend attempts per 5 minutes
- Session regeneration after successful verification

### Session Security
- Session encryption enabled (`'encrypt' => true`)
- Secure cookies with `SameSite=strict`
- Session ID regeneration on login and 2FA verification

### File Uploads
- Handled through Livewire components with `UploadEnclosure`
- Stored in `storage/app/livewire-tmp/` during processing

## Foundation-Specific Features

### Application Status Workflow
- `NOTSEND` → `PENDING` → `WAITING` → `COMPLETE` → `APPROVED`/`BLOCKED` → `FINISHED`
- Admin can modify status and communicate with users via Messages

### Payment Tracking
- Applications have `payment_amount` and `payment_date` fields
- Reports can be generated for financial tracking

### Multi-Currency Support
- Currency model with exchange rates
- Applications can specify different currencies