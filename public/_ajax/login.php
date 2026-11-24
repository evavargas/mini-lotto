<?php
declare(strict_types=1);

require dirname(__DIR__, 2) . '/src/bootstrap.php';
Http::post();

$email = trim((string) ($_POST['email'] ?? ''));
$password = (string) ($_POST['password'] ?? '');

if (!Auth::login($email, $password)) {
    Http::fail('err.credentials', 401);
}
Http::json(['ok' => true]);
