<?php
declare(strict_types=1);

define('ROOT', dirname(__DIR__));

spl_autoload_register(static function (string $class): void {
    $file = __DIR__ . '/' . $class . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require __DIR__ . '/helpers.php';

ini_set('display_errors', '0');
error_reporting(E_ALL);

set_exception_handler(static function (Throwable $e): void {
    error_log((string) $e);
    http_response_code(500);
    if (str_contains($_SERVER['REQUEST_URI'] ?? '', '/_ajax/')) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Server error']);
    } else {
        echo 'Server error';
    }
});

$https = ($_SERVER['HTTPS'] ?? '') === 'on' || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $https,
    'httponly' => true,
    'samesite' => 'Lax',
]);
ini_set('session.use_strict_mode', '1');
session_start();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header("Content-Security-Policy: default-src 'self'; script-src 'self' https://code.jquery.com https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net; img-src 'self' data:; frame-ancestors 'none'; base-uri 'self'; form-action 'self'");
if ($https) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

I18n::init();
