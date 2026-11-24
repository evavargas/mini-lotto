<?php
declare(strict_types=1);

require dirname(__DIR__, 2) . '/src/bootstrap.php';
Http::post();

$numbers = Lotto::parse($_POST['nums'] ?? null);
if ($numbers === null) {
    Http::fail('err.numbers');
}
if (!Cart::add($numbers)) {
    Http::fail('err.cart_full');
}
Http::json(['count' => Cart::count()]);
