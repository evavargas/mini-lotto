<?php
declare(strict_types=1);

require dirname(__DIR__) . '/src/bootstrap.php';

render('my-plays', ['title' => t('mine.title'), 'orders' => Orders::forUser(Auth::require())]);
