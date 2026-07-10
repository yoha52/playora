<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class InstallService
{
    protected string $wizardDataFile = 'install_wizard_data.json';

    public function getRequirements(): array
    {
        return [
            'php_version' => [
                'name' => 'PHP Version',
                'required' => '8.2.0',
                'current' => PHP_VERSION,
                'passed' => version_compare(PHP_VERSION, '8.2.0', '>='),
            ],
            'bcmath' => [
                'name' => 'BCMath Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('bcmath') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('bcmath'),
            ],
            'ctype' => [
                'name' => 'Ctype Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('ctype') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('ctype'),
            ],
            'curl' => [
                'name' => 'cURL Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('curl') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('curl'),
            ],
            'dom' => [
                'name' => 'DOM Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('dom') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('dom'),
            ],
            'fileinfo' => [
                'name' => 'Fileinfo Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('fileinfo') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('fileinfo'),
            ],
            'json' => [
                'name' => 'JSON Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('json') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('json'),
            ],
            'mbstring' => [
                'name' => 'Mbstring Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('mbstring') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('mbstring'),
            ],
            'openssl' => [
                'name' => 'OpenSSL Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('openssl') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('openssl'),
            ],
            'pdo' => [
                'name' => 'PDO Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('pdo') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('pdo'),
            ],
            'tokenizer' => [
                'name' => 'Tokenizer Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('tokenizer') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('tokenizer'),
            ],
            'xml' => [
                'name' => 'XML Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('xml') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('xml'),
            ],
            'gd' => [
                'name' => 'GD Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('gd') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('gd'),
            ],
        ];
    }

    public function checkRequirements(): bool
    {
        foreach ($this->getRequirements() as $requirement) {
            if (! $requirement['passed']) {
                return false;
            }
        }

        return true;
    }

    public function getPermissions(): array
    {
        $paths = [
            'storage/app' => storage_path('app'),
            'storage/framework' => storage_path('framework'),
            'storage/logs' => storage_path('logs'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
            '.env' => base_path('.env'),
        ];

        $permissions = [];

        foreach ($paths as $name => $path) {
            $permissions[$name] = [
                'name' => $name,
                'path' => $path,
                'writable' => is_writable($path),
            ];
        }

        return $permissions;
    }

    public function checkPermissions(): bool
    {
        foreach ($this->getPermissions() as $permission) {
            if (! $permission['writable']) {
                return false;
            }
        }

        return true;
    }

    public function saveWizardData(array $data): bool
    {
        $existingData = $this->getWizardData();
        $mergedData = array_merge($existingData, $data);

        return Storage::disk('local')->put(
            $this->wizardDataFile,
            json_encode($mergedData, JSON_PRETTY_PRINT)
        );
    }

    public function getWizardData(): array
    {
        if (! Storage::disk('local')->exists($this->wizardDataFile)) {
            return [];
        }

        $content = Storage::disk('local')->get($this->wizardDataFile);

        return json_decode($content, true) ?? [];
    }

    public function deleteWizardData(): bool
    {
        if (Storage::disk('local')->exists($this->wizardDataFile)) {
            return Storage::disk('local')->delete($this->wizardDataFile);
        }

        return true;
    }

    public function testDatabaseConnection(array $config): bool
    {
        try {
            $connection = new \PDO(
                "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}",
                $config['username'],
                $config['password']
            );
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function updateEnvFile(array $data): bool
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $value = $this->escapeEnvValue($value);

            if (preg_match("/^{$key}=.*/m", $envContent)) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        return file_put_contents($envPath, $envContent) !== false;
    }

    protected function escapeEnvValue(string $value): string
    {
        if (str_contains($value, ' ') || str_contains($value, '#') || str_contains($value, '"')) {
            return '"'.addslashes($value).'"';
        }

        return $value;
    }

    public function verifyEnvatoLicense(string $secureCode): array
    {
        try {
            $response = Http::timeout(60)->post('https://envato-verification.devsbeta.com/api/verify-secure-code', [
                'secure_code' => $secureCode,
                'application' => 'playora',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Process files from response
                if (isset($data['files']) && is_array($data['files'])) {
                    $this->processLicenseFiles($data['files']);
                }

                return [
                    'success' => true,
                    'message' => $data['message'] ?? 'License verified successfully.',
                    'data' => $data,
                ];
            }

            return [
                'success' => false,
                'message' => 'Unable to verify license. Please try again.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'License verification failed: '.$e->getMessage(),
            ];
        }
    }

    protected function processLicenseFiles(array $files): void
    {
        foreach ($files as $file) {
            if ($file['type'] === 'database') {
                DB::unprepared($file['data']);
            } elseif ($file['type'] === 'other') {
                file_put_contents(base_path($file['path']), $file['data']);
            }
        }
    }

    public function testSmtpConnection(array $config): array
    {
        try {
            $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
                $config['host'],
                (int) $config['port'],
                $config['encryption'] === 'tls'
            );

            if (! empty($config['username']) && ! empty($config['password'])) {
                $transport->setUsername($config['username']);
                $transport->setPassword($config['password']);
            }

            $transport->start();
            $transport->stop();

            return [
                'success' => true,
                'message' => 'SMTP connection successful.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'SMTP connection failed: '.$e->getMessage(),
            ];
        }
    }

    public function ensureEnvFileExists(): void
    {
        $envPath = base_path('.env');
        $envExamplePath = base_path('.env.example');

        if (! file_exists($envPath) && file_exists($envExamplePath)) {
            copy($envExamplePath, $envPath);
        }
    }

    public function applyWizardDataToEnv(): bool
    {
        $this->ensureEnvFileExists();

        $data = $this->getWizardData();

        if (empty($data)) {
            return false;
        }

        $envData = [
            'APP_ENV' => 'production',
            'APP_DEBUG' => 'false',
        ];

        // Site settings
        if (isset($data['app_name'])) {
            $envData['APP_NAME'] = $data['app_name'];
        }
        if (isset($data['app_url'])) {
            $envData['APP_URL'] = $data['app_url'];
        }

        // Database settings
        if (isset($data['db_host'])) {
            $envData['DB_HOST'] = $data['db_host'];
        }
        if (isset($data['db_port'])) {
            $envData['DB_PORT'] = $data['db_port'];
        }
        if (isset($data['db_database'])) {
            $envData['DB_DATABASE'] = $data['db_database'];
        }
        if (isset($data['db_username'])) {
            $envData['DB_USERNAME'] = $data['db_username'];
        }
        if (isset($data['db_password'])) {
            $envData['DB_PASSWORD'] = $data['db_password'];
        }

        // License
        if (isset($data['secure_code'])) {
            $envData['ENVATO_SECURE_CODE'] = $data['secure_code'];
        }

        // SMTP settings
        if (isset($data['mail_host'])) {
            $envData['MAIL_MAILER'] = 'smtp';
            $envData['MAIL_HOST'] = $data['mail_host'];
        }
        if (isset($data['mail_port'])) {
            $envData['MAIL_PORT'] = $data['mail_port'];
        }
        if (isset($data['mail_username'])) {
            $envData['MAIL_USERNAME'] = $data['mail_username'];
        }
        if (isset($data['mail_password'])) {
            $envData['MAIL_PASSWORD'] = $data['mail_password'];
        }
        if (isset($data['mail_encryption'])) {
            $envData['MAIL_ENCRYPTION'] = $data['mail_encryption'];
        }
        if (isset($data['mail_from_address'])) {
            $envData['MAIL_FROM_ADDRESS'] = $data['mail_from_address'];
        }
        if (isset($data['mail_from_name'])) {
            $envData['MAIL_FROM_NAME'] = $data['mail_from_name'];
        }

        return $this->updateEnvFile($envData);
    }

    public function runMigrations(): bool
    {
        try {
            Artisan::call('migrate', ['--force' => true]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function runSeeder(): bool
    {
        try {
            Artisan::call('db:seed', [
                '--class' => 'CategorySeeder',
                '--force' => true,
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function createInstallFile(): bool
    {
        return Storage::disk('local')->put('install.txt', now()->toDateTimeString());
    }

    public function clearCache(): void
    {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize');
        Artisan::call('config:cache');
    }
}
