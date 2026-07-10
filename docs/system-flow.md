# Playora - Sports Ground Booking System

## Table of Contents
1. [Project Overview](#project-overview)
2. [Technology Stack](#technology-stack)
3. [Database Architecture](#database-architecture)
4. [Application Architecture](#application-architecture)
5. [Admin Panel Features](#admin-panel-features)
6. [Mobile API](#mobile-api)
7. [Frontend Architecture](#frontend-architecture)
8. [Key Utilities](#key-utilities)
9. [Authentication](#authentication)
10. [Reports System](#reports-system)

---

## Project Overview

**Playora** is a comprehensive sports ground booking management system designed to facilitate the booking and management of sports facilities. The platform consists of two main interfaces:

1. **Admin Panel (Web Application)**: A full-featured dashboard for administrators to manage categories, grounds, courts, bookings, users, and generate reports.

2. **Mobile API**: RESTful API endpoints for mobile applications that allow users to discover grounds, check availability, and make bookings.

### Key Business Entities

- **Categories**: Types of sports (e.g., Football, Cricket, Tennis)
- **Grounds**: Physical locations/venues that contain courts
- **Courts**: Individual playing areas within grounds with specific operating hours and rates
- **Bookings**: Reservations made by users for specific time slots on courts
- **Users**: Both admin users (web panel) and mobile app users

---

## Technology Stack

### Backend
| Technology | Version | Purpose |
|------------|---------|---------|
| PHP | 8.2.27 | Server-side language |
| Laravel Framework | 12.46.0 | PHP web framework |
| Laravel Sanctum | 4.2.3 | API authentication |
| Livewire | 3.7.3 | Full-stack framework for Laravel |
| Spatie Media Library | - | File/image management |
| MySQL | - | Database engine |

### Frontend
| Technology | Version | Purpose |
|------------|---------|---------|
| Tailwind CSS | 4.1.18 | Utility-first CSS framework |
| Flowbite UI | 2.10.2 | UI component library |
| Alpine.js | (via Livewire) | Reactive JavaScript framework |
| ApexCharts | - | Charts and data visualization |
| FilePond | - | File upload handling |

### Development Tools
| Tool | Version | Purpose |
|------|---------|---------|
| Laravel Pint | 1.27.0 | Code style fixer |
| PHPUnit | 11.5.46 | Testing framework |
| Laravel Sail | 1.52.0 | Docker development environment |

---

## Database Architecture

### Entity Relationship Diagram

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│   Category   │     │    Ground    │     │    Court     │
├──────────────┤     ├──────────────┤     ├──────────────┤
│ id           │     │ id           │     │ id           │
│ name         │     │ name         │     │ ground_id    │◄──┐
│ active       │     │ description  │     │ category_id  │◄──┼──┐
│ created_at   │     │ address      │     │ name         │   │  │
│ updated_at   │     │ latitude     │     │ active       │   │  │
└──────┬───────┘     │ longitude    │     │ opening_time │   │  │
       │             │ location     │     │ closing_time │   │  │
       │             │ parking      │     │ rate_per_hour│   │  │
       │             │ camera       │     │ created_at   │   │  │
       │             │ waiting_area │     │ updated_at   │   │  │
       │             │ changing_room│     └──────┬───────┘   │  │
       │             │ security     │            │           │  │
       │             │ active       │────────────┘           │  │
       │             │ created_at   │                        │  │
       │             │ updated_at   │                        │  │
       │             └──────────────┘                        │  │
       │                                                     │  │
       └─────────────────────────────────────────────────────┘  │
                                                                │
┌──────────────┐     ┌──────────────┐                           │
│    User      │     │   Booking    │                           │
├──────────────┤     ├──────────────┤                           │
│ id           │     │ id           │                           │
│ name         │     │ court_id     │◄──────────────────────────┘
│ email        │     │ user_id      │◄──┐
│ contact_no   │     │ date         │   │
│ password     │     │ start_time   │   │
│ created_at   │     │ end_time     │   │
│ updated_at   │     │ paid_amount  │   │
└──────┬───────┘     │ total_amount │   │
       │             │ status       │   │
       └─────────────│ notes        │───┘
                     │ created_at   │
                     │ updated_at   │
                     └──────────────┘

┌──────────────┐
│   Setting    │
├──────────────┤
│ id           │
│ date_format  │
│ time_format  │
│ currency     │
│ created_at   │
│ updated_at   │
└──────────────┘
```

### Models and Their Relationships

#### Category Model (`app/Models/Category.php`)
- **Has Many**: Courts (through `courts()` relationship)
- **Has Many Through**: Bookings (through courts)
- **Implements**: `HasMedia` (Spatie Media Library)
- **Traits**: `HasActiveScope`, `InteractsWithMedia`
- **Media Collection**: `picture` (single file)

#### Ground Model (`app/Models/Ground.php`)
- **Has Many**: Courts
- **Has Many Through**: Bookings (through courts)
- **Implements**: `HasMedia`
- **Traits**: `HasActiveScope`, `InteractsWithMedia`
- **Media Collection**: `picture` (single file)
- **Facilities**: parking_available, camera_allowed, waiting_area, changing_room, security_locker
- **Location**: Stores latitude/longitude for geolocation features
- **Custom Scope**: `nearBy(lat, lng, radiusKm)` for proximity-based searches

#### Court Model (`app/Models/Court.php`)
- **Belongs To**: Ground, Category
- **Has Many**: Bookings
- **Implements**: `HasMedia`
- **Traits**: `HasActiveScope`, `InteractsWithMedia`
- **Media Collection**: `picture` (single file)
- **Key Fields**: `opening_time`, `closing_time`, `rate_per_hour`

#### Booking Model (`app/Models/Booking.php`)
- **Belongs To**: Court, User
- **Enum**: `status` (BookingStatus: Confirmed, Completed, Cancelled)
- **Custom Scopes**:
  - `forDate($date)` - Filter by specific date
  - `forCourt($courtId)` - Filter by court
  - `confirmed()` - Only confirmed bookings
  - `completed()` - Only completed bookings
  - `cancelled()` - Only cancelled bookings
  - `notCancelled()` - Exclude cancelled bookings
  - `withDue()` - Bookings with outstanding balance
  - `overlapping($startTime, $endTime)` - Check for time conflicts
  - `pastBookings()` - Historical bookings

**Helper Methods**:
- `getDurationInHours()` - Calculate booking duration
- `getBalanceDue()` - Outstanding amount
- `isFullyPaid()` - Check payment status
- `canReceivePayment()` - Business rule validation
- `canCancel()` - Cancellation eligibility

#### User Model (`app/Models/User.php`)
- **Has Many**: Bookings
- **Implements**: `HasMedia`
- **Traits**: `HasApiTokens` (Sanctum), `InteractsWithMedia`
- **Media Collection**: `picture` (profile picture)

#### Setting Model (`app/Models/Setting.php`)
- Stores global application settings
- Fields: `date_format`, `time_format`, `currency`

### BookingStatus Enum (`app/Enums/BookingStatus.php`)

```php
enum BookingStatus: string
{
    case Confirmed = 'confirmed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
```

Provides:
- `label()` - Translated label
- `color()` - Color for UI
- `badgeClasses()` - Tailwind CSS classes for badges

---

## Application Architecture

### Directory Structure

```
app/
├── Enums/
│   └── BookingStatus.php          # Booking status enum
├── Http/
│   ├── Controllers/
│   │   ├── Api/                   # Mobile API controllers
│   │   │   ├── AuthController.php
│   │   │   ├── BookingController.php
│   │   │   ├── CategoryController.php
│   │   │   └── GroundController.php
│   │   ├── BookingController.php  # Web booking management
│   │   ├── CategoryController.php # Category CRUD
│   │   ├── CourtController.php    # Court CRUD
│   │   ├── GroundController.php   # Ground CRUD
│   │   ├── ReportController.php   # Reports generation
│   │   ├── SettingController.php  # App settings
│   │   ├── UploadMediaController.php # File uploads
│   │   └── UserController.php     # User management
│   └── Requests/                  # Form request validation
├── Livewire/
│   ├── Actions/
│   │   └── Logout.php
│   ├── Auth/                      # Authentication components
│   │   ├── ConfirmPassword.php
│   │   ├── ForgotPassword.php
│   │   ├── Login.php
│   │   ├── Register.php
│   │   ├── ResetPassword.php
│   │   └── VerifyEmail.php
│   └── Dashboard.php              # Main dashboard component
├── Models/                        # Eloquent models
│   ├── Booking.php
│   ├── Category.php
│   ├── Court.php
│   ├── Ground.php
│   ├── Setting.php
│   └── User.php
└── Traits/
    └── Scopes/
        └── HasActiveScope.php     # Reusable active scope

resources/
├── js/
│   ├── app.js                     # Main JS entry point
│   └── utils/
│       ├── filepond-manager.js    # FilePond utility
│       └── modal-manager.js       # Modal utility
├── views/
│   ├── bookings/                  # Booking views
│   ├── categories/                # Category views
│   ├── components/                # Blade components
│   │   ├── crud/                  # CRUD modal components
│   │   └── layouts/               # Layout components
│   ├── courts/                    # Court views
│   ├── grounds/                   # Ground views
│   ├── livewire/                  # Livewire views
│   │   └── dashboard.blade.php
│   ├── reports/                   # Report views
│   │   ├── category-stats.blade.php
│   │   ├── court-stats.blade.php
│   │   ├── due-bookings.blade.php
│   │   └── ground-stats.blade.php
│   └── settings/                  # Settings views
└── lang/
    └── en/
        └── general.php            # Language strings
```

---

## Admin Panel Features

### Dashboard (`App\Livewire\Dashboard`)
Location: `app/Livewire/Dashboard.php`

A Livewire-powered dashboard with real-time statistics:

**Row 1 - Entity Counts (Always Visible)**:
- Total Categories
- Total Grounds
- Total Courts

**Row 2 - Booking Statistics (Filtered by Date Range)**:
- Total Bookings
- Total Amount Received
- Total Amount Due

**Date Filter Options**:
- Today
- Yesterday
- This Week
- Last Week
- This Month
- Last Month
- This Year

**Row 3 - Charts and Lists**:
- Category-wise Bookings (ApexCharts Donut Chart)
- 10 Upcoming Bookings list

### Category Management
Route: `/categories`
Controller: `CategoryController`

Features:
- List categories with search and pagination
- Create/Edit via modal
- Image upload using FilePond
- Active/Inactive status toggle
- Delete with confirmation

### Ground Management
Route: `/grounds`
Controller: `GroundController`

Features:
- List grounds with search and pagination
- Create/Edit with multiple facility toggles
- Image upload
- Location data (address, latitude, longitude)
- Active/Inactive status

### Court Management
Route: `/courts`
Controller: `CourtController`

Features:
- List courts with ground/category info
- Create/Edit with ground and category selection
- Operating hours (opening/closing time)
- Hourly rate configuration
- Image upload

### Booking Management
Route: `/bookings`
Controller: `BookingController`

Features:
- List bookings with filters (search, court, date, status)
- Create booking with slot availability check
- Edit booking details
- View booking details
- Receive payment functionality
- Cancel booking
- Delete booking

**Slot Availability Logic**:
1. Fetch court operating hours
2. Generate hourly slots
3. Check existing bookings for conflicts
4. Return available/booked status for each slot

### Settings Management
Route: `/settings`
Controller: `SettingController`

Configurable Settings:
- Date format
- Time format
- Currency

---

## Mobile API

Base URL: `/api/v1/`

### Authentication Endpoints

#### Sign Up
```
POST /api/v1/sign-up
```
**Request Body**:
```json
{
    "name": "string (required)",
    "email": "string (required, unique)",
    "contact_no": "string (required, unique)",
    "password": "string (required, min:6)",
    "password_confirmation": "string (required)"
}
```
**Response**: User data + Bearer token

#### Sign In
```
POST /api/v1/sign-in
```
**Request Body**:
```json
{
    "email": "string (required)",
    "password": "string (required)"
}
```
**Response**: User data + Bearer token

#### Get Profile (Protected)
```
GET /api/v1/get-profile
Authorization: Bearer {token}
```

#### Update Profile (Protected)
```
POST /api/v1/update-profile
Authorization: Bearer {token}
```
**Request Body** (multipart/form-data):
```json
{
    "name": "string (optional)",
    "contact_no": "string (required)",
    "password": "string (optional)",
    "picture": "file (optional)"
}
```

### Category Endpoints

#### List Categories
```
GET /api/v1/categories
```

### Ground Endpoints

#### Get Ground Details
```
GET /api/v1/ground-details?ground_id={id}
```

#### Grounds by Category
```
GET /api/v1/grounds/category?category_id={id}
```

#### Nearby Grounds
```
GET /api/v1/grounds/nearby?lat={latitude}&lng={longitude}&radius={km}
```

#### Popular Grounds
```
GET /api/v1/grounds/popular
```

### Booking Endpoints (Protected)

#### Check Available Slots
```
GET /api/v1/booking/available-slots
```
**Query Parameters**:
- `date` (required, date, after_or_equal:today)
- `court_id` (required, integer)

**Response**:
```json
{
    "court": {
        "id": 1,
        "name": "Court A",
        "rate_per_hour": 100.00,
        "opening_time": "8:00 AM",
        "closing_time": "10:00 PM"
    },
    "date": "2025-01-20",
    "slots": [
        {
            "start_time": "8:00 AM",
            "end_time": "9:00 AM",
            "available": true
        }
    ]
}
```

#### Create Booking
```
POST /api/v1/new-booking
Authorization: Bearer {token}
```
**Request Body**:
```json
{
    "court_id": "integer (required)",
    "date": "date (required, after_or_equal:today)",
    "start_time": "H:i (required)",
    "end_time": "H:i (required, after:start_time)",
    "notes": "string (optional, max:1000)"
}
```

**Validation Rules**:
1. No overlapping bookings
2. Within court operating hours
3. Total amount calculated automatically

#### Get Booking Details
```
GET /api/v1/booking-details?booking_id={id}
Authorization: Bearer {token}
```

#### Upcoming Bookings
```
GET /api/v1/bookings/upcoming
Authorization: Bearer {token}
```

#### Completed Bookings
```
GET /api/v1/bookings/completed
Authorization: Bearer {token}
```

---

## Frontend Architecture

### Main Entry Point (`resources/js/app.js`)

The main JavaScript file that:
1. Imports and registers FilePond with plugins
2. Creates a mutable copy of FilePond for global access
3. Imports utility modules (ModalManager, FilepondManager)
4. Handles dark mode toggle functionality
5. Manages mobile sidebar

```javascript
// FilePond Setup
import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';

FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateType);
window.FilePond = { ...FilePond }; // Mutable copy for wrappers

// Utilities
import { ModalManager } from './utils/modal-manager.js';
import { FilepondManager } from './utils/filepond-manager.js';

window.ModalManager = ModalManager;
window.FilepondManager = FilepondManager;
```

### Dark Mode Implementation

The application supports dark mode with:
1. User preference stored in `localStorage` under key `color-theme`
2. Respects system preference via `prefers-color-scheme` media query
3. Toggle button with sun/moon icons
4. Persists across page navigations (including Livewire soft navigation)

```javascript
function applyThemeFromStorage() {
    if (localStorage.getItem('color-theme') === 'dark' ||
        (!('color-theme' in localStorage) &&
         window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}
```

### Mobile Sidebar

Responsive sidebar with:
- Toggle button on mobile (hamburger/close icons)
- Backdrop overlay when open
- Auto-close on navigation link click
- Supports Livewire soft navigation

---

## Key Utilities

### FilepondManager (`resources/js/utils/filepond-manager.js`)

A utility class for managing FilePond file upload instances with these features:

#### Form Submit Button Control
Automatically disables submit buttons during file uploads to prevent form submission with incomplete uploads:

```javascript
static setupFormSubmitControl(pond) {
    const pondElement = pond.element;
    const form = pondElement ? pondElement.closest('form') : null;

    // Disable on upload start
    pond.on('addfilestart', disableSubmit);
    pond.on('processfilestart', disableSubmit);

    // Enable on upload complete
    pond.on('processfile', enableSubmit);
    pond.on('processfileabort', enableSubmit);
    pond.on('removefile', enableSubmit);
    pond.on('error', enableSubmit);
}
```

#### Global Wrapper
Wraps `window.FilePond.create` to automatically apply submit control to ALL FilePond instances:

```javascript
static applyGlobalWrapper() {
    const originalCreate = window.FilePond.create.bind(window.FilePond);

    window.FilePond.create = (element, options) => {
        const pond = originalCreate(element, options);
        this.setupFormSubmitControl(pond);
        return pond;
    };
}
```

#### Instance Management
- `init(selector, options)` - Initialize FilePond with defaults
- `getInstance(id)` - Get FilePond instance by ID
- `clear(id)` - Clear files from instance
- `destroy(id)` - Destroy instance
- `clearAll()` - Clear all instances

#### Default Configuration
```javascript
{
    server: {
        process: '/upload-media',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    },
    acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp'],
    maxFileSize: '2MB',
    stylePanelLayout: 'compact',
    credits: false
}
```

### ModalManager (`resources/js/utils/modal-manager.js`)

A utility class for managing Flowbite modals:

#### Basic Operations
- `getInstance(modalId)` - Get modal instance from FlowbiteInstances
- `show(modalId)` - Show modal
- `hide(modalId)` - Hide modal
- `toggle(modalId)` - Toggle modal
- `setupCloseButtons(modalId)` - Setup close button handlers

#### CRUD Modal Helper
```javascript
static initCrudModal(config) {
    // Returns object with:
    // - openForCreate() - Open modal in create mode
    // - openForEdit(id, data) - Open modal in edit mode
    // - close() - Close modal
}
```

#### Delete Modal Helper
```javascript
static initDeleteModal(config) {
    // Returns object with:
    // - confirm(id, name) - Show confirmation with item name
    // - close() - Close modal
}
```

---

## Authentication

### Web Authentication (Livewire)

The admin panel uses Livewire-based authentication components:

- **Login**: `App\Livewire\Auth\Login`
- **Register**: `App\Livewire\Auth\Register`
- **Forgot Password**: `App\Livewire\Auth\ForgotPassword`
- **Reset Password**: `App\Livewire\Auth\ResetPassword`
- **Verify Email**: `App\Livewire\Auth\VerifyEmail`
- **Confirm Password**: `App\Livewire\Auth\ConfirmPassword`
- **Logout**: `App\Livewire\Actions\Logout`

### API Authentication (Sanctum)

Mobile API uses Laravel Sanctum for token-based authentication:

1. User signs up or signs in
2. Server returns a Bearer token
3. Client includes token in subsequent requests
4. Token validated via `auth:sanctum` middleware

```php
// Token creation
$token = $user->createToken('auth_token')->plainTextToken;

// Usage in requests
Authorization: Bearer {token}
```

---

## Reports System

### Report Controller (`app/Http/Controllers/ReportController.php`)

All reports require date range filters (from_date, to_date) before displaying data.

### Due Bookings Report
Route: `/reports/due-bookings`

Shows bookings with outstanding payments:
- Filter by date range and court
- Displays: Customer info, booking details, amounts
- Summary totals: Total Amount, Paid Amount, Due Amount

### Ground Stats Report
Route: `/reports/ground-stats`

Statistics by ground:
- Court count
- Booking counts by status (Total, Confirmed, Completed, Cancelled)
- Revenue metrics (Total Revenue, Collected Amount)

### Court Stats Report
Route: `/reports/court-stats`

Statistics by court:
- Filter by ground
- Booking counts by status
- Revenue metrics

### Category Stats Report
Route: `/reports/category-stats`

Statistics by category:
- Court count
- Booking counts by status
- Revenue metrics

### Report Filter UI Pattern

All reports use a collapsible filter card with:
- Filter icon and "Filters" heading
- Alpine.js `x-data="{ open: true }"` for collapse state
- Date range picker (from_date, to_date)
- Additional filters specific to each report
- "Apply Filters" button
- Display message when no filters applied

```html
<div x-data="{ open: true }" class="bg-white shadow-md rounded-lg mb-6 border-l-4 border-brand-500">
    <button @click="open = !open" class="w-full p-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-brand-100 rounded-lg">
                <!-- Filter icon SVG -->
            </div>
            <h2 class="text-lg font-semibold">{{ __('general.filters') }}</h2>
        </div>
        <svg :class="{ 'rotate-180': open }"><!-- Chevron --></svg>
    </button>
    <div x-show="open" x-collapse class="px-4 pb-4">
        <!-- Filter form -->
    </div>
</div>
```

---

## Global Helper Functions

The application provides global JavaScript helper functions through language files:

### `formatDate(date)`
Formats dates according to the configured date format setting.

### `formatTime(time)`
Formats time according to the configured time format setting.

### `formatNumber(number)`
Formats numbers as currency according to the configured currency setting.

### Language Strings Access

**Backend (Blade/PHP)**:
```php
{{ __('general.key_name') }}
```

**Frontend (JavaScript)**:
```javascript
i18n.general.key_name
```

Language strings are served via `/js/lang.js` route.

---

## Coding Conventions

### Eloquent Queries
- Use `Model::query()` instead of `Model::` for better IDE support
- Always use nested array syntax for eager loading with field selection:

```php
Booking::query()->with([
    'court:id,name,ground_id,category_id' => [
        'ground:id,name',
        'category:id,name',
    ],
])->get();
```

### Scopes
- Use Laravel 12's `#[Scope]` attribute for model scopes
- Reusable scopes go in traits (e.g., `HasActiveScope`)

### Form Validation
- Always use Form Request classes
- Use `prepareForValidation()` for data transformation

### API Resources
- Use `@mixin Model` PHPDoc for proper IDE completion:

```php
/** @mixin User */
class UserResource extends JsonResource
{
    // ...
}
```

### Media Handling
- Always use `getFirstMediaUrl('collection')` method
- Never access media relationship directly

---

## Running the Application

### Development
```bash
# Start development server
composer run dev
# or
php artisan serve

# Compile assets (watch mode)
npm run dev

# Compile assets (production)
npm run build
```

### Testing
```bash
# Run all tests
php artisan test --compact

# Run specific test file
php artisan test --compact tests/Feature/ExampleTest.php

# Run with filter
php artisan test --compact --filter=testName
```

### Code Formatting
```bash
# Fix code style
vendor/bin/pint --dirty
```

---

## Environment Configuration

Key environment variables:
- `APP_URL` - Application URL
- `DB_*` - Database connection settings
- `SANCTUM_STATEFUL_DOMAINS` - Domains for Sanctum SPA authentication

---

*This documentation was auto-generated and reflects the current state of the Playora booking system.*
