<?php
declare(strict_types=1);

require dirname(__DIR__, 2) . '/src/bootstrap.php';
Http::post();

Auth::logout();
Http::json(['ok' => true]);
