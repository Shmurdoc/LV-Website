<?php
/**
 * Section: Testimonials — Viata Luxe Guesthouse
 * Guest review cards — auto-rotating slideshow with navigation dots.
 * Variables: $section
 */

$reviews = get_featured_testimonials();
?>

<?php if (!empty($section['title'])): ?>
<div class="reviews__head reveal">
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
<div class="testi-slideshow reveal" data-auto="5000" role="region" aria-label="Guest testimonials">
    <div class="testi-slideshow__track">
        <?php foreach ($reviews as $i => $review): ?>
        <article class="testi-slide<?= $i === 0 ? ' is-active' : '' ?>" data-index="<?= $i ?>" aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>">
            <div class="testi-slide__inner">
                <div class="testi-slide__stars" aria-label="<?= (int)$review['rating'] ?> out of 5 stars">
                    <?= str_repeat('★', (int)$review['rating']) ?>
                </div>
                <blockquote class="testi-slide__text">
                    <p>"<?= e($review['review_text']) ?>"</p>
                </blockquote>
                <div class="testi-slide__author">
                    <div class="testi-slide__avatar"><?= e(mb_strtoupper(mb_substr($review['reviewer_name'], 0, 2))) ?></div>
                    <div class="testi-slide__meta">
                        <div class="testi-slide__name"><?= e($review['reviewer_name']) ?></div>
                        <div class="testi-slide__role"><?= e($review['source'] ?? 'Guest') ?></div>
                    </div>
                </div>
                <span class="testi-slide__badge"><i data-lucide="badge-check" style="width:12px;height:12px;vertical-align:-0.1em;margin-right:4px"></i> <?= e(setting('testimonials_badge', 'Verified Guest')) ?></span>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

    <div class="testi-slideshow__nav" role="tablist" aria-label="Testimonial navigation">
        <?php foreach ($reviews as $i => $review): ?>
        <button class="testi-nav__dot<?= $i === 0 ? ' is-active' : '' ?>" role="tab" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" aria-label="Testimonial <?= $i + 1 ?> of <?= count($reviews) ?>" data-target="<?= $i ?>"></button>
        <?php endforeach; ?>
    </div>

    <button class="testi-slideshow__prev" aria-label="Previous testimonial"><i data-lucide="chevron-left" style="width:20px;height:20px"></i></button>
    <button class="testi-slideshow__next" aria-label="Next testimonial"><i data-lucide="chevron-right" style="width:20px;height:20px"></i></button>
</div>
<?php endif; ?>
