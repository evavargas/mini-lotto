<?php
declare(strict_types=1);

require dirname(__DIR__, 2) . '/src/bootstrap.php';
Http::post();

$userId = Http::userId();
$order = Orders::draw((int) ($_POST['id'] ?? 0), $userId);
if ($order === null) {
    Http::fail('err.not_found', 404);
}

Http::json([
    'drawn' => $order['drawn'],
    'plays' => array_map(static fn (array $p): array => [
        'id' => (int) $p['id'],
        'matches' => (int) $p['matches'],
        'prize' => money((int) $p['prize_cents']),
        'won' => (int) $p['prize_cents'] > 0,
    ], $order['plays']),
    'total' => money(Orders::prizeTotal($order)),
]);
