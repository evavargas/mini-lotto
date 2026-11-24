<?php
declare(strict_types=1);

require dirname(__DIR__) . '/src/bootstrap.php';

render('cart', ['title' => t('nav.cart'), 'plays' => Cart::all()]);
