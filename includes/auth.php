<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user']['id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        setFlash('error', 'Please log in first.');
        redirect('index.php');
    }
}

function requireRole(array $roles): void
{
    requireLogin();
    $user = currentUser();
    if (!$user || !in_array($user['role'], $roles, true)) {
        setFlash('error', 'You are not authorized to access this page.');
        redirect('dashboard.php');
    }
}

function loginUserByEmailPassword(string $email, string $password, bool $rememberMe): bool
{
    $db = getDb();
    $stmt = $db->prepare('SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();

    if (!$user || !password_verify($password, $user['password'])) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => (int)$user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role']
    ];

    if ($rememberMe) {
        setcookie('remember_email', $email, [
            'expires' => time() + (60 * 60 * 24 * REMEMBER_DAYS),
            'path' => '/',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => false,
            'samesite' => 'Lax'
        ]);
    } else {
        setcookie('remember_email', '', time() - 3600, '/');
    }

    return true;
}
