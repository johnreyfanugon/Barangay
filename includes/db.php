<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function getDb(): mysqli
{
    static $conn = null;
    if ($conn instanceof mysqli) {
        return $conn;
    }

    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, (int)DB_PORT);
        $conn->set_charset('utf8mb4');
        return $conn;
    } catch (Throwable $e) {
        http_response_code(500);
        $hintHost = DB_HOST === '127.0.0.1' || DB_HOST === 'localhost'
            ? 'Note: On Render/hosts, use an external MySQL host (not 127.0.0.1).'
            : '';
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Database Connection Error</title>
            <link rel="stylesheet" href="/css/style.css">
            <style>
                .db-error-wrap{min-height:100vh;display:grid;place-items:center;background:linear-gradient(140deg,#e6f7ff,#e9f9ef);}
                .db-card{max-width:720px;background:rgba(255,255,255,.7);backdrop-filter:blur(12px);border:1px solid #fff;border-radius:16px;box-shadow:0 20px 40px rgba(19,63,41,.15);padding:1.2rem}
                .db-card h1{margin:.2rem 0}
                .db-card pre{white-space:pre-wrap;background:#0b3d2e; color:#eafff0; padding:.75rem;border-radius:10px;overflow:auto}
                .tips{color:#325a49}
                .trees{position:fixed;bottom:0;left:0;width:100%;opacity:.25;pointer-events:none}
                .pulse{animation:pulse 1.8s ease-in-out infinite}
                @keyframes pulse{0%{transform:scale(1)}50%{transform:scale(1.01)}100%{transform:scale(1)}}
            </style>
        </head>
        <body>
        <div class="db-error-wrap">
            <div class="db-card pulse">
                <h1>We can’t reach the database</h1>
                <p class="tips">Please verify these environment variables on your server/Render:</p>
                <ul class="tips">
                    <li>DB_HOST (should be your MySQL provider host, not 127.0.0.1 on Render)</li>
                    <li>DB_PORT (usually 3306)</li>
                    <li>DB_NAME</li>
                    <li>DB_USER</li>
                    <li>DB_PASS</li>
                </ul>
                <p class="tips"><?php echo htmlspecialchars($hintHost, ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="tips">Also ensure the schema has been imported: <code>database/schema.sql</code></p>
                <details>
                    <summary>Technical details</summary>
                    <pre><?php echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'); ?></pre>
                </details>
            </div>
        </div>
        <img class="trees" src="/assets/trees.svg" alt="">
        </body>
        </html>
        <?php
        exit;
    }
}
