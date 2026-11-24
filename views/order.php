<h1 class="h3"><?= e(t('order.title', ['id' => $order['id']])) ?></h1>
<p class="text-muted"><?= e(t('order.paid')) ?> <?= e(money((int) $order['total_cents'])) ?></p>
<?php if ($order['reference']): ?>
    <p><strong><?= e(t('order.reference')) ?>:</strong> <code><?= e($order['reference']) ?></code><br><small class="text-muted"><?= e(t('order.reference_help')) ?></small></p>
<?php endif; ?>

<div id="draw-area" data-order="<?= (int) $order['id'] ?>">
    <?php $drawn = $order['drawn'] ?? []; ?>
    <div<?= $drawn ? '' : ' hidden' ?> id="drawn-box">
        <h2 class="h5"><?= e(t('order.drawn')) ?></h2>
        <div class="balls" id="drawn-balls"><?php foreach ($drawn as $n) { echo ball($n, 'ball-drawn'); } ?></div>
    </div>
    <?php if (!$drawn): ?>
        <button type="button" class="btn btn-accent btn-lg my-3" id="draw-btn"><?= e(t('order.draw')) ?></button>
    <?php endif; ?>
    <p class="h5 my-3" id="draw-result" role="status" aria-live="polite"><?php if ($drawn): $total = Orders::prizeTotal($order); echo e($total > 0 ? t('order.result_won', ['total' => money($total)]) : t('order.result_lost')); endif; ?></p>
</div>

<ul class="list-unstyled">
    <?php foreach ($order['plays'] as $i => $play): ?>
        <li class="card-surface mb-2" data-play="<?= (int) $play['id'] ?>">
            <div class="small text-muted"><?= e(t('cart.play')) ?> <?= $i + 1 ?> · <span class="play-result"><?= $drawn ? e(t('order.matches', ['n' => (int) $play['matches']]) . ' · ' . money((int) $play['prize_cents'])) : '' ?></span></div>
            <div class="balls">
                <?php foreach ($play['numbers'] as $n):
                    $hit = $drawn && in_array($n, $drawn, true); ?>
                    <span class="ball<?= $hit ? ' ball-hit' : '' ?>" data-n="<?= $n ?>"><?= $n ?><?php if ($hit): ?><span class="sr-only"> <?= e(t('order.hit')) ?></span><?php endif; ?></span>
                <?php endforeach; ?>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
<p><a href="/my-plays.php"><?= e(t('nav.mine')) ?></a></p>
