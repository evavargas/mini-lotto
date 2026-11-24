<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function t(string $key, array $params = []): string
{
    return I18n::t($key, $params);
}

function money(int $cents): string
{
    return '$' . number_format($cents / 100, 2);
}

function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    ob_start();
    require ROOT . '/views/' . $view . '.php';
    $content = ob_get_clean();
    require ROOT . '/views/layout.php';
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function ball(int $n, string $class = ''): string
{
    return '<span class="ball ' . e($class) . '" data-n="' . $n . '">' . $n . '</span>';
}
