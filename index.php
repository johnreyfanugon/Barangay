<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$flash = getFlash();
$rememberedEmail = $_COOKIE['remember_email'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | <?php echo e(APP_NAME); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
<div class="auth-bg"></div>
<main class="auth-wrap">
    <form class="auth-card" action="php/auth_login.php" method="post" id="loginForm" novalidate>
        <h1>Welcome Back</h1>
        <p>Barangay Community Health Check Details System</p>
        <?php if ($flash): ?>
            <div class="msg msg-<?php echo e($flash['type']); ?>"><?php echo e($flash['message']); ?></div>
        <?php endif; ?>
        <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
        <label>Email</label>
        <input type="email" name="email" id="email" required value="<?php echo e($rememberedEmail); ?>" placeholder="name@example.com">
        <label>Password</label>
        <div class="password-group">
            <input type="password" name="password" id="password" required minlength="6" placeholder="Enter password">
            <button type="button" id="togglePassword">Show</button>
        </div>
        <div class="auth-row">
            <label class="remember"><input type="checkbox" name="remember_me"> Remember Me</label>
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
        <button type="submit" class="btn-main">Sign In</button>
    </form>
</main>
<script src="js/login.js"></script>
</body>
</html>
