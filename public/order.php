<?php
declare(strict_types=1);

require dirname(__DIR__) . '/src/bootstrap.php';

$order = Orders::find((int) ($_GET['id'] ?? 0), Auth::require());
if ($order === null) {
    http_response_code(404);
    exit(t('err.not_found'));
}
render('order', ['title' => t('order.title', ['id' => $order['id']]), 'order' => $order]);
