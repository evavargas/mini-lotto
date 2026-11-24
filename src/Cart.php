<?php
declare(strict_types=1);

final class Cart
{
    public static function all(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    public static function count(): int
    {
        return count(self::all());
    }

    public static function totalCents(): int
    {
        return self::count() * Lotto::PRICE_CENTS;
    }

    public static function add(array $numbers): bool
    {
        if (self::count() >= Lotto::MAX_PLAYS) {
            return false;
        }
        $_SESSION['cart'][] = ['id' => bin2hex(random_bytes(6)), 'numbers' => $numbers];
        return true;
    }

    public static function remove(string $id): void
    {
        $_SESSION['cart'] = array_values(array_filter(self::all(), static fn (array $p): bool => $p['id'] !== $id));
    }

    public static function clear(): void
    {
        $_SESSION['cart'] = [];
    }
}
