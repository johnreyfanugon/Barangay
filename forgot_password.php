<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
$flash = getFlash();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | <?php echo e(APP_NAME); ?></title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
<div class="auth-bg"></div>
<main class="auth-wrap">
    <form class="auth-card" action="php/auth_forgot.php" method="post">
        <h1>Forgot Password</h1>
        <p>Enter your registered email and we will show a reset message.</p>
        <?php if ($flash): ?>
            <div class="msg msg-<?php echo e($flash['type']); ?>"><?php echo e($flash['message']); ?></div>
        <?php endif; ?>
        <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
        <label>Email</label>
        <input type="email" name="email" required>
        <button type="submit" class="btn-main">Send Reset Instructions</button>
        <a class="back-link" href="index.php">Back to Login</a>
    </form>
</main>
</body>
</html>
