<?php
declare(strict_types=1);

final class I18n
{
    public const LANGS = ['en', 'es'];

    private static string $lang = 'en';
    private static array $messages = [];

    public static function init(): void
    {
        $requested = $_GET['lang'] ?? null;
        if (is_string($requested) && in_array($requested, self::LANGS, true)) {
            $_SESSION['lang'] = $requested;
        }
        $lang = $_SESSION['lang'] ?? null;
        if ($lang === null) {
            $accept = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 0, 2));
            $lang = in_array($accept, self::LANGS, true) ? $accept : 'en';
        }
        self::$lang = $lang;
        self::$messages = require ROOT . '/lang/' . $lang . '.php';
    }

    public static function lang(): string
    {
        return self::$lang;
    }

    public static function t(string $key, array $params = []): string
    {
        $text = self::$messages[$key] ?? $key;
        foreach ($params as $name => $value) {
            $text = str_replace('{' . $name . '}', (string) $value, $text);
        }
        return $text;
    }

    public static function bundle(string $prefix): array
    {
        $out = [];
        foreach (self::$messages as $key => $text) {
            if (str_starts_with($key, $prefix)) {
                $out[substr($key, strlen($prefix))] = $text;
            }
        }
        return $out;
    }
}
