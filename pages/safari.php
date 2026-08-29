<?php
/**
 * Safari Page — DB-driven, renders HTML directly (home.php pattern)
 * Fetches page hero data from `pages` table, activities from `safari_activities`.
 */
require_once __DIR__ . '/../includes/functions.php';

$db   = Database::get();
$page = get_page('safari');
$nav  = get_navigation();
$settings = settings_group('contact');

// ── Hero data (from pages table) ──
$heroImage   = url($page['hero_image']  ?? '/Luxury Images/hero/safari-hero.webp');
$heroKicker  = $page['hero_kicker']    ?? 'Discover the Bushveld';
$heroTitle   = $page['hero_title']     ?? '<em>Safari</em> Adventures';
$heroLead    = $page['hero_lead']      ?? 'From Big Five game drives in the Kruger to scenic boat safaris on the Olifants River — every day is a new chapter in the wild.';
$metaTitle   = $page['meta_title']     ?? 'Safari | Viata Luxe Guesthouse';
$metaDesc    = $page['meta_description'] ?? 'Explore Limpopo safaris from Phalaborwa — Kruger game drives, Olifants boat cruises, Blyde River Canyon and cultural tours.';

// ── Activities (all published, sorted) ──
$activities = $db->query(
    'SELECT title, content, image, video_urls, link_url, link_text, sort_order
     FROM safari_activities
     WHERE is_published = 1
     ORDER BY sort_order ASC, id ASC'
)->fetchAll();

// ── Group activities by sort_order ranges ──
// 1–2  = Video / Game Drive  |  3–4  = Boat Safaris
// 5–6  = Adventure           |  7+   = Beyond the Gate
$videos      = array_filter($activities, fn($a) => $a['sort_order'] <= 2);
$boatCards   = array_filter($activities, fn($a) => $a['sort_order'] >= 3 && $a['sort_order'] <= 4);
$adventure   = array_filter($activities, fn($a) => $a['sort_order'] >= 5 && $a['sort_order'] <= 6);
$beyondGate  = array_filter($activities, fn($a) => $a['sort_order'] >= 7);

// ── Gallery images (static fallback, can be DB-driven later) ──
$galleryImages = [
    ['src' => url('/Luxury Images/optimized/standard-apartment-luxury-guesthouse-phalaborwa.webp'), 'alt' => 'Kruger landscape'],
    ['src' => url('/Luxury Images/optimized/DSC03609.webp'),                                       'alt' => 'Elephant herd at sunset'],
    ['src' => url('/Luxury Images/optimized/DSC03963.webp'),                                       'alt' => 'Hippos in the Olifants River'],
    ['src' => url('/Luxury Images/optimized/DSC02142.webp'),                                       'alt' => 'Birdlife along the river'],
];

// ── Pricelist download ──
$pricelistUrl = url('/uploads/safari/viata-safari-pricelist.pdf');

require_once __DIR__ . '/../templates/header.php';
?>

