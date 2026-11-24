<h1 class="h3"><?= e(t('checkout.title')) ?></h1>
<p class="h5"><?= e(t('cart.total')) ?>: <?= e(money(Cart::totalCents())) ?></p>

<form id="checkout-form" novalidate>
    <fieldset class="form-group">
        <legend class="h6"><?= e(t('checkout.method')) ?></legend>
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="m-card" name="method" value="card" checked>
            <label class="custom-control-label" for="m-card"><?= e(t('checkout.card')) ?></label>
        </div>
        <div class="custom-control custom-radio">
            <input type="radio" class="custom-control-input" id="m-oxxo" name="method" value="oxxo">
            <label class="custom-control-label" for="m-oxxo"><?= e(t('checkout.oxxo')) ?></label>
        </div>
    </fieldset>
    <div class="form-group" id="card-panel">
        <label for="card"><?= e(t('checkout.card_number')) ?></label>
        <input class="form-control" id="card" name="card" inputmode="numeric" autocomplete="off" value="4242 4242 4242 4242" aria-describedby="card-hint">
        <small id="card-hint" class="form-text text-muted"><?= e(t('checkout.card_hint')) ?></small>
    </div>
    <div class="alert alert-danger form-error" role="alert" hidden></div>
    <button type="submit" class="btn btn-accent"><?= e(t('checkout.confirm', ['total' => money(Cart::totalCents())])) ?></button>
</form>
