<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Asia/Manila');

function envValue(string $key, string $default = ''): string
{
    $value = $_ENV[$key] ?? getenv($key);
    if ($value === false || $value === null || $value === '') {
        return $default;
    }
    return (string)$value;
}

define('APP_NAME', envValue('APP_NAME', 'Barangay Community Health Check Details System'));
define('APP_ENV', envValue('APP_ENV', 'development'));
define('BASE_URL', rtrim(envValue('BASE_URL', ''), '/'));

define('DATABASE_URL', envValue('DATABASE_URL', ''));
define('PGHOST', envValue('PGHOST', '127.0.0.1'));
define('PGPORT', envValue('PGPORT', '5432'));
define('PGDATABASE', envValue('PGDATABASE', 'barangay_details'));
define('PGUSER', envValue('PGUSER', 'postgres'));
define('PGPASSWORD', envValue('PGPASSWORD', ''));

define('REMEMBER_DAYS', 14);
