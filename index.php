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
<div class="auth-orb orb-1" aria-hidden="true"></div>
<div class="auth-orb orb-2" aria-hidden="true"></div>
<main class="auth-wrap">
    <section class="auth-hero" aria-hidden="true">
        <div class="hero-badge">Community Wellness Platform</div>
        <h1>Barangay Community Health Check Details System</h1>
        <p>A secure, responsive, modern platform for patient care, health records, and data-driven monitoring.</p>
        <div class="hero-panel">
            <h3>Mission &amp; Vision</h3>
            <p>Deliver compassionate, efficient, and data-driven community healthcare through trusted digital services.</p>
        </div>
        <ul class="hero-points">
            <li>Realtime insights dashboard</li>
            <li>Secure role-based access</li>
            <li>Fast and reliable records management</li>
        </ul>
    </section>
    <form class="auth-card" action="php/auth_login.php" method="post" id="loginForm" novalidate>
        <div class="auth-card-head">
            <h2>Welcome Back</h2>
            <p>Sign in to continue managing your barangay health services.</p>
        </div>
        <?php if ($flash): ?>
            <div class="msg msg-<?php echo e($flash['type']); ?>" role="status"><?php echo e($flash['message']); ?></div>
        <?php endif; ?>
        <div class="msg msg-error hidden" id="loginError" role="alert"></div>
        <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" required value="<?php echo e($rememberedEmail); ?>" placeholder="name@example.com" autocomplete="email">
        <label for="password">Password</label>
        <div class="password-group">
            <input type="password" name="password" id="password" required minlength="6" placeholder="Enter password" autocomplete="current-password">
            <button type="button" id="togglePassword" aria-label="Toggle password visibility">Show</button>
        </div>
        <div class="auth-row">
            <label class="remember"><input type="checkbox" name="remember_me" <?php echo $rememberedEmail !== '' ? 'checked' : ''; ?>> Remember Me</label>
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
        <button type="submit" class="btn-main" id="signInBtn">
            <span class="btn-text">Sign In</span>
            <span class="btn-loader" aria-hidden="true"></span>
        </button>
        <small class="auth-footnote">Protected by secure session authentication and encrypted passwords.</small>
    </form>
</main>
<script src="js/login.js"></script>
</body>
</html>
