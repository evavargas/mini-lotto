<?php
declare(strict_types=1);

final class Auth
{
    public static function id(): ?int
    {
        return isset($_SESSION['uid']) ? (int) $_SESSION['uid'] : null;
    }

    public static function email(): ?string
    {
        $id = self::id();
        if ($id === null) {
            return null;
        }
        $row = Db::run('SELECT email FROM users WHERE id = ?', [$id])->fetch();
        return $row ? $row['email'] : null;
    }

    public static function require(): int
    {
        $id = self::id();
        if ($id === null) {
            $next = urlencode($_SERVER['REQUEST_URI'] ?? '/');
            redirect('/?login=1&next=' . $next);
        }
        return $id;
    }

    public static function register(string $email, string $password): ?string
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254) {
            return 'err.email';
        }
        if (strlen($password) < 8 || strlen($password) > 72) {
            return 'err.password';
        }
        if (Db::run('SELECT 1 FROM users WHERE email = ?', [$email])->fetch()) {
            return 'err.email_taken';
        }
        Db::run(
            'INSERT INTO users (email, password_hash, created_at) VALUES (?, ?, ?)',
            [$email, password_hash($password, PASSWORD_DEFAULT), gmdate('c')]
        );
        self::start((int) Db::pdo()->lastInsertId());
        return null;
    }

    public static function login(string $email, string $password): bool
    {
        $row = Db::run('SELECT id, password_hash FROM users WHERE email = ?', [$email])->fetch();
        $hash = $row['password_hash'] ?? '$2y$10$usesomesillystringforsalt.usesomesillystringforsaltuseso';
        if (!password_verify($password, $hash) || !$row) {
            return false;
        }
        self::start((int) $row['id']);
        return true;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_regenerate_id(true);
    }

    private static function start(int $id): void
    {
        $cart = $_SESSION['cart'] ?? [];
        $lang = $_SESSION['lang'] ?? null;
        session_regenerate_id(true);
        $_SESSION = ['uid' => $id, 'cart' => $cart];
        if ($lang !== null) {
            $_SESSION['lang'] = $lang;
        }
    }
}
