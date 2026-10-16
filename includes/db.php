<?php
/**
 * RiTiVerse - MySQL Database Connection Layer (PDO)
 * Designed for phpMyAdmin & MySQL / MariaDB
 */

/**
 * Lightweight .env file loader for RiTiVerse
 * Automatically parses project root .env into getenv(), $_ENV, and $_SERVER
 */
if (!function_exists('loadEnvFile')) {
    function loadEnvFile(string $path): void
    {
        if (!file_exists($path) || !is_readable($path)) {
            return;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || str_starts_with($line, ';')) {
                continue;
            }
            if (!str_contains($line, '=')) {
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Strip surrounding quotes
            if (strlen($value) >= 2) {
                $first = $value[0];
                $last = $value[strlen($value) - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                }
            }

            if (!empty($key)) {
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

// Load .env from project root
loadEnvFile(dirname(__DIR__) . '/.env');

// Database Credentials (Loaded dynamically from .env or environment)
if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
if (!defined('DB_PORT')) define('DB_PORT', getenv('DB_PORT') ?: 3306);
if (!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'ritivers_realestate');
if (!defined('DB_USER')) define('DB_USER', getenv('DB_USER') ?: 'root');
if (!defined('DB_PASS')) define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
if (!defined('DB_CHARSET')) define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

class Database
{
    private static ?PDO $pdo = null;
    private static ?string $connectionError = null;

    /**
     * Get active singleton PDO database connection
     */
    public static function getConnection(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_PORT,
            DB_NAME,
            DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            self::$pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            self::$connectionError = null;
            return self::$pdo;
        } catch (PDOException $e) {
            self::$connectionError = $e->getMessage();
            self::renderConnectionError($e);
            exit;
        }
    }

    /**
     * Check if database connection is alive without halting
     */
    public static function isConnected(): bool
    {
        try {
            self::getConnection();
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Get last connection error message
     */
    public static function getLastError(): ?string
    {
        return self::$connectionError;
    }

    /**
     * Render user-friendly error screen when database is not connected
     */
    private static function renderConnectionError(PDOException $e): void
    {
        @error_log("DATABASE CONNECTION ERROR: " . $e->getMessage());

        if (php_sapi_name() === 'cli') {
            fwrite(STDERR, "DATABASE CONNECTION ERROR: " . $e->getMessage() . "\n");
            return;
        }

        if (!headers_sent()) {
            http_response_code(500);
        }

        $isProduction = (getenv('APP_ENV') === 'production');
        $isDebug = (getenv('APP_DEBUG') === 'true');
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title><?= ($isProduction && !$isDebug) ? 'Service Unavailable' : 'Database Connection Required' ?> | RiTiVerse</title>
            <style>
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body {
                    background: #0f172a;
                    color: #f8fafc;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    padding: 1.5rem;
                }
                .error-card {
                    background: #1e293b;
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    border-radius: 1rem;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
                    max-width: 620px;
                    width: 100%;
                    padding: 2.5rem;
                }
                .badge {
                    display: inline-block;
                    background: rgba(202, 130, 22, 0.15);
                    color: #CA8216;
                    border: 1px solid rgba(202, 130, 22, 0.3);
                    padding: 0.35rem 0.85rem;
                    border-radius: 9999px;
                    font-size: 0.8rem;
                    font-weight: 600;
                    margin-bottom: 1.25rem;
                }
                h1 {
                    font-size: 1.5rem;
                    font-weight: 700;
                    color: #ffffff;
                    margin-bottom: 0.75rem;
                }
                p {
                    color: #94a3b8;
                    font-size: 0.95rem;
                    line-height: 1.6;
                    margin-bottom: 1.5rem;
                }
                .steps {
                    background: rgba(15, 23, 42, 0.6);
                    border: 1px solid rgba(255, 255, 255, 0.08);
                    border-radius: 0.75rem;
                    padding: 1.25rem 1.5rem;
                    margin-bottom: 1.5rem;
                }
                .steps h3 {
                    font-size: 0.85rem;
                    text-transform: uppercase;
                    letter-spacing: 0.05em;
                    color: #CA8216;
                    margin-bottom: 0.75rem;
                }
                .steps ol {
                    padding-left: 1.25rem;
                    color: #cbd5e1;
                    font-size: 0.9rem;
                    line-height: 1.7;
                }
                .code-box {
                    background: #090d16;
                    color: #fca5a5;
                    padding: 0.75rem 1rem;
                    border-radius: 0.5rem;
                    font-family: monospace;
                    font-size: 0.82rem;
                    overflow-x: auto;
                    margin-top: 1rem;
                    border: 1px solid rgba(239, 68, 68, 0.2);
                }
                .btn {
                    display: inline-block;
                    background: #CA8216;
                    color: #ffffff;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 0.9rem;
                    padding: 0.75rem 1.5rem;
                    border-radius: 0.5rem;
                    border: none;
                    cursor: pointer;
                }
                .btn:hover { background: #b46f0c; }
            </style>
        </head>
        <body>
            <div class="error-card">
                <?php if ($isProduction && !$isDebug): ?>
                    <span class="badge">Scheduled System Maintenance</span>
                    <h1>Service Temporarily Unavailable</h1>
                    <p>The RiTiVerse digital platform is currently undergoing scheduled infrastructure maintenance. Our services will be restored shortly.</p>
                    <p style="font-size:0.85rem; color:#64748b;">For confidential acquisitions and priority advisory, please contact: <strong style="color:#ffffff;">concierge@ritiverse.com</strong></p>
                    <div style="margin-top: 1.5rem;">
                        <button onclick="window.location.reload()" class="btn">Check Status / Refresh</button>
                    </div>
                <?php else: ?>
                    <span class="badge">Local Development Notice</span>
                    <h1>Connect to MySQL Database</h1>
                    <p>RiTiVerse is configured to load all residences, inquiries, and CMS settings directly from MySQL.</p>
                    
                    <div class="steps">
                        <h3>Quick Setup in phpMyAdmin (3 Steps):</h3>
                        <ol>
                            <li>Open <strong>XAMPP Control Panel</strong> and click <strong>Start</strong> next to <strong>MySQL</strong>.</li>
                            <li>Open browser to <strong>http://localhost/phpmyadmin/</strong>.</li>
                            <li>Click <strong>Import</strong> tab, choose <code>database.sql</code> from the project root folder, and click <strong>Import / Go</strong>.</li>
                        </ol>
                    </div>

                    <p style="font-size: 0.85rem; color: #94a3b8; margin-bottom: 0.5rem;"><strong>Diagnostic Details (Hidden in Production):</strong></p>
                    <div class="code-box"><?= htmlspecialchars($e->getMessage()) ?></div>

                    <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                        <button onclick="window.location.reload()" class="btn">Retry Connection</button>
                        <a href="http://localhost/phpmyadmin/" target="_blank" class="btn" style="background:#334155;">Open phpMyAdmin</a>
                    </div>
                <?php endif; ?>
            </div>
        </body>
        </html>
        <?php
    }
}

/**
 * Global helper function to get PDO database instance
 */
function getDb(): PDO
{
    return Database::getConnection();
}
