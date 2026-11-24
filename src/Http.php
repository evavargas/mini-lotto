<?php
declare(strict_types=1);

final class Http
{
    public static function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_THROW_ON_ERROR);
        exit;
    }

    public static function fail(string $key, int $status = 422): never
    {
        self::json(['error' => t($key)], $status);
    }

    public static function post(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            header('Allow: POST');
            self::fail('err.method', 405);
        }
        if (!Csrf::valid($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
            self::fail('err.csrf', 403);
        }
    }

    public static function userId(): int
    {
        $id = Auth::id();
        if ($id === null) {
            self::fail('err.auth', 401);
        }
        return $id;
    }
}
