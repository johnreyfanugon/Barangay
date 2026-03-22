<?php
declare(strict_types=1);
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';
requireLogin();
$flash = getFlash();
$pageTitle = $pageTitle ?? APP_NAME;
$user = currentUser();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="content">
        <header class="topbar">
            <button class="icon-btn" id="sidebarToggle" aria-label="Toggle Sidebar">☰</button>
            <div class="breadcrumbs"><?php echo e($pageTitle); ?></div>
            <div class="user-chip">
                <span><?php echo e($user['name']); ?></span>
                <small><?php echo e($user['role']); ?></small>
            </div>
        </header>
        <?php if ($flash): ?>
            <div class="toast toast-<?php echo e($flash['type']); ?>" id="flashToast"><?php echo e($flash['message']); ?></div>
        <?php endif; ?>