<style>
/* ── YouTube facade ── */
.yt-facade{position:relative;width:100%;aspect-ratio:16/9;border-radius:10px;overflow:hidden;background:#000;cursor:pointer;display:block}
.yt-facade img{width:100%;height:100%;object-fit:cover;display:block;filter:brightness(.78);transition:filter .3s}
.yt-facade:hover img{filter:brightness(.55)}
.yt-facade__play{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);pointer-events:none;z-index:2;width:56px;height:56px;background:rgba(0,0,0,.6);border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid rgba(255,255,255,.5);transition:background .2s,transform .2s}
.yt-facade:hover .yt-facade__play{background:rgba(0,0,0,.85);transform:translate(-50%,-50%) scale(1.06)}
.yt-facade__play::after{content:"";display:block;width:0;height:0;border-top:10px solid transparent;border-bottom:10px solid transparent;border-left:16px solid #fff;margin-left:3px}
.yt-facade__cap{position:absolute;bottom:0;left:0;right:0;padding:14px 16px;background:linear-gradient(transparent,rgba(0,0,0,.78));color:#fff;font-family:var(--font-display);font-size:16px;font-weight:300;z-index:2}
@media(max-width:720px){.yt-facade__play{width:44px;height:44px}.yt-facade__play::after{border-top:8px solid transparent;border-bottom:8px solid transparent;border-left:13px solid #fff}}

/* ── Activity cards ── */
.activity-card{background:var(--cream);border:1px solid var(--line);border-radius:14px;overflow:hidden;transition:transform .25s,box-shadow .3s}
.activity-card:hover{transform:translateY(-3px);box-shadow:0 10px 28px rgba(11,26,46,.13)}
.activity-card__img{aspect-ratio:16/10;overflow:hidden}
.activity-card__img img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s}
.activity-card:hover .activity-card__img img{transform:scale(1.03)}
.activity-card__body{padding:22px 24px 26px}
.activity-card__title{font-family:var(--font-display);font-size:clamp(20px,2.2vw,26px);font-weight:300;line-height:1.15;margin-bottom:10px}
.activity-card__text{color:var(--ink-70);font-size:15px;line-height:1.65}
.activity-card__link{display:inline-flex;align-items:center;gap:6px;margin-top:14px;font-weight:600;font-size:14px;letter-spacing:.03em;text-transform:uppercase;color:var(--navy);text-decoration:none;transition:color .2s}
.activity-card__link:hover{color:var(--gold-600)}
.activity-card__link::after{content:"→";transition:transform .2s}
.activity-card__link:hover::after{transform:translateX(3px)}
</style>

<!-- ════════════════════════════════════════════════════
     HERO
     ════════════════════════════════════════════════════ -->
<section class="page-hero" id="top">
    <div class="page-hero__media">
        <img src="<?= e($heroImage) ?>" alt="Safari landscape near Phalaborwa" width="1920" height="800" fetchpriority="high" decoding="async">
    </div>
    <div class="page-hero__veil" aria-hidden="true"></div>
    <div class="page-hero__content">
        <span class="page-hero__kicker"><?= e($heroKicker) ?></span>
        <h1 class="page-hero__title"><?= $heroTitle /* raw HTML — DB-authored */ ?></h1>
        <p class="page-hero__lead"><?= e($heroLead) ?></p>
        <div class="page-hero__meta">
            <a href="#safari-intro" class="cta cta--primary">Explore Experiences</a>
            <a href="<?= e($pricelistUrl) ?>" class="cta cta--outline" download>Download Pricelist</a>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════════
     INTRO + VIDEO FACADES
     ════════════════════════════════════════════════════ -->
<section class="section" id="safari-intro" style="padding-top:96px; padding-bottom:64px">
    <div class="container">
        <p class="section__intro">Every stay at Viata Luxe is a gateway to the wild — just 15 minutes from the Phalaborwa Gate of Kruger National Park. Safari drives at dawn, boat cruises on the Olifants, and canyon vistas that stretch to the horizon.</p>

        <?php if (!empty($videos)): ?>
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:24px;margin-top:42px">
            <?php foreach ($videos as $vid):
                $thumb = url($vid['image'] ?? '/Luxury Images/optimized/standard-apartment-luxury-guesthouse-phalaborwa.webp');
                $videoUrls = json_decode($vid['video_urls'] ?? '[]', true);
                $videoUrl  = $videoUrls[0] ?? '';
                $ytId      = preg_match('/(?:youtu\.be\/|v=)([a-zA-Z0-9_-]+)/', $videoUrl, $m) ? $m[1] : '';
                $ytThumb   = $ytId ? "https://img.youtube.com/vi/{$ytId}/maxresdefault.jpg" : $thumb;
            ?>
            <div class="yt-facade" data-src="https://www.youtube.com/embed/<?= e($ytId) ?>?autoplay=1&rel=0&modestbranding=1&playsinline=1">
                <img src="<?= e($ytThumb) ?>" alt="<?= e($vid['title']) ?>" width="720" height="405" loading="lazy" decoding="async">
                <div class="yt-facade__play" aria-hidden="true"></div>
                <div class="yt-facade__cap"><?= e($vid['title']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ════════════════════════════════════════════════════
     BOAT SAFARIS  /  ADVENTURE CARDS
     ════════════════════════════════════════════════════ -->
<section class="section section--alt">
    <div class="container" style="display:grid;grid-template-columns:repeat(2,1fr);gap:32px">
        <?php foreach ($boatCards as $card):
            $img = url($card['image'] ?? '/Luxury Images/optimized/standard-apartment-luxury-guesthouse-phalaborwa.webp');
        ?>
        <article class="activity-card">
            <div class="activity-card__img">
                <img src="<?= e($img) ?>" alt="<?= e($card['title']) ?>" width="640" height="400" loading="lazy" decoding="async">
            </div>
            <div class="activity-card__body">
                <h3 class="activity-card__title"><?= e($card['title']) ?></h3>
                <p class="activity-card__text"><?= e($card['content']) ?></p>
                <?php if (!empty($card['link_url'])): ?>
                <a href="<?= e($card['link_url']) ?>" class="activity-card__link" target="_blank" rel="noopener">
                    <?= e($card['link_text'] ?: 'Learn More') ?>
                </a>
                <?php endif; ?>
            </div>
        </article>
        <?php endforeach; ?>

        <?php foreach ($adventure as $card):
            $img = url($card['image'] ?? '/Luxury Images/optimized/standard-apartment-luxury-guesthouse-phalaborwa.webp');
        ?>
        <article class="activity-card">
            <div class="activity-card__img">
                <img src="<?= e($img) ?>" alt="<?= e($card['title']) ?>" width="640" height="400" loading="lazy" decoding="async">
            </div>
            <div class="activity-card__body">
                <h3 class="activity-card__title"><?= e($card['title']) ?></h3>
                <p class="activity-card__text"><?= e($card['content']) ?></p>
                <?php if (!empty($card['link_url'])): ?>
                <a href="<?= e($card['link_url']) ?>" class="activity-card__link" target="_blank" rel="noopener">
                    <?= e($card['link_text'] ?: 'Learn More') ?>
                </a>
                <?php endif; ?>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- ════════════════════════════════════════════════════
     BEYOND THE GATE
     ════════════════════════════════════════════════════ -->
<section class="section" style="padding-top:96px; padding-bottom:72px">
    <div class="container">
        <h2 class="section__title" style="max-width:16ch">Beyond the Gate</h2>
        <p class="section__lead" style="max-width:58ch">Phalaborwa sits at the crossroads of the Lowveld — Blyde River Canyon to the south, cultural heritage sites within minutes, and sunset drinks at Amarula on the banks of the Olifants.</p>

        <?php if (!empty($beyondGate)): ?>
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:24px;margin-top:42px">
            <?php foreach ($beyondGate as $card):
                $img = url($card['image'] ?? '/Luxury Images/optimized/standard-apartment-luxury-guesthouse-phalaborwa.webp');
            ?>
            <article class="activity-card">
                <div class="activity-card__img">
                    <img src="<?= e($img) ?>" alt="<?= e($card['title']) ?>" width="640" height="400" loading="lazy" decoding="async">
                </div>
                <div class="activity-card__body">
                    <h3 class="activity-card__title"><?= e($card['title']) ?></h3>
                    <p class="activity-card__text"><?= e($card['content']) ?></p>
                    <?php if (!empty($card['link_url'])): ?>
                    <a href="<?= e($card['link_url']) ?>" class="activity-card__link" target="_blank" rel="noopener">
                        <?= e($card['link_text'] ?: 'Learn More') ?>
                    </a>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ════════════════════════════════════════════════════
     IMAGE GALLERY GRID
     ════════════════════════════════════════════════════ -->
<section class="section section--alt">
    <div class="container">
        <h2 class="section__title" style="max-width:16ch">Moments from the Bush</h2>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-top:36px">
            <?php foreach ($galleryImages as $img): ?>
            <figure class="gallery-item" style="margin:0;overflow:hidden;border-radius:12px;aspect-ratio:4/5">
                <img src="<?= e($img['src']) ?>" alt="<?= e($img['alt']) ?>" width="400" height="500" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:cover;display:block">
            </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════════
     CTA — PRICELIST DOWNLOAD
     ════════════════════════════════════════════════════ -->
<section class="section" style="padding:96px 0">
    <div class="container" style="text-align:center">
        <h2 class="section__title" style="margin-inline:auto">Ready to Explore?</h2>
        <p class="section__lead" style="margin-inline:auto; margin-top:12px; max-width:52ch">Download our full safari pricelist or get in touch to tailor your Limpopo adventure.</p>
        <div style="display:flex;gap:16px;justify-content:center;margin-top:36px;flex-wrap:wrap">
            <a href="<?= e($pricelistUrl) ?>" class="cta cta--primary" download>Download Pricelist</a>
            <a href="<?= e(url('/contact/')) ?>" class="cta cta--outline">Contact Us</a>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════════
     SCRIPTS — YouTube facade lazy-load
     ════════════════════════════════════════════════════ -->
<script>
(function(){
  document.querySelectorAll('.yt-facade').forEach(function(facade){
    facade.addEventListener('click', function(){
      var src = facade.getAttribute('data-src');
      if(!src) return;
      var iframe = document.createElement('iframe');
      iframe.src = src;
      iframe.setAttribute('allow','accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
      iframe.setAttribute('allowfullscreen','');
      iframe.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;border:0';
      facade.replaceWith(iframe);
    });
  });
})();
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
