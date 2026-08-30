<?php
/**
 * Safari Page — matches reference at D:\newkit\final website\safari\index.html
 * DB-driven hero + activities, structured layout matching static reference.
 */
require_once __DIR__ . '/../includes/functions.php';

$db   = Database::get();
$page = get_page('safari');
$nav  = get_navigation();

// ── Hero data (from pages table) ──
$heroImage   = url($page['hero_image']  ?? '/Luxury Images/gallery-scenic/exterior-grey-cottages-red-doors.jpg');
$heroKicker  = $page['hero_kicker']    ?? 'Safari — Kedibone Safari Tours and Activities';
$heroTitle   = $page['hero_title']     ?? 'Kedibone <em>Safari.</em>';
$heroLead    = $page['hero_lead']      ?? 'At Viata Luxe Guesthouse, we proudly collaborate with <strong>Kedibone Safari</strong> to offer thrilling wildlife and adventure.';
$metaTitle   = $page['meta_title']     ?? 'Safari | Viata Luxe Guesthouse';
$metaDesc    = $page['meta_description'] ?? 'Explore Limpopo safaris from Phalaborwa.';

// ── Activities (from safari_activities table) ──
$activities = [];
try {
    $activities = $db->query(
        'SELECT title, content, image, video_urls, sort_order
         FROM safari_activities
         WHERE is_published = 1
         ORDER BY sort_order ASC, id ASC'
    )->fetchAll();
} catch (Throwable $e) {
    // Table missing — use empty array
}

// ── Extract video IDs from activities ──
$videoIds = [];
foreach ($activities as $act) {
    $urls = json_decode($act['video_urls'] ?? '[]', true);
    $url  = $urls[0] ?? '';
    if (preg_match('/(?:youtu\.be\/|v=|\/embed\/)([a-zA-Z0-9_-]+)/', $url, $m)) {
        $videoIds[] = [
            'id'    => $m[1],
            'image' => $act['image'],
            'title' => $act['title'],
        ];
    }
}

// ── Gallery images (reference-specific outdoor images) ──
$galleryImages = [
    ['src' => url('/Luxury Images/activities/zebra-golden-hour-closeup.jpg'),      'alt' => 'Zebra golden hour'],
    ['src' => url('/Luxury Images/activities/elephants-river-crossing-herd.jpg'),  'alt' => 'Elephants crossing'],
    ['src' => url('/Luxury Images/activities/bourkes-luck-potholes-bridge.jpg'),   'alt' => "Bourke's Luck potholes bridge"],
    ['src' => url('/Luxury Images/activities/river-landscape-panoramic.jpg'),      'alt' => 'River landscape panoramic'],
];

// ── Beyond the Gate cards (from DB settings) ──
$beyondCards = json_decode(setting('safari_beyond_cards', '[]'), true);

// ── Pricelist download ──
$pricelistUrl = url('/uploads/safari/viata-safari-pricelist.pdf');

require_once __DIR__ . '/../templates/header.php';
?>

<style>
/* ── YouTube facade (matches reference) ── */
.yt-facade{position:relative; aspect-ratio:16/9; background:var(--navy); border-radius:var(--radius-lg,10px); overflow:hidden; border:1px solid rgba(11,26,46,0.12); cursor:pointer}
.yt-facade img{position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:0.92}
.yt-facade__play{width:64px; height:64px; border-radius:999px; background:rgba(248,246,241,0.92); border:1px solid var(--line); display:grid; place-items:center; color:var(--navy); font-size:20px; z-index:2; backdrop-filter:blur(8px)}
.yt-facade:hover .yt-facade__play{background:rgba(248,246,241,1); transform:scale(1.05)}
.yt-facade__label{position:absolute; left:16px; bottom:16px; z-index:2; background:rgba(11,26,46,0.72); backdrop-filter:blur(8px); color:var(--cream); padding:6px 10px; border-radius:999px; font-size:11px; letter-spacing:0.12em; text-transform:uppercase; font-weight:700}
.yt-facade__overlay{position:absolute; inset:0; background:linear-gradient(180deg, transparent 40%, rgba(11,26,46,0.42))}
.yt-facade:hover .yt-facade__overlay{background:linear-gradient(180deg, transparent 20%, rgba(11,26,46,0.55))}

