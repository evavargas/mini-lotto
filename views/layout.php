<?php
$lang = I18n::lang();
$email = Auth::email();
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$nav = ['/' => 'nav.home', '/play.php' => 'nav.play', '/my-plays.php' => 'nav.mine'];
$other = $lang === 'en' ? 'es' : 'en';
?>
<!doctype html>
<html lang="<?= e($lang) ?>" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <meta name="csrf-token" content="<?= e(Csrf::token()) ?>">
    <title><?= e($title ?? t('app.name')) ?> · <?= e(t('app.name')) ?></title>
    <script src="/assets/js/theme-init.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body data-auth="<?= $email ? '1' : '0' ?>" data-i18n="<?= e(json_encode(I18n::bundle('js.'))) ?>">
<a class="skip-link" href="#main"><?= e(t('nav.skip')) ?></a>
<header>
    <nav class="navbar navbar-expand-md app-nav" aria-label="<?= e(t('app.name')) ?>">
        <div class="container">
            <a class="navbar-brand" href="/"><?= e(t('app.name')) ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="<?= e(t('nav.toggle')) ?>">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav mr-auto">
                    <?php foreach ($nav as $href => $label): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= e($href) ?>"<?= $path === $href ? ' aria-current="page"' : '' ?>><?= e(t($label)) ?></a>
                        </li>
                    <?php endforeach; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/cart.php"<?= $path === '/cart.php' ? ' aria-current="page"' : '' ?>>
                            <?= e(t('nav.cart')) ?> <span class="badge badge-pill badge-accent" id="cart-count"><?= Cart::count() ?></span>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav align-items-md-center">
                    <li class="nav-item">
                        <a class="nav-link" href="?lang=<?= e($other) ?>" hreflang="<?= e($other) ?>" lang="<?= e($other) ?>" aria-label="<?= e(t('nav.lang')) ?>: <?= e(strtoupper($other)) ?>"><?= e(strtoupper($other)) ?></a>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-sm btn-outline-theme" id="theme-toggle" aria-pressed="false"><?= e(t('nav.theme')) ?></button>
                    </li>
                    <?php if ($email): ?>
                        <li class="nav-item ml-md-2"><button type="button" class="btn btn-sm btn-outline-theme" id="logout"><?= e(t('nav.logout')) ?></button></li>
                    <?php else: ?>
                        <li class="nav-item ml-md-2"><button type="button" class="btn btn-sm btn-accent" id="open-auth"><?= e(t('nav.login')) ?></button></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
<main id="main" class="container py-4" tabindex="-1">
    <?= $content ?>
</main>
<footer class="container py-4 small text-muted">
    <?= e(t('footer.note')) ?>
</footer>

<?php if (!$email): ?>
<div class="modal fade" id="auth-modal" tabindex="-1" role="dialog" aria-labelledby="auth-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h5" id="auth-title"><?= e(t('auth.title')) ?></h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= e(t('auth.close')) ?>"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <?php foreach (['login' => 'auth.login', 'register' => 'auth.register'] as $mode => $label): ?>
                    <form class="auth-form" data-endpoint="/_ajax/<?= $mode ?>.php" data-mode="<?= $mode ?>" novalidate<?= $mode === 'register' ? ' hidden' : '' ?>>
                        <div class="form-group">
                            <label for="<?= $mode ?>-email"><?= e(t('auth.email')) ?></label>
                            <input class="form-control" id="<?= $mode ?>-email" name="email" type="email" autocomplete="email" required>
                        </div>
                        <div class="form-group">
                            <label for="<?= $mode ?>-password"><?= e(t('auth.password')) ?></label>
                            <input class="form-control" id="<?= $mode ?>-password" name="password" type="password" autocomplete="<?= $mode === 'login' ? 'current-password' : 'new-password' ?>" minlength="8" required<?= $mode === 'register' ? ' aria-describedby="register-hint"' : '' ?>>
                            <?php if ($mode === 'register'): ?><small id="register-hint" class="form-text text-muted"><?= e(t('auth.password_hint')) ?></small><?php endif; ?>
                        </div>
                        <div class="alert alert-danger form-error" role="alert" hidden></div>
                        <button type="submit" class="btn btn-accent btn-block"><?= e(t($label)) ?></button>
                        <button type="button" class="btn btn-link btn-block auth-switch"><?= e(t($mode === 'login' ? 'auth.to_register' : 'auth.to_login')) ?></button>
                        <?php if ($mode === 'login'): ?><p class="small text-muted text-center mb-0"><?= e(t('auth.demo')) ?></p><?php endif; ?>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script src="/assets/js/app.js"></script>
</body>
</html>
