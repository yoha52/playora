<?php
// Standalone debug endpoint — remove after use
error_reporting(E_ALL);
ini_set('display_errors', '1');
header('Content-Type: application/json');

$out = [];
$out['php_version'] = PHP_VERSION;
$out['env'] = getenv('APP_ENV');
$out['app_debug'] = getenv('APP_DEBUG');
$out['env_file'] = file_exists(__DIR__.'/../.env') ? 'present' : 'missing';
$out['app_key_env'] = getenv('APP_KEY') ? 'set' : 'missing';

$out['extensions'] = [
    'pdo' => extension_loaded('pdo'),
    'pdo_pgsql' => extension_loaded('pdo_pgsql'),
    'pdo_mysql' => extension_loaded('pdo_mysql'),
    'pgsql' => extension_loaded('pgsql'),
];

// storage writable test
try {
    $testPath = __DIR__.'/../storage/debug_write_test.txt';
    file_put_contents($testPath, date('c'));
    if (file_exists($testPath)) {
        unlink($testPath);
        $out['storage_writable'] = true;
    } else {
        $out['storage_writable'] = false;
    }
} catch (Throwable $e) {
    $out['storage_writable'] = false;
    $out['storage_write_error'] = $e->getMessage();
}

$out['db_connection_env'] = getenv('DB_CONNECTION');

// Try direct PDO connection using env vars
try {
    $dbconn = getenv('DB_CONNECTION');
    $host = getenv('DB_HOST');
    $port = getenv('DB_PORT') ?: '';
    $database = getenv('DB_DATABASE');
    $username = getenv('DB_USERNAME');
    $password = getenv('DB_PASSWORD');

    if ($dbconn === 'pgsql') {
        $dsn = "pgsql:host={$host};port={$port};dbname={$database}";
    } elseif ($dbconn === 'mysql') {
        $dsn = "mysql:host={$host};port={$port};dbname={$database}";
    } else {
        $dsn = null;
        $out['db_driver_warning'] = 'unknown_or_missing';
    }

    if ($dsn) {
        $pdo = new PDO($dsn, $username, $password, [PDO::ATTR_TIMEOUT => 5]);
        $out['db_pdo'] = 'connected';

        try {
            $stmt = $pdo->query('SELECT current_user');
            $current = $stmt ? $stmt->fetchColumn() : null;
            $out['db_current_user'] = $current;
        } catch (Throwable $e) {
            $out['db_current_user_error'] = $e->getMessage();
        }
    }
} catch (Throwable $e) {
    $out['db_pdo'] = 'error';
    $out['db_pdo_message'] = $e->getMessage();
}

echo json_encode($out, JSON_PRETTY_PRINT);
