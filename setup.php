<?php
declare(strict_types=1);

define('ROOT', __DIR__);
require ROOT . '/src/Db.php';

if (!is_dir(ROOT . '/data')) {
    mkdir(ROOT . '/data', 0775, true);
}

$pdo = Db::pdo();
$pdo->exec(file_get_contents(ROOT . '/src/schema.sql'));

if ((int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() === 0) {
    Db::run(
        'INSERT INTO users (email, password_hash, created_at) VALUES (?, ?, ?)',
        ['demo@example.com', password_hash('Demo1234!', PASSWORD_DEFAULT), gmdate('c')]
    );
}

if ((int) $pdo->query('SELECT COUNT(*) FROM past_draws')->fetchColumn() === 0) {
    $samples = [['3,11,19,27,34,42', 118400000], ['5,9,22,23,38,45', 112800000], ['1,14,16,30,31,40', 108100000]];
    foreach ($samples as $i => [$numbers, $jackpot]) {
        Db::run(
            'INSERT INTO past_draws (drawn_at, numbers, jackpot_cents) VALUES (?, ?, ?)',
            [gmdate('c', strtotime('last saturday 21:00 UTC') - $i * 7 * 86400), $numbers, $jackpot]
        );
    }
}

echo "Database ready. Demo user: demo@example.com / Demo1234!\n";
