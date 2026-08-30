<?php
/**
 * Gallery — Viata Luxe Guesthouse
 * DB-driven template following home.php pattern.
 * Bypasses section renderer — renders HTML directly.
 */

$page = get_page('gallery');
if (!$page) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$nav       = get_navigation();
$settings  = settings_group('branding');
$contact   = settings_group('contact');
$categories = get_gallery_categories();

// Fetch all gallery images with category names (real schema: category_id FK, sort_order, deleted_at)
$db = Database::get();
$stmt = $db->query("
    SELECT gi.*, gc.name AS cat_name, gc.slug AS cat_slug
    FROM gallery_images gi
    JOIN gallery_categories gc ON gi.category_id = gc.id
    WHERE gi.deleted_at IS NULL
    ORDER BY gi.category_id ASC, gi.sort_order ASC
");
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total images
$total = count($images);

require __DIR__ . '/../templates/header.php';
?>

<style>
/* ——— Page hero ——— */
.page-hero{position:relative; min-height:52vh; overflow:hidden; background:var(--navy); display:flex; align-items:flex-end}
.page-hero__media{position:absolute; inset:0}
.page-hero__media img{width:100%; height:100%; object-fit:cover; object-position:center 58%; transform:scale(1.06); animation:heroKb 9s var(--ease-in-out) forwards}
@keyframes heroKb{from{transform:scale(1.06)} to{transform:scale(1.14)}}
.page-hero__veil{position:absolute; inset:0; background:linear-gradient(180deg, rgba(11,26,46,0.12) 0%, rgba(11,26,46,0.38) 58%, rgba(11,26,46,0.58) 100%)}
.page-hero__veil::after{content:""; position:absolute; inset:0; background:linear-gradient(90deg, rgba(11,26,46,0.18), transparent 62%)}
.page-hero__content{position:relative; z-index:3; width:min(var(--container), calc(100% - 2*var(--gutter))); margin-inline:auto; padding-block: clamp(72px, 14vh, 160px); display:grid; gap:16px; color:var(--cream)}
.page-hero__kicker{font-size:10px; letter-spacing:0.22em; text-transform:uppercase; font-weight:800; color:var(--gold-300)}
.page-hero__title{font-family:var(--font-display); font-weight:300; font-size:clamp(32px,5vw,56px); line-height:0.95; letter-spacing:-0.02em}
.page-hero__title em{font-style:italic; color:var(--gold-300)}
.page-hero__lead{font-size:15px; line-height:1.6; color:rgba(248,246,241,0.78); max-width:58ch}

/* ——— Filter bar ——— */
.filter{position:sticky; top:68px; z-index:5; background:rgba(248,246,241,0.88); backdrop-filter:blur(10px); border-bottom:1px solid var(--line); padding:12px 0; margin:0 calc(-1*var(--gutter)); padding-inline:var(--gutter)}
.filter__inner{width:min(var(--container), calc(100% - 2*var(--gutter))); margin-inline:auto; display:flex; gap:8px; flex-wrap:wrap; justify-content:center}
.filter button{padding:8px 14px; border-radius:999px; border:1px solid var(--line); background:var(--white); font-size:11px; letter-spacing:0.12em; text-transform:uppercase; font-weight:700; color:var(--ink-70); cursor:pointer; transition:background var(--dur-fast), color var(--dur-fast), border-color var(--dur-fast)}
.filter button:hover{border-color:var(--navy); color:var(--navy)}
.filter button.is-active{background:var(--navy); color:var(--cream); border-color:var(--navy)}

/* ——— Masonry grid ——— */
.masonry{column-count:3; column-gap:14px}
.masonry__item{break-inside:avoid; margin-bottom:14px; border-radius:var(--radius-lg); overflow:hidden; border:1px solid var(--line); background:var(--white); box-shadow:var(--shadow-soft); cursor:pointer; display:inline-block; width:100%; opacity:1; transform:none; contain:none; transition:transform var(--dur) var(--ease-spring), box-shadow var(--dur) var(--ease-out)}
.masonry__item:hover{transform:translateY(-3px); box-shadow:var(--shadow-medium)}
.masonry__item img{width:100%; height:auto; display:block; transition:transform 600ms var(--ease-out)}
.masonry__item:hover img{transform:scale(1.04)}
.masonry__cap{padding:8px 12px; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; font-weight:700; color:var(--ink-55); background:var(--cream); border-top:1px solid var(--line); transition:background var(--dur-fast) var(--ease-out), color var(--dur-fast) var(--ease-out)}
.masonry__item:hover .masonry__cap{background:var(--ivory); color:var(--navy)}
.masonry__item.is-hidden{display:none}

/* ——— Section intro ——— */
.section-intro{text-align:center; margin-bottom:32px}
.section-intro .kicker{margin-bottom:6px}
.section-intro h2{font-family:var(--font-display); font-weight:300; font-size:clamp(28px,4vw,44px); line-height:0.95; letter-spacing:-0.02em; margin-bottom:10px}
.section-intro h2 em{font-style:italic; color:var(--gold-600)}
.section-intro p{color:var(--ink-70); max-width:58ch; margin-inline:auto; font-size:15px; line-height:1.6}

</style>

<main id="main-content">

<!-- HERO — DB-driven page-hero -->
<section class="page-hero">
  <div class="page-hero__media">
    <img src="<?= url($page['hero_image'] ?? '/Luxury Images/bedrooms/bedroom-chevron-pillows-headboard.jpg') ?>" alt="" width="1920" height="1080" fetchpriority="high" decoding="async">
  </div>
  <div class="page-hero__veil"></div>
  <div class="page-hero__content">
    <p class="page-hero__kicker"><?= e($page['hero_kicker'] ?? 'Gallery — Luxe Bedrooms, Kitchens, Bathrooms, Living Rooms, Outdoors') ?></p>
    <h1 class="page-hero__title"><?= $total ?> frames.<br><em>One story.</em></h1>
    <p class="page-hero__lead">Our curated collection of Viata Luxe interiors, kitchens, bathrooms, and outdoor spaces.</p>
  </div>
</section>

<!-- FILTER BAR -->
<div class="filter" id="filter">
  <div class="filter__inner">
    <button class="is-active" data-filter="all">All</button>
    <?php foreach ($categories as $cat): ?>
      <button data-filter="<?= e(strtolower($cat['slug'])) ?>"><?= e($cat['name']) ?></button>
    <?php endforeach; ?>
  </div>
</div>

<!-- MASONRY GRID — DB-driven -->
<section class="container" style="padding-bottom:var(--section-pad); padding-top:18px">
  <div class="masonry" id="masonry">
    <?php foreach ($images as $img): ?>
      <div class="masonry__item" data-cat="<?= e($img['cat_slug']) ?>" data-lightbox href="<?= url($img['image_path']) ?>">
        <img src="<?= url($img['image_path']) ?>" alt="<?= e($img['alt_text'] ?? $img['caption'] ?? $img['cat_name']) ?>" width="800" height="600" loading="lazy" decoding="async">
        <div class="masonry__cap"><?= e($img['cat_name']) ?> — <?= e($img['caption'] ?? $img['alt_text'] ?? '') ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

</main>

<script>
// Gallery filter — mirrors static gallery/index.html behaviour
(function(){
  var btns  = [].slice.call(document.querySelectorAll('.filter button'));
  var items = [].slice.call(document.querySelectorAll('.masonry__item'));

  btns.forEach(function(b){
    b.addEventListener('click', function(){
      btns.forEach(function(x){ x.classList.remove('is-active'); });
      b.classList.add('is-active');
      var f = b.getAttribute('data-filter');
      items.forEach(function(it){
        var c = it.getAttribute('data-cat');
        it.classList.toggle('is-hidden', !(f === 'all' || c === f));
      });
      if(window.ScrollTrigger) ScrollTrigger.refresh();
    });
  });
})();
</script>

<?php
require __DIR__ . '/../templates/footer.php';
