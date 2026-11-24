<?php
declare(strict_types=1);

require dirname(__DIR__, 2) . '/src/bootstrap.php';
Http::post();

Cart::remove((string) ($_POST['id'] ?? ''));
Http::json(['count' => Cart::count(), 'total' => money(Cart::totalCents())]);
