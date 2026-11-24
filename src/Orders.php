<?php
declare(strict_types=1);

final class Orders
{
    public static function create(int $userId, array $plays, string $method): int
    {
        $pdo = Db::pdo();
        $reference = $method === 'oxxo' ? 'OXXO-' . self::digits(14) : null;
        $pdo->beginTransaction();
        try {
            Db::run(
                'INSERT INTO orders (user_id, method, reference, total_cents, status, created_at) VALUES (?, ?, ?, ?, ?, ?)',
                [$userId, $method, $reference, count($plays) * Lotto::PRICE_CENTS, 'paid', gmdate('c')]
            );
            $orderId = (int) $pdo->lastInsertId();
            foreach ($plays as $play) {
                Db::run('INSERT INTO plays (order_id, numbers) VALUES (?, ?)', [$orderId, implode(',', $play['numbers'])]);
            }
            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
        return $orderId;
    }

    public static function find(int $orderId, int $userId): ?array
    {
        $order = Db::run('SELECT * FROM orders WHERE id = ? AND user_id = ?', [$orderId, $userId])->fetch();
        if (!$order) {
            return null;
        }
        $order['drawn'] = $order['drawn_numbers'] === null ? null : self::ints($order['drawn_numbers']);
        $order['plays'] = Db::run('SELECT * FROM plays WHERE order_id = ? ORDER BY id', [$orderId])->fetchAll();
        foreach ($order['plays'] as &$play) {
            $play['numbers'] = self::ints($play['numbers']);
        }
        return $order;
    }

    public static function forUser(int $userId): array
    {
        $ids = Db::run('SELECT id FROM orders WHERE user_id = ? ORDER BY id DESC', [$userId])->fetchAll(PDO::FETCH_COLUMN);
        return array_map(static fn ($id): array => self::find((int) $id, $userId), $ids);
    }

    public static function draw(int $orderId, int $userId): ?array
    {
        $pdo = Db::pdo();
        $order = self::find($orderId, $userId);
        if (!$order) {
            return null;
        }
        if ($order['drawn'] !== null) {
            return $order;
        }
        $drawn = Lotto::draw();
        $jackpot = Draws::jackpot();
        $pdo->beginTransaction();
        try {
            $claimed = Db::run(
                'UPDATE orders SET drawn_numbers = ?, drawn_at = ? WHERE id = ? AND drawn_numbers IS NULL',
                [implode(',', $drawn), gmdate('c'), $orderId]
            )->rowCount();
            if ($claimed === 1) {
                foreach ($order['plays'] as $play) {
                    $matches = Lotto::matches($play['numbers'], $drawn);
                    Db::run(
                        'UPDATE plays SET matches = ?, prize_cents = ? WHERE id = ?',
                        [$matches, Prizes::cents($matches, $jackpot), $play['id']]
                    );
                }
            }
            $pdo->commit();
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
        return self::find($orderId, $userId);
    }

    public static function prizeTotal(array $order): int
    {
        return array_sum(array_column($order['plays'], 'prize_cents'));
    }

    private static function ints(string $csv): array
    {
        return array_map('intval', explode(',', $csv));
    }

    private static function digits(int $length): string
    {
        $out = '';
        for ($i = 0; $i < $length; $i++) {
            $out .= random_int(0, 9);
        }
        return $out;
    }
}
