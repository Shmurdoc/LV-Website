<?php
/**
 * Section: FAQs — Viata Luxe Guesthouse
 * Renders FAQs for the current page (or global if none). Uses get_faqs().
 * Variables: $section, $page (optional)
 */

$pageId = $page['id'] ?? $section['page_id'] ?? null;
$faqs = get_faqs($pageId ? (int)$pageId : null);

// Fallback: if page-specific empty, show global (page_id IS NULL) faqs for generic sections
if (empty($faqs) && $pageId) {
    $faqs = get_faqs(null);
    // Keep only global ones (seed has 2 global with page_id NULL)
    $faqs = array_filter($faqs, fn($f) => $f['page_id'] === null);
}

if (empty($faqs) && empty($section['title'])) return;
?>

<?php if (!empty($section['title'])): ?>
<div class="faqs__head reveal">
    <?php if (!empty($section['subtitle'])): ?>
    <span class="kicker"><?= e($section['subtitle']) ?></span>
    <?php endif; ?>
    <h2 class="section-heading"><?= e($section['title']) ?></h2>
    <?php if (!empty($section['content'])): ?>
    <p class="subhead"><?= e(strip_tags($section['content'])) ?></p>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if (!empty($faqs)): ?>
<div class="faqs__list reveal" role="list">
<?php foreach ($faqs as $faq): ?>
    <details class="faqs__item card" role="listitem">
        <summary class="faqs__summary">
            <span><?= e($faq['question']) ?></span>
            <span class="faqs__toggle" aria-hidden="true">+</span>
        </summary>
        <div class="faqs__answer prose"><?= nl2br(e($faq['answer'])) ?></div>
    </details>
<?php endforeach; ?>
</div>
<?php else: ?>
<p class="muted small reveal" role="status">No FAQs yet — add them in admin → FAQs.</p>
<?php endif; ?>
