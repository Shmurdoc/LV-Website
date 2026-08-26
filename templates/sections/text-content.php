<?php
/**
 * Section: Text Content — Viata Luxe Guesthouse
 * Generic title + text block section.
 * Variables: $section
 */
?>

<?php if (!empty($section['title'])): ?>
<h2 class="section-heading reveal"><?= $section['title'] ?></h2>
<?php endif; ?>

<?php if (!empty($section['subtitle'])): ?>
<p class="subhead reveal"><?= e($section['subtitle']) ?></p>
<?php endif; ?>

<?php if (!empty($section['content'])): ?>
<div class="section-content prose reveal"><?= $section['content'] ?></div>
<?php endif; ?>
