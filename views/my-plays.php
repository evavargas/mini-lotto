<h1 class="h3"><?= e(t('mine.title')) ?></h1>
<?php if (!$orders): ?>
    <p class="text-muted"><?= e(t('mine.empty')) ?></p>
<?php endif; ?>
<?php foreach ($orders as $order): $drawn = $order['drawn']; ?>
    <section class="card-surface mb-3" aria-labelledby="o<?= (int) $order['id'] ?>">
        <h2 class="h6" id="o<?= (int) $order['id'] ?>">
            <?= e(t('order.title', ['id' => $order['id']])) ?> · <?= e(substr($order['created_at'], 0, 10)) ?> · <?= e(money((int) $order['total_cents'])) ?>
        </h2>
        <p class="small text-muted mb-2">
            <?= $drawn ? e(t('order.prize_total', ['total' => money(Orders::prizeTotal($order))])) : e(t('mine.pending')) ?>
            · <a href="/order.php?id=<?= (int) $order['id'] ?>"><?= e(t('mine.view')) ?></a>
        </p>
        <?php foreach ($order['plays'] as $play): ?>
            <div class="balls mb-2">
                <?php foreach ($play['numbers'] as $n):
                    $hit = $drawn && in_array($n, $drawn, true); ?>
                    <span class="ball<?= $hit ? ' ball-hit' : '' ?>"><?= $n ?><?php if ($hit): ?><span class="sr-only"> <?= e(t('order.hit')) ?></span><?php endif; ?></span>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </section>
<?php endforeach; ?>