/* ── Beyond the Gate cards ── */
.beyond-grid{display:grid; grid-template-columns:repeat(2,1fr); gap:14px}
.beyond-card{border:1px solid var(--line); border-radius:var(--radius-lg,10px); overflow:hidden; background:var(--white); display:grid; grid-template-columns:1fr 1fr}
.beyond-card__media{aspect-ratio:4/3; overflow:hidden; background:var(--ivory)}
.beyond-card__media img{width:100%; height:100%; object-fit:cover}
.beyond-card__body{padding:16px; display:grid; gap:8px; align-content:center}
.beyond-card .micro{font-size:11px; letter-spacing:0.12em; text-transform:uppercase; font-weight:700; color:var(--gold-600)}
.beyond-card h3{font-family:var(--font-display); font-weight:300; font-size:18px; margin:0}
.beyond-card p{font-size:13px; color:var(--ink-55); margin:0}
@media(max-width:760px){.beyond-grid{grid-template-columns:1fr}}
@media(max-width:640px){.beyond-card{grid-template-columns:1fr}}
</style>

<!-- ════════════════════════════════════════════════════
     HERO — page-hero with background image
     ════════════════════════════════════════════════════ -->
<section class="page-hero">
  <div class="page-hero__media">
    <img src="<?= e($heroImage) ?>" alt="" width="1920" height="800" fetchpriority="high" decoding="async">
  </div>
  <div class="page-hero__veil"></div>
  <div class="page-hero__content">
    <p class="page-hero__kicker reveal"><?= e($heroKicker) ?></p>
    <h1 class="page-hero__title reveal"><?= $heroTitle /* raw HTML */ ?></h1>
    <p class="page-hero__lead reveal"><?= $heroLead /* raw HTML */ ?></p>
    <div class="page-hero__meta reveal">
      <a class="btn btn--gold" href="<?= e($pricelistUrl) ?>" download>Download Pricelist — PDF</a>
      <span class="chip">Phalaborwa Gate</span>
      <span class="chip">Boat · Canyon · Amarula</span>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════
     YOUTUBE FACADES — 2×2 video grid
     ════════════════════════════════════════════════════ -->
<section class="reveal" style="display:grid; gap:14px; margin-top:28px">
  <div class="kicker">Video — Safari Highlights</div>
  <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px">
    <?php foreach ($videoIds as $vid): ?>
    <div class="yt-facade" data-yt="<?= e($vid['id']) ?>" tabindex="0" role="button" aria-label="Play YouTube video">
      <img src="<?= e(url($vid['image'])) ?>" alt="<?= e($vid['title']) ?> — click to play" decoding="async">
      <div class="yt-facade__overlay"></div>
      <div class="yt-facade__play">&#9654;</div>
      <div class="yt-facade__label"><?= e($vid['title']) ?></div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ════════════════════════════════════════════════════
     BRAND MARK
     ════════════════════════════════════════════════════ -->
<div class="brand-mark reveal" aria-hidden="true">
  <img src="<?= e(url('/Luxury Images/logos/logo-viata-full-dark.png')) ?>" alt="">
</div>

<!-- ════════════════════════════════════════════════════
     BOAT SAFARIS  /  ADVENTURE
     ════════════════════════════════════════════════════ -->
