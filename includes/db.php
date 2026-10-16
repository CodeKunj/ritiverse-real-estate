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
        if (php_sapi_name() === 'cli') {
            fwrite(STDERR, "DATABASE CONNECTION ERROR: " . $e->getMessage() . "\n");
            return;
        }

        if (!headers_sent()) {
            http_response_code(500);
        }
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Database Connection Required | RiTiVerse</title>
            <style>
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body {
                    background: #f8f9fa;
                    color: #0f172a;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    padding: 1.5rem;
                }
                .error-card {
                    background: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 1rem;
                    box-shadow: 0 10px 35px -5px rgba(15, 23, 42, 0.08);
                    max-width: 640px;
                    width: 100%;
                    padding: 2.5rem;
                }
                .badge {
                    display: inline-block;
                    background: #fee2e2;
                    color: #b91c1c;
                    padding: 0.35rem 0.85rem;
                    border-radius: 9999px;
                    font-size: 0.8rem;
                    font-weight: 600;
                    margin-bottom: 1.25rem;
                }
                h1 {
                    font-size: 1.5rem;
                    font-weight: 700;
                    color: #0f172a;
                    margin-bottom: 0.75rem;
                }
                p {
                    color: #475569;
                    font-size: 0.95rem;
                    line-height: 1.6;
                    margin-bottom: 1.5rem;
                }
                .steps {
                    background: #f8fafc;
                    border: 1px solid #e2e8f0;
                    border-radius: 0.75rem;
                    padding: 1.25rem 1.5rem;
                    margin-bottom: 1.5rem;
                }
                .steps h3 {
                    font-size: 0.9rem;
                    text-transform: uppercase;
                    letter-spacing: 0.05em;
                    color: #CA8216;
                    margin-bottom: 0.75rem;
                }
                .steps ol {
                    padding-left: 1.25rem;
                    color: #334155;
                    font-size: 0.9rem;
                    line-height: 1.7;
                }
                .code-box {
                    background: #0f172a;
                    color: #f8fafc;
                    padding: 0.75rem 1rem;
                    border-radius: 0.5rem;
                    font-family: monospace;
                    font-size: 0.82rem;
                    overflow-x: auto;
                    margin-top: 1rem;
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
                <span class="badge">MySQL Connection Required</span>
                <h1>Connect to MySQL Database</h1>
                <p>RiTiVerse is configured to load all real estate data, inquiries, and CMS settings directly from MySQL via phpMyAdmin.</p>
                
                <div class="steps">
                    <h3>Quick Setup in phpMyAdmin (3 Steps):</h3>
                    <ol>
                        <li>Open <strong>XAMPP Control Panel</strong> and click <strong>Start</strong> next to <strong>MySQL</strong>.</li>
                        <li>Open browser to <strong>http://localhost/phpmyadmin/</strong>.</li>
                        <li>Click <strong>Import</strong> tab, choose the file <code>database.sql</code> from the project root folder, and click <strong>Import / Go</strong>.</li>
                    </ol>
                </div>

                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.5rem;"><strong>Error details:</strong></p>
                <div class="code-box"><?= htmlspecialchars($e->getMessage()) ?></div>

                <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                    <button onclick="window.location.reload()" class="btn">Retry Connection</button>
                    <a href="http://localhost/phpmyadmin/" target="_blank" class="btn" style="background:#0f172a;">Open phpMyAdmin</a>
                </div>
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
