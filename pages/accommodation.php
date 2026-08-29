<?php
/**
 * Accommodation Overview — Viata Luxe Guesthouse
 * Full DB-driven template. Bypasses section renderer for design fidelity.
 */

require_once __DIR__ . '/../includes/functions.php';

$page = get_page('accommodation');
if (!$page) {
    $page = get_page('accomodation');
}
if (!$page) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$nav      = get_navigation();
$settings = settings_group('branding');
$contact  = settings_group('contact');
$apartments = get_apartments();

$meta_title       = $page['meta_title'] ?? 'Accommodation — Viata Luxe Guesthouse';
$meta_description = $page['meta_description'] ?? '4 curated apartments: Classic 1, Classic 2, Comfort 3, Deluxe 4. From R950/night, self-catering, city views.';
$og_image         = $page['og_image'] ?? '/Luxury Images/apartments-classic-4/apt4-bedroom-main-view.jpg';
$canonical        = url('/accomodation/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0B1A2E">
    <meta name="color-scheme" content="light">
    <title><?= e($meta_title) ?></title>
    <meta name="description" content="<?= e($meta_description) ?>">
    <meta property="og:title" content="Accommodation — Viata Luxe Guesthouse">
    <meta property="og:description" content="4 curated apartments: Classic 1, Classic 2, Comfort 3, Deluxe 4. From R950/night, self-catering, city views.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:image" content="<?= e(image_url($og_image)) ?>">
    <meta property="og:site_name" content="Viata Luxe Guesthouse">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('/css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= url('/css/main.css') ?>">
    <style>
/* ——— Page hero ——— */
.page-hero{position:relative; min-height:68vh; overflow:hidden; background:var(--navy); padding-top:68px; display:grid; place-items:center}
.page-hero__media{position:absolute; inset:0}
.page-hero__media img{width:100%; height:100%; object-fit:cover; object-position:center 58%; transform:scale(1.04); animation:heroZoom 9s var(--ease-in-out) forwards}
@keyframes heroZoom{from{transform:scale(1.04)} to{transform:scale(1.1)}}
.page-hero__veil{position:absolute; inset:0; background:linear-gradient(180deg, rgba(11,26,46,0.18) 0%, rgba(11,26,46,0.48) 58%, rgba(11,26,46,0.62) 100%)}
.page-hero__content{position:relative; z-index:3; text-align:center; padding:clamp(72px,14vh,140px) 24px 40px; max-width:720px; display:grid; gap:18px; color:var(--cream)}
.page-hero__kicker{font-size:10px; letter-spacing:0.22em; text-transform:uppercase; font-weight:700; color:var(--gold)}
.page-hero__title{font-family:var(--font-display); font-weight:300; font-size:clamp(32px,5vw,52px); line-height:0.95; letter-spacing:-0.02em}
.page-hero__title em{font-style:italic; color:var(--gold-300)}
.page-hero__lead{font-size:15px; line-height:1.65; color:rgba(248,246,241,0.82); max-width:54ch; margin-inline:auto}
.page-hero__meta{display:flex; justify-content:center; gap:10px; flex-wrap:wrap; margin-top:6px}

/* ——— Section intro ——— */
.section-intro{padding-block:var(--section-pad); text-align:center}
.section-intro .kicker{color:var(--gold); font-size:10px; letter-spacing:0.22em; text-transform:uppercase; font-weight:700; margin-bottom:12px}
.section-intro h2{font-family:var(--font-display); font-weight:300; font-size:clamp(28px,4vw,44px); line-height:0.95; letter-spacing:-0.02em}
.section-intro h2 em{font-style:italic; color:var(--gold-600)}
.section-intro p{color:var(--ink-70); max-width:58ch; font-size:15px; line-height:1.6; margin:12px auto 0}

/* ——— Amenities strip ——— */
.amenities-strip{background:var(--white); border-top:1px solid var(--line); border-bottom:1px solid var(--line); padding:28px 0}
.amenities-strip__grid{display:grid; grid-template-columns:repeat(4,1fr); gap:16px; width:min(var(--container), calc(100% - 2*var(--gutter))); margin-inline:auto; text-align:center}
.amenities-strip__item{display:grid; gap:8px; place-items:center}
.amenities-strip__icon{font-size:28px; color:var(--gold); width:52px; height:52px; display:grid; place-items:center; border-radius:999px; border:1px solid var(--line); background:var(--cream)}
.amenities-strip__label{font-size:12px; font-weight:700; color:var(--navy); letter-spacing:0.04em}
.amenities-strip__sub{font-size:11px; color:var(--ink-55)}
@media (max-width:680px){ .amenities-strip__grid{grid-template-columns:repeat(2,1fr); gap:20px} }

/* ——— Apartments grid ——— */
.apartments-section{background:var(--cream); padding-block:var(--section-pad)}
.apartments-section__head{text-align:center; margin-bottom:28px}
.apartments-grid{display:grid; grid-template-columns:repeat(2,1fr); gap:16px}
.apartment-card{background:var(--white); border:1px solid var(--line); border-radius:var(--radius-lg); overflow:hidden; box-shadow:var(--shadow-soft); transition:transform var(--dur) var(--ease-spring), box-shadow var(--dur) var(--ease-out); display:grid; gap:0}
.apartment-card:hover{transform:translateY(-4px); box-shadow:var(--shadow-medium)}
.apartment-card--reverse{display:grid; grid-template-columns:1fr 1fr}
.apartment-card--reverse .apartment-card__media{order:2}
.apartment-card__media{aspect-ratio:4/3; overflow:hidden; position:relative; background:var(--ivory)}
.apartment-card__media img{width:100%; height:100%; object-fit:cover; transition:transform 700ms var(--ease-out)}
.apartment-card:hover .apartment-card__media img{transform:scale(1.06)}
.apartment-card__badge{position:absolute; bottom:12px; left:12px; font-size:10px; letter-spacing:0.14em; text-transform:uppercase; font-weight:800; background:rgba(248,246,241,0.92); backdrop-filter:blur(8px); border:1px solid var(--line); padding:5px 10px; border-radius:999px; color:var(--navy)}
.apartment-card__body{padding:20px; display:grid; gap:10px; align-content:start}
.apartment-card__kicker{font-size:10px; letter-spacing:0.18em; text-transform:uppercase; font-weight:700; color:var(--gold-600)}
.apartment-card__name{font-family:var(--font-display); font-weight:300; font-size:22px; line-height:1.1}
.apartment-card__specs{display:flex; flex-wrap:wrap; gap:6px; margin-top:2px}
.spec{font-size:11px; color:var(--ink-55); background:var(--cream); border:1px solid var(--line); border-radius:999px; padding:3px 10px}
.spec strong{color:var(--navy); font-weight:700}
.apartment-card__desc{font-size:13px; color:var(--ink-70); line-height:1.6}
.apartment-card__price{display:flex; align-items:baseline; gap:6px; margin-top:4px}
.apartment-card__price strong{font-size:20px; color:var(--navy); font-weight:700}
.apartment-card__price span{font-size:12px; color:var(--ink-55)}
.apartment-card__features{display:grid; grid-template-columns:1fr 1fr; gap:6px; margin-top:4px}
.apartment-card__feature{display:flex; align-items:center; gap:6px; font-size:11px; color:var(--ink-70)}
.apartment-card__feature::before{content:"✓"; color:var(--sage); font-weight:700; font-size:12px}
.apartment-card__actions{display:flex; gap:10px; flex-wrap:wrap; margin-top:6px}
.apartment-card__testimonial{font-size:11px; color:var(--ink-55); font-style:italic; margin-top:6px; padding-top:8px; border-top:1px solid var(--line)}
.apartment-card__testimonial strong{color:var(--navy); font-style:normal}
@media (max-width:860px){ .apartments-grid{grid-template-columns:1fr} .apartment-card--reverse{grid-template-columns:1fr} .apartment-card--reverse .apartment-card__media{order:0} }

/* ——— Commitment / Trust ——— */
.commitment{background:var(--white); border-top:1px solid var(--line); padding-block:var(--section-pad)}
.commitment__inner{width:min(var(--container), calc(100% - 2*var(--gutter))); margin-inline:auto; display:grid; grid-template-columns:repeat(3,1fr); gap:20px}
.commitment-item{display:grid; gap:10px; text-align:center; padding:20px}
.commitment-item__icon{font-size:30px; color:var(--gold)}
.commitment-item__title{font-family:var(--font-display); font-weight:300; font-size:18px}
.commitment-item__text{font-size:13px; color:var(--ink-70); line-height:1.6}
@media (max-width:768px){ .commitment__inner{grid-template-columns:1fr} }

/* ——— CTA banner ——— */
.accom-cta{background:linear-gradient(135deg, var(--navy) 0%, var(--navy-60) 100%); color:var(--cream); padding:36px 0; position:relative; overflow:hidden}
.accom-cta::before{content:""; position:absolute; inset:0; background:radial-gradient(400px 300px at 80% 50%, rgba(184,150,90,0.15), transparent 60%); pointer-events:none}
.accom-cta__inner{width:min(var(--container), calc(100% - 2*var(--gutter))); margin-inline:auto; display:grid; grid-template-columns:1fr auto; gap:24px; align-items:center; position:relative}
.accom-cta__title{font-family:var(--font-display); font-weight:300; font-size:clamp(22px,3vw,32px); line-height:0.95}
.accom-cta__title em{font-style:italic; color:var(--gold-300)}
.accom-cta__text{font-size:14px; color:rgba(248,246,241,0.7); margin-top:6px}
@media (max-width:700px){ .accom-cta__inner{grid-template-columns:1fr; text-align:center} }

/* ——— Outline button ——— */
.btn--outline{display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:11px 20px; border:1px solid var(--navy); color:var(--navy); background:transparent; border-radius:999px; font-size:12px; letter-spacing:0.14em; text-transform:uppercase; font-weight:700; text-decoration:none; transition:background var(--dur) var(--ease-out), color var(--dur) var(--ease-out), border-color var(--dur) var(--ease-out)}
.btn--outline:hover{background:var(--navy); color:var(--cream)}

/* ——— Nav brand lockup (matching home) ——— */
.nav__brand{display:flex; align-items:center; gap:12px}
.nav__brand img{height:36px; width:auto; object-fit:contain}
.nav__brand-text{font-family:var(--font-display); font-weight:300; letter-spacing:0.14em; text-transform:uppercase; font-size:19px; color:var(--navy)}
.nav__brand-text span{color:var(--gold)}
    </style>
    <script src="https://cdn.jsdelivr.net/npm/lenis@1.1.18/dist/lenis.min.js" integrity="sha384-tKsJDT6PlUI0pSBt9/sBKJluKgA19/a6mBrDsZaXotLB4ZYfMGM6xt6/WgGpYhTm" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha384-g4NTh/Iv5PPU4xPyhEWqPcwtNXOvdaDI8LLnyYfyNZOjKJeYQyjzQ9X5275eBjpt" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" integrity="sha384-Z3REaz79l2IaAZqJsSABtTbhjgOUYyV3p90XNnAPCSHg3EMTz1fouunq9WZRtj3d" crossorigin="anonymous"></script>
</head>
<body>
<a class="skip-link" href="#main-content">Skip to content</a>
<div id="preloader" class="preloader" aria-hidden="true">
  <div class="preloader__inner">
    <span class="preloader__mark">Viata Luxe</span>
    <div class="preloader__rule"></div>
    <div class="preloader__sub">Accommodation · 4 Apartments</div>
    <div class="preloader__bar"><i></i></div>
  </div>
</div>
<div class="grain" aria-hidden="true"></div>

<nav class="nav" aria-label="Primary">
  <div class="nav__inner">
    <a class="nav__brand" href="<?= url('/') ?>" aria-label="Viata Luxe Guesthouse — Home">
      <img src="<?= e(url(setting('logo_dark', '/Luxury Images/logos/logo-viata-full-dark-official.png'))) ?>" alt="Viata Luxe" width="132" height="36" fetchpriority="high" decoding="async" onerror="this.style.display='none'">
      <span class="nav__brand-text">Viata <span>Luxe</span></span>
    </a>
    <div class="nav__links" role="navigation">
      <a href="<?= url('/') ?>">Home</a>
      <a class="is-active" href="<?= url('/accomodation/') ?>" aria-current="page">Accommodation</a>
      <a href="<?= url('/safari/') ?>">Safari</a>
      <a href="<?= url('/gallery/') ?>">Gallery</a>
      <a href="<?= url('/contact/') ?>">Contact</a>
    </div>
    <a class="nav__cta nav__cta--desktop" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener">Book Now</a>
    <a class="nav__admin" href="/admin/login" rel="nofollow" aria-label="Admin login">Admin</a>
    <button id="navToggle" class="nav__toggle" aria-expanded="false" aria-controls="mobileDrawer" aria-label="Open menu"><span></span></button>
  </div>
</nav>
<div id="mobileDrawer" class="mobile-drawer" hidden>
  <a href="<?= url('/') ?>">Home</a>
  <a href="<?= url('/accomodation/') ?>" aria-current="page">Accommodation</a>
  <a href="<?= url('/bachelor-apartment/') ?>">Classic Apartment 1</a>
  <a href="<?= url('/classic-apartment-2/') ?>">Classic Apartment 2</a>
  <a href="<?= url('/comfort-apartment-3/') ?>">Comfort Apartment 3</a>
  <a href="<?= url('/deluxe-apartment-4/') ?>">Deluxe Apartment 4</a>
  <a href="<?= url('/safari/') ?>">Safari</a>
  <a href="<?= url('/gallery/') ?>">Gallery</a>
  <a href="<?= url('/contact/') ?>">Contact</a>
  <a class="cta" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener">Book Now — NightsBridge</a>
</div>
<script>
  document.getElementById('mobileDrawer').hidden=false;
  document.getElementById('mobileDrawer').style.display='none';
  var _t=document.getElementById('navToggle'),_d=document.getElementById('mobileDrawer');
  _t.addEventListener('click',function(){ _d.style.display=_d.classList.contains('is-open')?'grid':'none'; });
  new MutationObserver(function(){ _d.style.display=_d.classList.contains('is-open')?'grid':'none'; }).observe(_d,{attributes:true, attributeFilter:['class']});
</script>

<!-- ====== HERO ====== -->
<section class="page-hero">
  <div class="page-hero__media">
    <img src="<?= url('/Luxury Images/apartments-classic-4/apt4-bedroom-main-view.jpg') ?>" alt="Deluxe apartment — elegant interior with city views" width="1920" height="1080" fetchpriority="high" decoding="async">
  </div>
  <div class="page-hero__veil"></div>
  <div class="page-hero__content">
    <p class="page-hero__kicker">Accommodation — 4 Apartments · Viata Luxe</p>
    <h1 class="page-hero__title">Four apartments.<br><em>One standard: luxe.</em></h1>
    <p class="page-hero__lead">Each apartment features a private balcony with garden views, luxury finishes, and modern amenities for a truly upscale stay.</p>
    <div class="page-hero__meta">
      <span class="chip">13 m² · Queen beds</span>
      <span class="chip">Max 2–6 guests</span>
      <span class="chip">From R950</span>
      <a class="btn btn--primary" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener">Book Now</a>
    </div>
  </div>
</section>

<main id="main-content">

<!-- ====== INTRO ====== -->
<section class="section-intro container reveal">
  <div class="kicker">Your private slice of the bush</div>
  <h2>Discover <em>luxury</em> accommodation</h2>
  <p>One Bedroom Apartment · 5 Sleeper Apartment — both with City Views, Tours, Drinks &amp; Food, Wifi, DSTV, and Spacious Rooms. From classic comfort to luxury suite, every apartment is self-contained.</p>
</section>

<!-- ====== AMENITIES STRIP ====== -->
<section class="amenities-strip">
  <div class="amenities-strip__grid">
    <div class="amenities-strip__item reveal">
      <div class="amenities-strip__icon">⬢</div>
      <div class="amenities-strip__label">Free WiFi</div>
      <div class="amenities-strip__sub">Complimentary in-room</div>
    </div>
    <div class="amenities-strip__item reveal reveal--delay-1">
      <div class="amenities-strip__icon">🅿</div>
      <div class="amenities-strip__label">Secure Parking</div>
      <div class="amenities-strip__sub">On-site, gated</div>
    </div>
    <div class="amenities-strip__item reveal reveal--delay-2">
      <div class="amenities-strip__icon">◐</div>
      <div class="amenities-strip__label">Pool &amp; Jacuzzi</div>
      <div class="amenities-strip__sub">Garden oasis</div>
    </div>
    <div class="amenities-strip__item reveal reveal--delay-3">
      <div class="amenities-strip__icon">✦</div>
      <div class="amenities-strip__label">Full Kitchen</div>
      <div class="amenities-strip__sub">Self-catering ready</div>
    </div>
  </div>
</section>

<!-- ====== APARTMENTS GRID ====== -->
<section class="apartments-section">
  <div class="container">
    <div class="apartments-section__head reveal">
      <div class="kicker">Nightly rates</div>
      <h2 class="section-heading">Your private <em>slice of the bush</em></h2>
      <p class="subhead">From classic comfort to luxury suite — every apartment is self-contained with kitchen, jacuzzi access, and Kruger on your doorstep.</p>
    </div>
    <div class="apartments-grid">
      <?php $i = 0; foreach ($apartments as $apt):
        $images    = get_apartment_images((int)$apt['id']);
        $first_img = $images[0]['image_path'] ?? '/Luxury Images/apartments-classic-1/apt1-kitchen-dining-main.jpg';
        $first_alt = $images[0]['alt_text'] ?? e($apt['name']);
        $features  = json_decode($apt['features'] ?? '[]', true);
        $testimonials = get_apartment_testimonials((int)$apt['id']);
        $t = $testimonials[0] ?? null;
      ?>
      <article class="apartment-card<?= ($i % 2 === 1) ? ' apartment-card--reverse' : '' ?> reveal<?= $i > 0 ? ' reveal--delay-' . min($i, 3) : '' ?>">
        <div class="apartment-card__media">
          <img src="<?= url($first_img) ?>" alt="<?= $first_alt ?>" width="1200" height="800" loading="lazy" decoding="async">
          <?php if (!empty($apt['is_featured'])): ?>
            <span class="apartment-card__badge">Featured</span>
          <?php endif; ?>
        </div>
        <div class="apartment-card__body">
          <div class="apartment-card__kicker"><?= e($apt['name']) ?></div>
          <h3 class="apartment-card__name"><?= e($apt['tagline'] ?? $apt['name']) ?></h3>
          <div class="apartment-card__specs">
            <?php if (!empty($apt['max_guests'])): ?>
              <span class="spec">Sleeps <strong><?= e($apt['max_guests']) ?></strong></span>
            <?php endif; ?>
            <?php if (!empty($apt['bedrooms'])): ?>
              <span class="spec"><?= e($apt['bedrooms']) ?> <?= (int)$apt['bedrooms'] === 1 ? 'Bedroom' : 'Bedrooms' ?></span>
            <?php endif; ?>
            <?php if (!empty($apt['area_sqm'])): ?>
              <span class="spec"><?= e($apt['area_sqm']) ?> m²</span>
            <?php endif; ?>
            <?php if (!empty($apt['bathrooms'])): ?>
              <span class="spec"><?= e($apt['bathrooms']) ?> <?= (int)$apt['bathrooms'] === 1 ? 'Bath' : 'Baths' ?></span>
            <?php endif; ?>
          </div>
          <?php if (!empty($apt['description'])): ?>
            <p class="apartment-card__desc"><?= e(mb_strimwidth(strip_tags($apt['description']), 0, 180, '…')) ?></p>
          <?php endif; ?>
          <div class="apartment-card__price">
            <strong>From <?= e($apt['price_from'] ? 'R' . number_format((float)$apt['price_from'], 0) : 'R950') ?></strong>
            <span>/ night</span>
          </div>
          <?php if (!empty($features)): ?>
            <div class="apartment-card__features">
              <?php foreach (array_slice($features, 0, 4) as $feat): ?>
                <span class="apartment-card__feature"><?= is_array($feat) ? e($feat['name'] ?? $feat) : e($feat) ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <div class="apartment-card__actions">
            <a class="btn btn--navy" href="<?= url('/' . e($apt['slug']) . '/') ?>">View Details →</a>
            <a class="link" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener">Book Now</a>
          </div>
          <?php if ($t): ?>
            <div class="apartment-card__testimonial">Testimonial — <strong><?= e($t['author_name'] ?? 'Guest') ?></strong>: "<?= e(mb_strimwidth($t['quote'] ?? '', 0, 120, '…')) ?>"</div>
          <?php endif; ?>
        </div>
      </article>
      <?php $i++; endforeach; ?>
    </div>
  </div>
</section>

<!-- ====== COMMITMENT / TRUST ====== -->
<section class="commitment">
  <div class="commitment__inner">
    <div class="commitment-item reveal">
      <div class="commitment-item__icon">◐</div>
      <div class="commitment-item__title">Fully Furnished</div>
      <div class="commitment-item__text">Each apartment is elegantly furnished with modern finishes, queen beds, full kitchens, and everything you need for a self-catering stay.</div>
    </div>
    <div class="commitment-item reveal reveal--delay-1">
      <div class="commitment-item__icon">♡</div>
      <div class="commitment-item__title">Personalised Service</div>
      <div class="commitment-item__text">Dedicated, well-trained staff committed to making every guest's stay truly memorable — from arrival to departure.</div>
    </div>
    <div class="commitment-item reveal reveal--delay-2">
      <div class="commitment-item__icon">✦</div>
      <div class="commitment-item__title">Prime Location</div>
      <div class="commitment-item__text">86 Nollie Bosman Street, Phalaborwa — just minutes from Kruger National Park gate. The perfect base for your safari adventure.</div>
    </div>
  </div>
</section>

<!-- ====== CTA ====== -->
<section class="accom-cta" aria-label="Book your stay">
  <div class="accom-cta__inner">
    <div>
      <h2 class="accom-cta__title">Ready to stay?<br><em>One check.</em></h2>
      <p class="accom-cta__text">Pick Classic 1–4, pick a date — NightsBridge instant confirms.</p>
    </div>
    <div>
      <a class="btn btn--gold" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener">Check Availability — NightsBridge</a>
    </div>
  </div>
</section>

</main>

<footer class="footer">
  <div class="footer__inner">
    <div class="footer__top">
      <div class="footer__brand">Viata <span>Luxe</span> · Phalaborwa</div>
      <nav class="footer__nav" aria-label="Footer"><a href="<?= url('/') ?>">Home</a><a href="<?= url('/accomodation/') ?>">Accommodation</a><a href="<?= url('/safari/') ?>">Safari</a><a href="<?= url('/gallery/') ?>">Gallery</a><a href="<?= url('/contact/') ?>">Contact</a></nav>
    </div>
    <div class="footer__legal"><span>© 2026 Viata Luxe Guesthouse. 86 Nollie Bosman Street, Phalaborwa 1390.</span><a class="footer__admin-btn" href="<?= e(url('/admin/login')) ?>" rel="nofollow" aria-label="Admin login">✳ Admin</a></div>
    <div class="footer__logos">
      <img src="<?= e(url(setting('logo_dark', '/Luxury Images/logos/logo-viata-full-dark-official.png'))) ?>" alt="Viata Luxe Guesthouse" loading="lazy" decoding="async">
    </div>
  </div>
</footer>

<div id="lightbox" class="lightbox" aria-hidden="true"><button class="lightbox__close" aria-label="Close">✕</button><img alt=""><span class="lightbox__counter"></span><button class="lightbox__nav lightbox__nav--prev" aria-label="Previous">‹</button><button class="lightbox__nav lightbox__nav--next" aria-label="Next">›</button></div>
<a href="tel:+27157810518" class="call-float" aria-label="Call Viata Luxe"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.01-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.07 21 3 13.93 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.1.31.03.66-.25 1.02l-2.2 2.2z"/></svg></a>
<a href="https://wa.me/27794182077?text=Hi%20Viata%20Luxe%2C%20I%27d%20like%20to%20enquire%20about%20availability." target="_blank" rel="noopener" class="wa-float" aria-label="Chat on WhatsApp"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></a>
<script src="<?= url('/js/main.js') ?>"></script>
</body>
</html>