<section class="reveal" style="margin-top:28px; display:grid; grid-template-columns:1fr 1fr; gap:18px">
  <div class="card card__pad">
    <h2 style="font-family:var(--font-display); font-weight:300; font-size:26px">Boat Safaris</h2>
    <p style="color:var(--ink-70); margin-top:8px; font-size:15px">The nearby <strong>Olifants River</strong> offers scenic boat safaris — hippos, crocodiles, diverse birdlife. Dive into history at <strong>Foskor Mine Museum</strong> and <strong>Masorini Archaeological Site</strong> within Kruger — BaPhalaborwa Iron Age smelting remnants.</p>
    <img src="<?= e(url('/Luxury Images/activities/hippos-water-group.jpg')) ?>" alt="Hippos water group — Olifants boat safari" style="margin-top:14px; border-radius:10px; aspect-ratio:16/10; object-fit:cover; width:100%" loading="lazy" decoding="async">
  </div>
  <div class="card card__pad">
    <h2 style="font-family:var(--font-display); font-weight:300; font-size:26px">Adventure</h2>
    <p style="color:var(--ink-70); margin-top:8px; font-size:15px">Iconic <strong>Blyde River Canyon</strong> — one of largest canyons, hiking + boat trips. <strong>Amarula Lapa</strong> — learn how Amarula liqueur is made, tasting session.</p>
    <img src="<?= e(url('/Luxury Images/activities/blyde-river-canyon-panorama.jpg')) ?>" alt="Blyde River Canyon panorama" style="margin-top:14px; border-radius:10px; aspect-ratio:16/10; object-fit:cover; width:100%" loading="lazy" decoding="async">
  </div>
</section>

<!-- ════════════════════════════════════════════════════
     BEYOND THE GATE — 4 cards
     ════════════════════════════════════════════════════ -->
<?php if (!empty($beyondCards)): ?>
<section class="reveal" style="margin-top:18px">
  <div class="kicker">Beyond Gate — measured distances</div>
  <div class="beyond-grid" style="margin-top:12px">
    <?php foreach ($beyondCards as $card): ?>
    <div class="beyond-card">
      <div class="beyond-card__media">
        <img src="<?= e(url($card['image'])) ?>" alt="<?= e($card['micro']) ?>" loading="lazy" decoding="async">
      </div>
      <div class="beyond-card__body">
        <div class="micro"><?= e($card['micro']) ?></div>
        <h3 style="font-family:var(--font-display); font-weight:300; font-size:18px"><?= e($card['title']) ?></h3>
        <p style="font-size:13px; color:var(--ink-55)"><?= e($card['text']) ?></p>
        <span class="chip" style="align-self:start"><?= e($card['chip']) ?></span>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<!-- ════════════════════════════════════════════════════
     IMAGE GALLERY — 4-column grid
     ════════════════════════════════════════════════════ -->
<section class="reveal" style="margin-top:18px; display:grid; grid-template-columns:repeat(4,1fr); gap:10px">
  <?php foreach ($galleryImages as $img): ?>
  <img src="<?= e($img['src']) ?>" alt="<?= e($img['alt']) ?>" style="aspect-ratio:4/3; object-fit:cover; border-radius:10px; border:1px solid var(--line)" loading="lazy" decoding="async">
  <?php endforeach; ?>
</section>

<!-- ════════════════════════════════════════════════════
     CTA — DOWNLOAD PRICELIST
     ════════════════════════════════════════════════════ -->
<div class="reveal" style="margin-top:18px; text-align:center">
  <a class="btn btn--navy" href="<?= e($pricelistUrl) ?>" download>Download Pricelist — Kedibone 2025 PDF</a>
</div>

<!-- ════════════════════════════════════════════════════
     SCRIPTS — YouTube facade lazy-load (matches reference)
     ════════════════════════════════════════════════════ -->
<script>
document.querySelectorAll('.yt-facade').forEach(function(f){
  function load(){
    if(f.querySelector('iframe')) return;
    var id=f.getAttribute('data-yt');
    if(!id) return;
    var iframe=document.createElement('iframe');
    iframe.src='https://www.youtube-nocookie.com/embed/'+id+'?autoplay=1&rel=0&modestbranding=1&playsinline=1';
    iframe.title='YouTube '+id;
    iframe.allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
    iframe.allowFullscreen=true;
    iframe.style.cssText='position:absolute; inset:0; width:100%; height:100%; border:0';
    f.appendChild(iframe);
    f.querySelectorAll('img, .yt-facade__play, .yt-facade__label, .yt-facade__overlay').forEach(function(el){ el.style.opacity='0'; el.style.pointerEvents='none'; });
  }
  f.addEventListener('click', load);
  f.addEventListener('keydown', function(e){ if(e.key==='Enter'||e.key===' '){ e.preventDefault(); load(); }});
});
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
