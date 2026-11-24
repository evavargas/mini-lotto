<?php
declare(strict_types=1);

final class Prizes
{
    public const FIXED = [3 => 500, 4 => 5000, 5 => 200000];

    public static function cents(int $matches, int $jackpotCents): int
    {
        if ($matches === Lotto::PICK) {
            return $jackpotCents;
        }
        return self::FIXED[$matches] ?? 0;
    }
}
