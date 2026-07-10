# Playora Installation Wizard

This document describes the installation wizard implementation for CodeCanyon submission.

## Overview

The installation wizard guides users through a 9-step process to set up Playora on their server. The wizard checks if the application is already installed by looking for an `install.txt` file in the local storage disk.

Wizard data is stored in a temporary JSON file (`storage/app/install_wizard_data.json`) during the installation process and is only applied to the `.env` file after successful license verification.

## Installation Flow

```
┌─────────────┐    ┌──────────────┐    ┌─────────────┐    ┌───────────┐
│   Welcome   │ -> │ Requirements │ -> │ Permissions │ -> │ Site Name │
└─────────────┘    └──────────────┘    └─────────────┘    └───────────┘
                                                                 │
┌──────────┐    ┌──────┐    ┌─────────┐    ┌──────────┐         │
│ Complete │ <- │ Admin│ <- │  SMTP   │ <- │ License  │ <- ┌────┴────┐
└──────────┘    └──────┘    └─────────┘    └──────────┘    │Database │
                                                           └─────────┘
```

## Wizard Steps

### Step 1: Welcome
- Introduction to the installation process
- Lists prerequisites (database credentials, Envato purchase code, SMTP details)
- Route: `GET /install`

### Step 2: Server Requirements
- Checks PHP version (8.2.0+)
- Validates required PHP extensions:
  - BCMath, Ctype, cURL, DOM, Fileinfo, JSON
  - Mbstring, OpenSSL, PDO, Tokenizer, XML, GD
- Blocks progression if requirements not met
- Route: `GET /install/requirements`

### Step 3: Folder Permissions
- Checks write permissions for:
  - `storage/app`
  - `storage/framework`
  - `storage/logs`
  - `bootstrap/cache`
  - `.env`
- Blocks progression if permissions not set
- Route: `GET /install/permissions`

### Step 4: Site Name
- Configures application name
- Configures application URL
- Data saved to wizard JSON file
- Route: `GET /install/site-name`, `POST /install/site-name`

### Step 5: Database
- Collects database credentials:
  - Host, Port, Database name, Username, Password
- Tests database connection before proceeding
- Data saved to wizard JSON file
- Route: `GET /install/database`, `POST /install/database`

### Step 6: License Verification (Envato)
This step handles Envato license verification with the following flow:

1. **Initial View**: Shows message explaining redirection to Envato
2. **Redirect to Envato**: User clicks button to verify with Envato
   - Redirects to: `https://envato-verification.devsbeta.com/envato-verification`
   - Query params: `callback_url` and `domain`
3. **Return from Envato**: User returns with `secure_code` parameter
4. **Verification**: POST to `https://envato-verification.devsbeta.com/api/verify-secure-code`
   - Params: `secure_code`, `application=playora`
5. **Process Response**: Processes `files` array from response:
   - `type=database`: Executes SQL via `DB::unprepared()`
   - `type=other`: Creates file at specified path
6. **Apply Settings**: Updates `.env` with all collected wizard data:
   - Sets `APP_ENV=production`
   - Sets `APP_DEBUG=false`
   - Copies from `.env.example` if `.env` doesn't exist
7. **Run Migrations**: Executes database migrations
8. **Run Seeder**: Runs CategorySeeder

Route: `GET /install/license`, `POST /install/license`

### Step 7: SMTP Configuration
- Collects SMTP settings:
  - Host, Port, Username, Password
  - Encryption (TLS/SSL/None)
  - From Address, From Name
- Tests SMTP connection before proceeding
- Updates `.env` file with mail settings
- Route: `GET /install/smtp`, `POST /install/smtp`

### Step 8: Admin Account
- Creates the first administrator user:
  - Name, Email, Contact Number, Password
- User is created with verified email
- Route: `GET /install/admin`, `POST /install/admin`

### Step 9: Complete
- Creates `install.txt` file to mark installation complete
- Deletes wizard data JSON file
- Clears all caches
- Provides link to login page
- Route: `GET /install/complete`

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Install/
│   │       └── InstallController.php
│   └── Middleware/
│       ├── InstallMiddleware.php
│       └── RedirectIfNotInstalled.php
└── Services/
    └── InstallService.php

resources/
└── views/
    ├── components/
    │   └── layouts/
    │       └── install.blade.php
    └── install/
        ├── welcome.blade.php
        ├── requirements.blade.php
        ├── permissions.blade.php
        ├── site-name.blade.php
        ├── database.blade.php
        ├── license.blade.php
        ├── smtp.blade.php
        ├── admin.blade.php
        └── complete.blade.php

routes/
└── install.php

lang/
└── en/
    └── install.php

storage/
└── app/
    └── install_wizard_data.json (temporary, deleted after installation)
