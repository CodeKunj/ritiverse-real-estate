<?php
/**
 * RiTiVerse - Core Security, Session Guard & Protection Engine
 * Production-ready security controls compliant with OWASP Top 10
 */

// 1. Enforce Secure Session Settings (Must be set before session starts)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_samesite', 'Lax');

    $isHttps = (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
        (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
    );

    if ($isHttps) {
        ini_set('session.cookie_secure', '1');
    }

    session_start();
}

// 2. Dispatch Essential HTTP Security Headers
if (!headers_sent()) {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    
    // Disable PHP version disclosure
    @header_remove('X-Powered-By');
}

// 3. Environment & Production Error Handling
$isProduction = (getenv('APP_ENV') === 'production');
$isDebug = (getenv('APP_DEBUG') === 'true');

if ($isProduction && !$isDebug) {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
    ini_set('log_errors', '1');
} else {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

class Security
{
    /**
     * File-backed rate limiter for public endpoints and login guards
     *
     * @param string $action Unique action identifier (e.g. 'login', 'inquiry')
     * @param int $maxAttempts Maximum allowed attempts in the window
     * @param int $decaySeconds Time window in seconds
     * @param string|null $identifier IP or user identifier (defaults to client IP)
     * @return array ['allowed' => bool, 'remaining' => int, 'retry_after' => int]
     */
    public static function checkRateLimit(string $action, int $maxAttempts = 5, int $decaySeconds = 900, ?string $identifier = null): array
    {
        $id = $identifier ?: ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
        $hash = md5($action . '_' . $id);
        $tmpDir = sys_get_temp_dir() . '/ritiverse_rl';

        if (!is_dir($tmpDir)) {
            @mkdir($tmpDir, 0700, true);
        }

        $file = $tmpDir . '/' . $hash . '.json';
        $now = time();
        $data = ['attempts' => 0, 'first_attempt' => $now];

        if (file_exists($file)) {
            $content = @file_get_contents($file);
            $parsed = json_decode($content, true);
            if (is_array($parsed) && isset($parsed['attempts'], $parsed['first_attempt'])) {
                if ($now - $parsed['first_attempt'] < $decaySeconds) {
                    $data = $parsed;
                }
            }
        }

        if ($data['attempts'] >= $maxAttempts) {
            $retryAfter = $decaySeconds - ($now - $data['first_attempt']);
            return [
                'allowed'     => false,
                'remaining'   => 0,
                'retry_after' => max(1, $retryAfter)
            ];
        }

        return [
            'allowed'     => true,
            'remaining'   => max(0, $maxAttempts - $data['attempts']),
            'retry_after' => 0
        ];
    }

    /**
     * Record an attempt in rate limiter
     */
    public static function hitRateLimit(string $action, int $decaySeconds = 900, ?string $identifier = null): void
    {
        $id = $identifier ?: ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
        $hash = md5($action . '_' . $id);
        $tmpDir = sys_get_temp_dir() . '/ritiverse_rl';

        if (!is_dir($tmpDir)) {
            @mkdir($tmpDir, 0700, true);
        }

        $file = $tmpDir . '/' . $hash . '.json';
        $now = time();
        $data = ['attempts' => 1, 'first_attempt' => $now];

        if (file_exists($file)) {
            $content = @file_get_contents($file);
            $parsed = json_decode($content, true);
            if (is_array($parsed) && isset($parsed['attempts'], $parsed['first_attempt'])) {
                if ($now - $parsed['first_attempt'] < $decaySeconds) {
                    $data['attempts'] = $parsed['attempts'] + 1;
                    $data['first_attempt'] = $parsed['first_attempt'];
                }
            }
        }

        @file_put_contents($file, json_encode($data), LOCK_EX);
    }

    /**
     * Clear rate limiter on successful authentication or action
     */
    public static function resetRateLimit(string $action, ?string $identifier = null): void
    {
        $id = $identifier ?: ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
        $hash = md5($action . '_' . $id);
        $file = sys_get_temp_dir() . '/ritiverse_rl/' . $hash . '.json';
        if (file_exists($file)) {
            @unlink($file);
        }
    }

    /**
     * Sanitize SVG files to prevent Stored XSS and XML External Entity (XXE) injection
     */
    public static function sanitizeSvg(string $svgContent): array
    {
        if (empty(trim($svgContent))) {
            return ['safe' => false, 'error' => 'SVG file is empty.'];
        }

        // Dangerous strings check
        $disallowed = [
            '<!ENTITY',
            '<!DOCTYPE',
            '<script',
            'javascript:',
            'data:text/html',
            'vbscript:',
            'onload=',
            'onerror=',
            'onclick=',
            'onmouseover=',
            'onfocus=',
            'onblur=',
            '<foreignObject',
            'xlink:href="http',
            'xlink:href="https',
        ];

        $lower = strtolower($svgContent);
        foreach ($disallowed as $pattern) {
            if (str_contains($lower, strtolower($pattern))) {
                return [
                    'safe'  => false,
                    'error' => 'SVG file contains disallowed scripting tag or external reference: ' . htmlspecialchars($pattern)
                ];
            }
        }

        // Verify basic SVG structure
        if (!str_contains($lower, '<svg') || !str_contains($lower, '</svg>')) {
            return ['safe' => false, 'error' => 'Invalid SVG document structure.'];
        }

        return ['safe' => true, 'sanitized' => $svgContent];
    }

    /**
     * Sanitize cell data for CSV Export to prevent CSV Formula Injection (Excel / LibreOffice)
     */
    public static function sanitizeCsvCell(?string $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $firstChar = $value[0] ?? '';
        // If starts with =, +, -, @, tab, or carriage return, escape with a single quote
        if (in_array($firstChar, ['=', '+', '-', '@', "\t", "\r"], true)) {
            return "'" . $value;
        }

        return $value;
    }

    /**
     * Sanitize general plain text inputs
     */
    public static function cleanInput(?string $value, int $maxLength = 255): string
    {
        if ($value === null) {
            return '';
        }
        $clean = strip_tags(trim($value));
        if ($maxLength > 0 && mb_strlen($clean) > $maxLength) {
            $clean = mb_substr($clean, 0, $maxLength);
        }
        return $clean;
    }
}
