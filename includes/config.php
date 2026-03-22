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

define('DB_HOST', envValue('DB_HOST', '127.0.0.1'));
define('DB_PORT', envValue('DB_PORT', '3306'));
define('DB_NAME', envValue('DB_NAME', 'barangay_health'));
define('DB_USER', envValue('DB_USER', 'root'));
define('DB_PASS', envValue('DB_PASS', ''));

define('REMEMBER_DAYS', 14);
