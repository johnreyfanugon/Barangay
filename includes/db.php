<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';

function getDb(): PDO
{
    static $conn = null;
    if ($conn instanceof PDO) {
        return $conn;
    }

    try {
        if (DATABASE_URL !== '') {
            $parts = parse_url(DATABASE_URL);
            $host = $parts['host'] ?? PGHOST;
            $port = $parts['port'] ?? PGPORT;
            $dbName = isset($parts['path']) ? ltrim($parts['path'], '/') : PGDATABASE;
            $user = $parts['user'] ?? PGUSER;
            $pass = $parts['pass'] ?? PGPASSWORD;
        } else {
            $host = PGHOST;
            $port = PGPORT;
            $dbName = PGDATABASE;
            $user = PGUSER;
            $pass = PGPASSWORD;
        }

        $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', $host, $port, $dbName);
        $conn = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
        return $conn;
    } catch (Throwable $e) {
        http_response_code(500);
        $hintHost = (PGHOST === '127.0.0.1' || PGHOST === 'localhost') && DATABASE_URL === ''
            ? 'Note: On Render, use your external PostgreSQL URL/host (not 127.0.0.1).'
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
                    <li>DATABASE_URL (recommended), or PGHOST/PGPORT/PGDATABASE/PGUSER/PGPASSWORD</li>
                    <li>PGHOST should be your PostgreSQL provider host</li>
                    <li>PGPORT is usually 5432</li>
                    <li>PGDATABASE / PGUSER / PGPASSWORD must match your Render Postgres values</li>
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
