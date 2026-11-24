<?php
declare(strict_types=1);

final class Lotto
{
    public const PICK = 6;
    public const MAX = 45;
    public const PRICE_CENTS = 200;
    public const MAX_PLAYS = 10;

    public static function parse(mixed $raw): ?array
    {
        if (!is_array($raw) || count($raw) !== self::PICK) {
            return null;
        }
        $numbers = [];
        foreach ($raw as $value) {
            if (!is_string($value) && !is_int($value)) {
                return null;
            }
            $n = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => self::MAX]]);
            if ($n === false) {
                return null;
            }
            $numbers[] = $n;
        }
        if (count(array_unique($numbers)) !== self::PICK) {
            return null;
        }
        sort($numbers);
        return $numbers;
    }

    public static function draw(): array
    {
        $pool = range(1, self::MAX);
        for ($i = count($pool) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$pool[$i], $pool[$j]] = [$pool[$j], $pool[$i]];
        }
        return array_slice($pool, 0, self::PICK);
    }

    public static function matches(array $play, array $drawn): int
    {
        return count(array_intersect($play, $drawn));
    }
}
