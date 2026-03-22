<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../index.php');
}

if (!verifyCsrf($_POST['csrf_token'] ?? null)) {
    setFlash('error', 'Invalid request token.');
    redirect('../index.php');
}

$email = filter_var(trim((string)($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
$password = (string)($_POST['password'] ?? '');
$rememberMe = isset($_POST['remember_me']);

if (!$email || strlen($password) < 6) {
    setFlash('error', 'Please enter a valid email and password.');
    redirect('../index.php');
}

if (!loginUserByEmailPassword($email, $password, $rememberMe)) {
    setFlash('error', 'Invalid login credentials.');
    redirect('../index.php');
}

setFlash('success', 'Login successful. Welcome!');
redirect('../dashboard.php');
