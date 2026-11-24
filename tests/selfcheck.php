<?php
declare(strict_types=1);

define('ROOT', dirname(__DIR__));
require ROOT . '/src/Lotto.php';
require ROOT . '/src/Prizes.php';
require ROOT . '/src/Draws.php';

assert(Lotto::parse(['1', '2', '3', '4', '5', '45']) === [1, 2, 3, 4, 5, 45]);
assert(Lotto::parse([6, 5, 4, 3, 2, 1]) === [1, 2, 3, 4, 5, 6]);
assert(Lotto::parse([1, 1, 2, 3, 4, 5]) === null);
assert(Lotto::parse([0, 1, 2, 3, 4, 5]) === null);
assert(Lotto::parse([1, 2, 3, 4, 5, 46]) === null);
assert(Lotto::parse([1, 2, 3, 4, 5]) === null);
assert(Lotto::parse(['a', 2, 3, 4, 5, 6]) === null);
assert(Lotto::parse('1,2,3,4,5,6') === null);

for ($i = 0; $i < 200; $i++) {
    $d = Lotto::draw();
    assert(count($d) === 6 && count(array_unique($d)) === 6 && min($d) >= 1 && max($d) <= 45);
}

assert(Lotto::matches([1, 2, 3, 4, 5, 6], [4, 5, 6, 7, 8, 9]) === 3);
assert(Prizes::cents(2, 999) === 0);
assert(Prizes::cents(3, 999) === 500);
assert(Prizes::cents(6, 999) === 999);

$next = Draws::next(new DateTimeImmutable('2026-11-28 20:59:00 UTC'));
assert($next->format('Y-m-d H:i') === '2026-11-28 21:00');
$next = Draws::next(new DateTimeImmutable('2026-11-28 21:00:00 UTC'));
assert($next->format('Y-m-d H:i') === '2026-12-05 21:00');

echo "selfcheck ok\n";
