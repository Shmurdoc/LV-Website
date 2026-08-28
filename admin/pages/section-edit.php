<?php
// Section Edit — Viata Luxe Guesthouse
$db = Database::get();
$pages = $db->query('SELECT id, title FROM pages ORDER BY sort_order ASC, title ASC')->fetchAll();
$section = null;
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $db->prepare('
        SELECT s.*, so.layout, so.background_color, so.text_color, so.alignment, so.animation
        FROM sections s
        LEFT JOIN section_orientation so ON so.section_id = s.id
        WHERE s.id = :id
    ');
    $stmt->execute(['id' => $id]);
    $section = $stmt->fetch() ?: null;
}
$isNew = $section === null;
$s = $section ?? [];
$sectionTypes = ['hero','about','services','features','text-left','text-right','text-top','image-top','text-only','image-only','full-width','centered','grid'];
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $isNew ? 'New Section' : 'Edit Section' ?></h2><p class="muted small"><?= $isNew ? 'Create a new section' : 'Editing "' . e($s['title'] ?? '') . '"' ?></p></div>
    <a href="/admin/sections" class="btn btn-outline">&larr; Back</a>
  </div>
  <div class="form-card"><div class="form-card__body form-card__body--narrow">
    <form method="POST" action="/admin/api/crud.php" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="section">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)($s['id'] ?? 0) ?>">

      <div class="form-row">
        <div class="form-group">
          <label>Section Type *</label>
          <select name="section_type" required>
            <?php foreach ($sectionTypes as $t): ?>
              <option value="<?= e($t) ?>" <?= ($s['section_type'] ?? '') === $t ? 'selected' : '' ?>><?= e(ucfirst($t)) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Page *</label>
          <select name="page_id" required>
            <option value="">— Select page —</option>
            <?php foreach ($pages as $pg): ?>
              <option value="<?= $pg['id'] ?>" <?= (int)($s['page_id'] ?? 0) === (int)$pg['id'] ? 'selected' : '' ?>><?= e($pg['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" value="<?= e($s['title'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Subtitle</label>
        <input type="text" name="subtitle" value="<?= e($s['subtitle'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Content</label>
        <textarea name="content"><?= e($s['content'] ?? '') ?></textarea>
      </div>
      <div class="form-group">
        <label>Image</label>
        <input type="text" name="image" value="<?= e($s['image'] ?? '') ?>">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Link URL</label>
          <input type="text" name="link_url" value="<?= e($s['link_url'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Link Text</label>
          <input type="text" name="link_text" value="<?= e($s['link_text'] ?? '') ?>">
        </div>
      </div>

      <h3 class="section-heading--sm">Orientation</h3>
      <div class="form-row">
        <div class="form-group">
          <label>Layout</label>
          <select name="layout">
            <?php foreach (['text-left','text-right','text-top','image-top','text-only','image-only','full-width','centered','grid-2','grid-3','grid-4'] as $l): ?>
              <option value="<?= e($l) ?>" <?= ($s['layout'] ?? 'text-left') === $l ? 'selected' : '' ?>><?= e($l) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Alignment</label>
          <select name="alignment">
            <?php foreach (['left','center','right'] as $a): ?>
              <option value="<?= e($a) ?>" <?= ($s['alignment'] ?? 'left') === $a ? 'selected' : '' ?>><?= e(ucfirst($a)) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Background Color</label>
          <input type="text" name="background_color" value="<?= e($s['background_color'] ?? '') ?>" placeholder="#ffffff">
        </div>
        <div class="form-group">
          <label>Text Color</label>
          <input type="text" name="text_color" value="<?= e($s['text_color'] ?? '') ?>" placeholder="#1a1a2e">
        </div>
      </div>
      <div class="form-group">
        <label>Animation</label>
        <select name="animation">
          <?php foreach (['fade-up','fade-in','fade-left','fade-right','zoom-in','none'] as $an): ?>
            <option value="<?= e($an) ?>" <?= ($s['animation'] ?? 'fade-up') === $an ? 'selected' : '' ?>><?= e($an) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>CSS Class</label>
        <input type="text" name="css_class" value="<?= e($s['css_class'] ?? '') ?>">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Sort Order</label>
          <input type="number" name="sort_order" value="<?= (int)($s['sort_order'] ?? 0) ?>">
        </div>
        <div class="form-group form-row--bottom">
          <label class="checkbox-label"><input type="checkbox" name="is_visible" value="1" <?= !$isNew && !isset($s['is_visible']) || (isset($s['is_visible']) && $s['is_visible']) ? 'checked' : '' ?>> Visible</label>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Section</button>
        <a href="/admin/sections" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div></div>
</div>