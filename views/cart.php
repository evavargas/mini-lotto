<h1 class="h3"><?= e(t('cart.title')) ?></h1>
<p id="cart-empty" class="text-muted"<?= $plays ? ' hidden' : '' ?>><?= e(t('cart.empty')) ?></p>

<div id="cart-body"<?= $plays ? '' : ' hidden' ?>>
    <ul class="list-unstyled" id="cart-list">
        <?php foreach ($plays as $i => $play): ?>
            <li class="card-surface mb-2 d-flex align-items-center justify-content-between" data-id="<?= e($play['id']) ?>">
                <div>
                    <div class="small text-muted"><?= e(t('cart.play')) ?> <?= $i + 1 ?> · <?= e(money(Lotto::PRICE_CENTS)) ?></div>
                    <div class="balls"><?php foreach ($play['numbers'] as $n) { echo ball($n); } ?></div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-theme cart-remove" data-id="<?= e($play['id']) ?>" aria-label="<?= e(t('cart.remove', ['n' => $i + 1])) ?>">&times;</button>
            </li>
        <?php endforeach; ?>
    </ul>
    <p class="h5"><?= e(t('cart.total')) ?>: <span id="cart-total"><?= e(money(Cart::totalCents())) ?></span></p>
    <a class="btn btn-accent" href="/checkout.php"><?= e(t('cart.checkout')) ?></a>
</div>
<p class="mt-3"><a href="/play.php"><?= e(t('cart.add_more')) ?></a></p>
