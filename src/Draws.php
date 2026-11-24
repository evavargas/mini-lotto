<?php
declare(strict_types=1);

final class Draws
{
    public const JACKPOT_BASE_CENTS = 100000000;

    public static function next(?DateTimeImmutable $now = null): DateTimeImmutable
    {
        $now ??= new DateTimeImmutable('now', new DateTimeZone('UTC'));
        $fixed = getenv('DRAW_AT');
        if ($fixed) {
            $at = new DateTimeImmutable($fixed);
            if ($at > $now) {
                return $at;
            }
        }
        $d = $now->setTime(21, 0);
        while ($d <= $now || $d->format('N') !== '6') {
            $d = $d->modify('+1 day');
        }
        return $d;
    }

    public static function jackpot(): int
    {
        $sales = (int) Db::run('SELECT COALESCE(SUM(total_cents), 0) AS s FROM orders')->fetch()['s'];
        return self::JACKPOT_BASE_CENTS + intdiv($sales, 2);
    }

    public static function recent(int $limit = 3): array
    {
        $rows = Db::run('SELECT drawn_at, numbers, jackpot_cents FROM past_draws ORDER BY drawn_at DESC LIMIT ' . (int) $limit)->fetchAll();
        foreach ($rows as &$row) {
            $row['numbers'] = array_map('intval', explode(',', $row['numbers']));
        }
        return $rows;
    }
}
