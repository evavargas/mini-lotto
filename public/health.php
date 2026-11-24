<?php
declare(strict_types=1);

define('ROOT', dirname(__DIR__));
require ROOT . '/src/Db.php';

header('Content-Type: text/plain');
try {
    Db::pdo()->query('SELECT 1');
    echo 'ok';
} catch (Throwable $e) {
    http_response_code(503);
    echo 'db unavailable';
}
