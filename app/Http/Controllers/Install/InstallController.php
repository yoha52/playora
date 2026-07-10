<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\InstallService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class InstallController extends Controller
{
    public function __construct(
        protected InstallService $installService
    ) {}

    public function welcome(): View
    {
        return view('install.welcome');
    }

    public function requirements(): View
    {
        $requirements = $this->installService->getRequirements();
        $allPassed = $this->installService->checkRequirements();

        return view('install.requirements', compact('requirements', 'allPassed'));
    }

    public function permissions(): View
    {
        $permissions = $this->installService->getPermissions();
        $allPassed = $this->installService->checkPermissions();

        return view('install.permissions', compact('permissions', 'allPassed'));
    }

    public function siteName(): View
    {
        $wizardData = $this->installService->getWizardData();

        return view('install.site-name', compact('wizardData'));
    }

    public function storeSiteName(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'app_url' => ['required', 'url'],
        ]);

        $this->installService->saveWizardData([
            'app_name' => $validated['app_name'],
            'app_url' => $validated['app_url'],
        ]);

        return redirect()->route('install.database');
    }

    public function database(): View
    {
        $wizardData = $this->installService->getWizardData();

        return view('install.database', compact('wizardData'));
    }

    public function storeDatabase(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'db_host' => ['required', 'string'],
            'db_port' => ['required', 'numeric'],
            'db_database' => ['required', 'string'],
            'db_username' => ['required', 'string'],
            'db_password' => ['nullable', 'string'],
        ]);

        $config = [
            'host' => $validated['db_host'],
            'port' => $validated['db_port'],
            'database' => $validated['db_database'],
            'username' => $validated['db_username'],
            'password' => $validated['db_password'] ?? '',
        ];

        if (! $this->installService->testDatabaseConnection($config)) {
            return back()->withErrors([
                'db_host' => 'Could not connect to the database. Please check your credentials.',
            ])->withInput();
        }

        $this->installService->saveWizardData([
            'db_host' => $config['host'],
            'db_port' => $config['port'],
            'db_database' => $config['database'],
            'db_username' => $config['username'],
            'db_password' => $config['password'],
        ]);

        return redirect()->route('install.license');
    }

    public function license(): View
    {
        $wizardData = $this->installService->getWizardData();

        return view('install.license', compact('wizardData'));
    }

    public function storeLicense(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'secure_code' => ['required', 'string'],
        ]);

        // Apply all wizard data to .env after successful license verification
        $this->installService->applyWizardDataToEnv();

        $this->installService->clearCache();

        $result = $this->installService->verifyEnvatoLicense($validated['secure_code']);

        if (! $result['success']) {
            return back()->withErrors([
                'secure_code' => $result['message'],
            ])->withInput();
        }

        // Run category seeder after license verification
        $this->installService->runSeeder();

        return redirect()->route('install.smtp');
    }

    public function smtp(): View
    {
        $wizardData = $this->installService->getWizardData();

        return view('install.smtp', compact('wizardData'));
    }

    public function storeSmtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mail_host' => ['required', 'string'],
            'mail_port' => ['required', 'numeric'],
            'mail_username' => ['nullable', 'string'],
            'mail_password' => ['nullable', 'string'],
            'mail_encryption' => ['nullable', 'string', 'in:tls,ssl,null'],
            'mail_from_address' => ['required', 'email'],
            'mail_from_name' => ['required', 'string'],
        ]);

        $config = [
            'host' => $validated['mail_host'],
            'port' => $validated['mail_port'],
            'username' => $validated['mail_username'] ?? '',
            'password' => $validated['mail_password'] ?? '',
            'encryption' => $validated['mail_encryption'] ?? 'tls',
        ];

        $result = $this->installService->testSmtpConnection($config);

        if (! $result['success']) {
            return back()->withErrors([
                'mail_host' => $result['message'],
            ])->withInput();
        }

        // Update .env with SMTP settings
        $this->installService->updateEnvFile([
            'MAIL_MAILER' => 'smtp',
            'MAIL_HOST' => $validated['mail_host'],
            'MAIL_PORT' => $validated['mail_port'],
            'MAIL_USERNAME' => $validated['mail_username'] ?? '',
            'MAIL_PASSWORD' => $validated['mail_password'] ?? '',
            'MAIL_ENCRYPTION' => $validated['mail_encryption'] ?? 'tls',
            'MAIL_FROM_ADDRESS' => $validated['mail_from_address'],
            'MAIL_FROM_NAME' => $validated['mail_from_name'],
        ]);

        $this->installService->clearCache();

        return redirect()->route('install.admin');
    }

    public function admin(): View
    {
        $wizardData = $this->installService->getWizardData();

        return view('install.admin', compact('wizardData'));
    }

    public function storeAdmin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'contact_no' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Create admin user
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact_no' => $validated['contact_no'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        // Mark installation as complete
        $this->installService->createInstallFile();

        // Clean up wizard data
        $this->installService->deleteWizardData();

        $this->installService->updateEnvFile([
            'SESSION_DRIVER' => 'database',
        ]);

        $this->installService->clearCache();

        Artisan::call('storage:link');

        return redirect()->route('install.complete');
    }

    public function complete(): View
    {
        return view('install.complete');
    }
}