```

## Middleware

### InstallMiddleware
- Applied to all install routes
- Redirects to login page if `install.txt` exists
- Prevents re-running installation on already installed systems

```php
// app/Http/Middleware/InstallMiddleware.php
if (Storage::disk('local')->exists('install.txt')) {
    return redirect()->route('login');
}
```

### RedirectIfNotInstalled
- Applied to all web routes (except install routes)
- Redirects to installation wizard if `install.txt` doesn't exist
- Forces users to complete installation before accessing the app

```php
// app/Http/Middleware/RedirectIfNotInstalled.php
if (! Storage::disk('local')->exists('install.txt')) {
    return redirect()->route('install.welcome');
}
```

## Configuration

### bootstrap/app.php

The middleware aliases and routes are registered in `bootstrap/app.php`:

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/install.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'install' => InstallMiddleware::class,
            'installed' => RedirectIfNotInstalled::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

### routes/web.php

All web routes are wrapped with the `installed` middleware:

```php
Route::middleware(['installed'])->group(function () {
    // All application routes
    require __DIR__.'/auth.php';
});
```

## InstallService Methods

| Method | Description |
|--------|-------------|
| `getRequirements()` | Returns array of PHP requirements with pass/fail status |
| `checkRequirements()` | Returns true if all requirements pass |
| `getPermissions()` | Returns array of folder permissions with writable status |
| `checkPermissions()` | Returns true if all folders are writable |
| `saveWizardData(array $data)` | Saves data to wizard JSON file (merges with existing) |
| `getWizardData()` | Returns all data from wizard JSON file |
| `deleteWizardData()` | Deletes the wizard JSON file |
| `testDatabaseConnection(array $config)` | Tests MySQL connection with given credentials |
| `ensureEnvFileExists()` | Copies .env.example to .env if .env doesn't exist |
| `updateEnvFile(array $data)` | Updates .env file with key-value pairs |
| `applyWizardDataToEnv()` | Applies all wizard data to .env (sets APP_ENV=production, APP_DEBUG=false) |
| `verifyEnvatoLicense(string $secureCode)` | Verifies license via Envato verification API |
| `processLicenseFiles(array $files)` | Processes files from license verification response |
| `testSmtpConnection(array $config)` | Tests SMTP connection |
| `runMigrations()` | Runs `php artisan migrate --force` |
| `runSeeder()` | Runs CategorySeeder |
| `createInstallFile()` | Creates `install.txt` in local storage |
| `clearCache()` | Clears config, cache, route, and view caches |

## Routes

| Method | URI | Name | Controller Method |
|--------|-----|------|-------------------|
| GET | /install | install.welcome | welcome |
| GET | /install/requirements | install.requirements | requirements |
| GET | /install/permissions | install.permissions | permissions |
| GET | /install/site-name | install.site-name | siteName |
| POST | /install/site-name | install.site-name.store | storeSiteName |
| GET | /install/database | install.database | database |
| POST | /install/database | install.database.store | storeDatabase |
| GET | /install/license | install.license | license |
| POST | /install/license | install.license.store | storeLicense |
| GET | /install/smtp | install.smtp | smtp |
| POST | /install/smtp | install.smtp.store | storeSmtp |
| GET | /install/admin | install.admin | admin |
| POST | /install/admin | install.admin.store | storeAdmin |
| GET | /install/complete | install.complete | complete |

## Envato License Verification Flow

```
User clicks "Verify with Envato"
        │
        ▼
Redirect to: https://envato-verification.devsbeta.com/envato-verification
             ?callback_url={current_url}&domain={scheme_and_host}
        │
        ▼
User logs in to Envato and verifies purchase
        │
        ▼
Redirect back to: /install/license?secure_code={code}
        │
        ▼
User clicks "Continue"
        │
        ▼
POST to: https://envato-verification.devsbeta.com/api/verify-secure-code
         Body: { secure_code, application: "playora" }
        │
        ▼
Process response files:
  - database: DB::unprepared($data)
  - other: file_put_contents(base_path($path), $data)
        │
        ▼
Apply wizard data to .env (APP_ENV=production, APP_DEBUG=false)
        │
        ▼
Run migrations and CategorySeeder
        │
        ▼
Redirect to SMTP step
```

## Translation Strings

All wizard text is translatable via `lang/en/install.php`. Key sections:

- Welcome page strings
- Requirements page strings
- Permissions page strings
- Site configuration strings
- Database configuration strings
- License verification strings
- SMTP configuration strings
- Admin account strings
- Completion page strings
- Navigation strings (Back, Continue, etc.)

## Re-installation

To re-run the installation wizard:

1. Delete the `install.txt` file from `storage/app/`
2. Visit the application URL
3. You will be redirected to the installation wizard

```bash
# Delete install marker file
rm storage/app/install.txt
```

**Warning:** Re-running installation will:
- Potentially overwrite `.env` settings
- Run migrations again (safe for existing data)
- Create a new admin user (duplicate emails will fail)

## Security Considerations

1. **License Verification**: Uses secure Envato OAuth flow via external verification server
2. **Database Credentials**: Tested before being saved to prevent misconfigurations
3. **SMTP Credentials**: Connection tested before saving
4. **Install Lock**: The `install.txt` file prevents unauthorized re-installation
5. **Middleware Protection**: All web routes require installation to be complete
6. **Production Mode**: Sets `APP_ENV=production` and `APP_DEBUG=false` automatically

## Data Storage

### Temporary Wizard Data
During installation, collected data is stored in `storage/app/install_wizard_data.json`:

```json
{
    "app_name": "Playora",
    "app_url": "https://example.com",
    "db_host": "127.0.0.1",
    "db_port": "3306",
    "db_database": "playora",
    "db_username": "root",
    "db_password": "",
    "secure_code": "abc123..."
}
```

This file is automatically deleted after successful installation.

## UI/UX Features

- **Step Indicator**: Visual progress bar showing current step
- **Validation Feedback**: Real-time display of requirements/permissions status
- **Error Handling**: Clear error messages for failed operations
- **Responsive Design**: Works on desktop and mobile devices
- **Dark Mode Support**: Full dark mode support via Tailwind CSS
- **Consistent Styling**: Uses project's brand colors and Flowbite components
