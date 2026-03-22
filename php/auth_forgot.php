<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../forgot_password.php');
}

if (!verifyCsrf($_POST['csrf_token'] ?? null)) {
    setFlash('error', 'Invalid request token.');
    redirect('../forgot_password.php');
}

$email = filter_var(trim((string)($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
if (!$email) {
    setFlash('error', 'Please provide a valid email address.');
    redirect('../forgot_password.php');
}

setFlash('success', 'If this email is registered, reset instructions have been sent.');
redirect('../index.php');
