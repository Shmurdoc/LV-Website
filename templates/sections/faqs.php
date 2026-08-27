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
<div class="reveal" style="display:grid;gap:8px;max-width:68ch">
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
<div class="reveal" style="display:grid;gap:12px;margin-top:24px;max-width:68ch" role="list">
<?php foreach ($faqs as $faq): ?>
    <details class="card" style="padding:16px 18px" role="listitem">
        <summary style="cursor:pointer;font-weight:700;color:var(--navy);list-style:none;display:flex;justify-content:space-between;gap:16px">
            <span><?= e($faq['question']) ?></span>
            <span aria-hidden="true" style="color:var(--gold)">+</span>
        </summary>
        <div class="prose" style="margin-top:10px;color:var(--ink-70)"><?= nl2br(e($faq['answer'])) ?></div>
    </details>
<?php endforeach; ?>
</div>
<?php else: ?>
<p class="muted small reveal" role="status">No FAQs yet — add them in admin → FAQs.</p>
<?php endif; ?>
