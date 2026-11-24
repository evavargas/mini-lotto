<?php
declare(strict_types=1);

require dirname(__DIR__) . '/src/bootstrap.php';

render('play', ['title' => t('nav.play')]);
