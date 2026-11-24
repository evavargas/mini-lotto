<?php
declare(strict_types=1);

require dirname(__DIR__) . '/src/bootstrap.php';

render('home', [
    'title' => t('nav.home'),
    'jackpot' => Draws::jackpot(),
    'next' => Draws::next(),
    'recent' => Draws::recent(),
]);
