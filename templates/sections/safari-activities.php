<?php
/**
 * Section: Safari Activities — Viata Luxe Guesthouse
 * Renders the full safari_activities list from the DB (4 rows: Kedibone
 * Safari, Individual Safaris, Boat Safaris, Adventure) with YouTube
 * facades + content.
 * Variables: $section
 */

$activities = get_safari_activities();
?>

<div class="container">
    <?php if (!empty($section['title'])): ?>
    <div class="reveal">
        <div class="kicker"><?= e($section['subtitle'] ?? 'Safari & Activities') ?></div>
        <h2 class="section-heading"><?= e($section['title']) ?></h2>
        <?php if (!empty($section['content'])): ?>
        <p class="subhead"><?= e($section['content']) ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (empty($activities)): ?>
    <p class="muted small" role="status">No safari activities yet — add them in admin.</p>
    <?php else: ?>
    <div class="activity-grid">
    <?php foreach ($activities as $i => $activity): ?>
        <?php
            $vids = json_decode($activity['video_urls'] ?? '[]', true);
            $vids = is_array($vids) ? array_values(array_filter($vids)) : [];
            $img = $activity['image'] ?? null;
        ?>
        <article class="activity-card reveal">
            <div>
                <div class="kicker">Safari &#183; <?= sprintf('%02d', $i + 1) ?></div>
                <h3 class="section-heading mt-6"><?= e($activity['title']) ?></h3>
                <p class="subhead mt-8"><?= e($activity['content']) ?></p>
                <?php if (!empty($activity['link_url'])): ?>
                <div class="mt-12">
                    <a class="btn btn--navy" href="<?= e($activity['link_url']) ?>" <?= !empty($vids) ? '' : 'target="_blank" rel="noopener"' ?>><?= e($activity['link_text'] ?? 'Explore') ?></a>
                </div>
                <?php endif; ?>
            </div>
            <div>
                <?php if ($img): ?>
                <div class="activity-media">
                    <img src="<?= e(image_url($img)) ?>" alt="<?= e($activity['title']) ?>" loading="lazy" decoding="async">
                    <?php if (!empty($vids)): ?>
                    <a href="<?= e($vids[0]) ?>" class="activity-overlay" target="_blank" rel="noopener" aria-label="Watch <?= e($activity['title']) ?> video">
                        <span class="activity-play">&#9658;</span>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </article>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($section['link_url'])): ?>
    <div class="text-center mt-26 reveal">
        <a class="btn btn--gold" href="<?= e($section['link_url']) ?>" target="_blank" rel="noopener"><?= e($section['link_text'] ?? 'Book Safari') ?></a>
    </div>
    <?php endif; ?>
</div>