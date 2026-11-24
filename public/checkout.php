<?php
declare(strict_types=1);

require dirname(__DIR__) . '/src/bootstrap.php';

Auth::require();
if (Cart::count() === 0) {
    redirect('/cart.php');
}
render('checkout', ['title' => t('checkout.title')]);
