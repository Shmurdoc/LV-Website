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
    <div style="display:grid;gap:26px;margin-top:26px">
    <?php foreach ($activities as $i => $activity): ?>
        <?php
            $vids = json_decode($activity['video_urls'] ?? '[]', true);
            $vids = is_array($vids) ? array_values(array_filter($vids)) : [];
            $img = $activity['image'] ?? null;
        ?>
        <article class="reveal" style="display:grid;grid-template-columns:1.1fr 0.9fr;gap:28px;align-items:center<?= $i % 2 === 1 ? '' : '' ?>">
            <div>
                <div class="kicker">Safari &#183; <?= sprintf('%02d', $i + 1) ?></div>
                <h3 class="section-heading" style="margin-top:6px"><?= e($activity['title']) ?></h3>
                <p class="subhead" style="margin-top:8px"><?= e($activity['content']) ?></p>
                <?php if (!empty($activity['link_url'])): ?>
                <div style="margin-top:12px">
                    <a class="btn btn--navy" href="<?= e($activity['link_url']) ?>" <?= !empty($vids) ? '' : 'target="_blank" rel="noopener"' ?>><?= e($activity['link_text'] ?? 'Explore') ?></a>
                </div>
                <?php endif; ?>
            </div>
            <div>
                <?php if ($img): ?>
                <div class="safari-tease__media" style="position:relative;border-radius:var(--radius-lg);overflow:hidden;height:300px">
                    <img src="<?= e(image_url($img)) ?>" alt="<?= e($activity['title']) ?>" style="width:100%;height:100%;object-fit:cover" loading="lazy" decoding="async">
                    <?php if (!empty($vids)): ?>
                    <a href="<?= e($vids[0]) ?>" class="safari-tease__veil" target="_blank" rel="noopener" aria-label="Watch <?= e($activity['title']) ?> video" style="position:absolute;inset:0;display:grid;place-items:center;background:rgba(11,26,46,0.30)">
                        <span style="width:56px;height:56px;border-radius:999px;background:rgba(248,246,241,0.92);display:grid;place-items:center;font-size:20px;color:var(--navy)">&#9658;</span>
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
    <div style="text-align:center;margin-top:26px" class="reveal">
        <a class="btn btn--gold" href="<?= e($section['link_url']) ?>" target="_blank" rel="noopener"><?= e($section['link_text'] ?? 'Book Safari') ?></a>
    </div>
    <?php endif; ?>
</div>