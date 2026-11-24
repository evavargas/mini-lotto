<?php
declare(strict_types=1);

require dirname(__DIR__, 2) . '/src/bootstrap.php';
Http::post();

$error = Auth::register(trim((string) ($_POST['email'] ?? '')), (string) ($_POST['password'] ?? ''));
if ($error !== null) {
    Http::fail($error);
}
Http::json(['ok' => true]);
