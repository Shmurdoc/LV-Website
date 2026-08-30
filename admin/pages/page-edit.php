<?php
// Page Edit — Viata Luxe Guesthouse
$db = Database::get();
$page = null;
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $db->prepare('SELECT * FROM pages WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $page = $stmt->fetch() ?: null;
}
$isNew = $page === null;
$p = $page ?? [];
?>
<div class="admin-page">
  <div class="page-header page-header-inline">
    <div><h2><?= $isNew ? 'New Page' : 'Edit Page' ?></h2><p class="muted small"><?= $isNew ? 'Create a new page' : 'Editing "' . e($p['title'] ?? '') . '"' ?></p></div>
    <a href="/admin/pages" class="btn btn-outline">&larr; Back</a>
  </div>
  <div class="form-card"><div class="form-card__body form-card__body--narrow">
    <form method="POST" action="/admin/api/crud.php" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="page">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="id" value="<?= (int)($p['id'] ?? 0) ?>">

      <div class="form-group">
        <label>Title *</label>
        <input type="text" name="title" value="<?= e($p['title'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>Slug *</label>
        <input type="text" name="slug" value="<?= e($p['slug'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>Subtitle</label>
        <input type="text" name="subtitle" value="<?= e($p['subtitle'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Template</label>
        <select name="template">
          <?php foreach (['default','homepage','about','apartments','contact','gallery','safari','faq'] as $t): ?>
            <option value="<?= e($t) ?>" <?= ($p['template'] ?? 'default') === $t ? 'selected' : '' ?>><?= e(ucfirst($t)) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Meta Title</label>
        <input type="text" name="meta_title" value="<?= e($p['meta_title'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Meta Description</label>
        <textarea name="meta_description"><?= e($p['meta_description'] ?? '') ?></textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Sort Order</label>
          <input type="number" name="sort_order" value="<?= (int)($p['sort_order'] ?? 0) ?>">
        </div>
        <div class="form-group form-row--gap form-row--bottom">
          <label class="checkbox-label"><input type="checkbox" name="is_published" value="1" <?= isset($p['is_published']) && $p['is_published'] ? 'checked' : '' ?>> Published</label>
          <label class="checkbox-label"><input type="checkbox" name="is_homepage" value="1" <?= isset($p['is_homepage']) && $p['is_homepage'] ? 'checked' : '' ?>> Homepage</label>
        </div>
      </div>

      <fieldset style="border:1px solid var(--border,#e2e0dc);border-radius:8px;padding:16px;margin-top:8px">
        <legend style="font-weight:600;font-size:13px;padding:0 6px;color:var(--text-secondary,#666)">Hero Section</legend>
        <div class="form-group">
          <label>Hero Image Path</label>
          <div class="flex gap-2">
            <input type="text" name="hero_image" value="<?= e($p['hero_image'] ?? '') ?>" placeholder="/Luxury Images/home-hero/hero.jpg" class="grow">
            <button type="button" class="btn btn-sm btn-outline browse-btn" data-target="hero_image">Browse</button>
          </div>
          <small style="color:var(--text-muted,#999)">Path from site root, e.g. /Luxury Images/pool/pool.jpg</small>
        </div>
        <div class="form-group">
          <label>Hero Kicker</label>
          <input type="text" name="hero_kicker" value="<?= e($p['hero_kicker'] ?? '') ?>" placeholder="Accommodation — 4 Apartments">
        </div>
        <div class="form-group">
          <label>Hero Text Align</label>
          <select name="hero_align">
            <option value="center" <?= ($p['hero_align'] ?? 'center') === 'center' ? 'selected' : '' ?>>Center</option>
            <option value="left" <?= ($p['hero_align'] ?? '') === 'left' ? 'selected' : '' ?>>Left</option>
          </select>
        </div>
      </fieldset>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save Page</button>
        <a href="/admin/pages" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div></div>

  <?php if (!$isNew): ?>
  <?php $seo = $db->prepare('SELECT * FROM page_seo WHERE page_id = :id'); $seo->execute(['id' => $p['id']]); $seo = $seo->fetch() ?: ['schema_type'=>'WebPage','schema_json'=>'','additional_meta'=>'']; ?>
  <div class="form-card" style="margin-top:20px"><div class="form-card__body form-card__body--narrow">
    <h3 class="section-heading--sm">SEO — <?= e($p['title']) ?></h3>
    <form method="POST" action="/admin/api/crud.php" data-ajax>
      <?= csrf_field() ?>
      <input type="hidden" name="entity" value="page_seo">
      <input type="hidden" name="action" value="save">
      <input type="hidden" name="page_id" value="<?= (int)$p['id'] ?>">
      <div class="form-group">
        <label>Schema type</label>
        <select name="schema_type">
          <?php foreach (['WebPage','LodgingBusiness','ContactPage','AboutPage','CollectionPage'] as $st): ?>
            <option value="<?= e($st) ?>" <?= ($seo['schema_type'] ?? 'WebPage') === $st ? 'selected' : '' ?>><?= e($st) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Schema JSON <span class="muted small">— valid JSON-LD, e.g. {"@context":"https://schema.org", ...}</span></label>
        <textarea name="schema_json" rows="6" style="font-family:monospace;font-size:12px"><?= e($seo['schema_json'] ?? '') ?></textarea>
      </div>
      <div class="form-group">
        <label>Additional meta <span class="muted small">— JSON object for extra meta tags</span></label>
        <textarea name="additional_meta" rows="3" style="font-family:monospace;font-size:12px"><?= e($seo['additional_meta'] ?? '') ?></textarea>
      </div>
      <div class="form-actions"><button type="submit" class="btn btn-sm btn-primary">Save SEO</button></div>
    </form>
  </div></div>
  <?php endif; ?>
</div>