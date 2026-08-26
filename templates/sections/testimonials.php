<?php
/**
 * Section: Testimonials — Viata Luxe Guesthouse
 * Guest review cards.
 * Variables: $section
 */

$reviews = get_featured_testimonials();
?>

<?php if (!empty($section['title'])): ?>
<div class="reviews__head">
    <span class="kicker"><?= e($section['subtitle'] ?? 'Guest Voices') ?></span>
    <h2 class="section-heading"><?= e($section['title']) ?></h2>
    <?php if (!empty($section['content'])): ?>
    <p class="subhead"><?= e(strip_tags($section['content'])) ?></p>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if (empty($reviews)): ?>
<p class="muted small" role="status">No testimonials yet — feature reviews in admin to display here.</p>
<?php else: ?>
<div class="reviews__grid" style="margin-top:28px">
<?php foreach ($reviews as $i => $review): ?>
    <article class="review reveal reveal--delay-<?= min($i, 3) ?>">
        <div class="review__stars" aria-label="<?= (int)$review['rating'] ?> out of 5 stars">
            <?= str_repeat('★', (int)$review['rating']) ?>
        </div>
        <p class="review__text">"<?= e($review['review_text']) ?>"</p>
        <div class="review__author">
            <div class="review__avatar"><?= e(mb_strtoupper(mb_substr($review['reviewer_name'], 0, 2))) ?></div>
            <div>
                <div class="review__name"><?= e($review['reviewer_name']) ?></div>
                <div class="review__role"><?= e($review['source'] ?? '') ?></div>
            </div>
        </div>
        <span class="review__badge">Verified Guest</span>
    </article>
<?php endforeach; ?>
</div>
<?php endif; ?>
