<?php
declare(strict_types=1);

require dirname(__DIR__, 2) . '/src/bootstrap.php';
Http::post();

$userId = Http::userId();
$plays = Cart::all();
if ($plays === []) {
    Http::fail('err.cart_empty');
}

$method = (string) ($_POST['method'] ?? '');
if (!in_array($method, ['card', 'oxxo'], true)) {
    Http::fail('err.method_invalid');
}
if ($method === 'card' && preg_replace('/\D/', '', (string) ($_POST['card'] ?? '')) !== '4242424242424242') {
    Http::fail('err.card');
}

$orderId = Orders::create($userId, $plays, $method);
Cart::clear();
Http::json(['redirect' => '/order.php?id=' . $orderId]);
