<section class="hero text-center mb-5" aria-labelledby="hero-title">
    <p class="eyebrow mb-1"><?= e(t('home.next')) ?></p>
    <h1 id="hero-title" class="display-4 jackpot"><span class="sr-only"><?= e(t('home.jackpot')) ?>: </span><?= e(money($jackpot)) ?></h1>
    <p class="lead"><?= e(t('home.jackpot')) ?></p>
    <time class="countdown" id="countdown" datetime="<?= e($next->format('c')) ?>" data-target="<?= e($next->format('c')) ?>">
        <?= e($next->setTimezone(new DateTimeZone('America/Montevideo'))->format('Y-m-d H:i')) ?> (UTC-3)
    </time>
    <p class="mt-4"><a class="btn btn-accent btn-lg" href="/play.php"><?= e(t('home.cta')) ?></a></p>
</section>

<div class="row">
    <section class="col-md-6 mb-4" aria-labelledby="recent-title">
        <h2 id="recent-title" class="h4"><?= e(t('home.recent')) ?></h2>
        <ul class="list-unstyled">
            <?php foreach ($recent as $draw): ?>
                <li class="card-surface mb-2">
                    <div class="small text-muted"><?= e(substr($draw['drawn_at'], 0, 10)) ?> · <?= e(money((int) $draw['jackpot_cents'])) ?></div>
                    <div class="balls"><?php foreach ($draw['numbers'] as $n) { echo ball($n); } ?></div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="col-md-6 mb-4" aria-labelledby="prizes-title">
        <h2 id="prizes-title" class="h4"><?= e(t('home.prizes')) ?></h2>
        <table class="table table-themed">
            <thead><tr><th scope="col"><?= e(t('home.matches')) ?></th><th scope="col"><?= e(t('home.prize')) ?></th></tr></thead>
            <tbody>
                <?php foreach (Prizes::FIXED as $matches => $cents): ?>
                    <tr><th scope="row"><?= $matches ?></th><td><?= e(money($cents)) ?></td></tr>
                <?php endforeach; ?>
                <tr><th scope="row"><?= Lotto::PICK ?></th><td><?= e(t('home.jackpot_row')) ?></td></tr>
            </tbody>
        </table>
    </section>
</div>
