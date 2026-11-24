<h1 class="h3"><?= e(t('play.title')) ?></h1>
<p><?= e(t('play.help', ['price' => money(Lotto::PRICE_CENTS)])) ?></p>

<div id="picker">
    <div class="ball-grid" role="group" aria-label="<?= e(t('play.grid')) ?>">
        <?php for ($n = 1; $n <= Lotto::MAX; $n++): ?>
            <button type="button" class="ball ball-btn" data-n="<?= $n ?>" aria-pressed="false"><?= $n ?></button>
        <?php endfor; ?>
    </div>

    <h2 class="h5 mt-4" id="selected-title"><?= e(t('play.selected')) ?></h2>
    <div class="balls slots" aria-labelledby="selected-title" id="slots">
        <?php for ($i = 0; $i < Lotto::PICK; $i++): ?><span class="ball ball-empty" aria-hidden="true"></span><?php endfor; ?>
    </div>
    <p class="text-muted" id="pick-status" role="status" aria-live="polite"><?= e(t('play.count', ['n' => 0])) ?></p>

    <div class="d-flex flex-wrap picker-actions">
        <button type="button" class="btn btn-outline-theme" id="pick-random"><?= e(t('play.random')) ?></button>
        <button type="button" class="btn btn-outline-theme" id="pick-clear"><?= e(t('play.clear')) ?></button>
        <button type="button" class="btn btn-accent" id="pick-add" disabled><?= e(t('play.add')) ?></button>
    </div>
    <div class="alert alert-danger mt-3" id="pick-error" role="alert" hidden></div>
</div>
