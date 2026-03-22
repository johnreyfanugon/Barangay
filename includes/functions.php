<?php
declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    if (BASE_URL !== '' && strpos($path, '../') !== 0 && !preg_match('/^https?:\/\//i', $path)) {
        header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
    } else {
        header('Location: ' . $path);
    }
    exit;
}

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function old(string $key, string $default = ''): string
{
    return isset($_POST[$key]) ? trim((string)$_POST[$key]) : $default;
}

function generatePatientUniqueId(PDO $db): string
{
    do {
        $candidate = 'PT-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));
        $stmt = $db->prepare('SELECT id FROM patients WHERE unique_id = :uid LIMIT 1');
        $stmt->execute(['uid' => $candidate]);
        $exists = (bool)$stmt->fetch();
    } while ($exists);
    return $candidate;
}

function computeAge(string $birthdate): int
{
    $birth = new DateTime($birthdate);
    $today = new DateTime();
    return (int)$today->diff($birth)->y;
}

function sanitizeText(string $value): string
{
    return trim(preg_replace('/\s+/', ' ', $value));
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(?string $token): bool
{
    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}
