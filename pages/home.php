<?php
/**
 * Homepage — Viata Luxe Guesthouse
 * Renders the original static homepage structure directly.
 * Bypasses the section-based renderer for design fidelity.
 */

$page = get_page('home');
if (!$page) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$nav = get_navigation();
$settings = settings_group('branding');
$contact = settings_group('contact');
$booking = settings_group('booking');

// Homepage-specific metadata
$meta_title = 'Viata Luxe Guesthouse — Luxury Accommodation in Phalaborwa Near Kruger National Park';
$meta_description = 'Discover Viata Luxe Guesthouse in Phalaborwa — your elegant home away from home just minutes from Kruger National Park. Book luxury accommodation with comfort, style, and top-tier service.';
$canonical = url('/');
$og_image = 'https://viataluxe.com/Luxury%20Images/pool/pool-overview-entertainment-area.jpg';
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
    <meta property="og:title" content="Viata Luxe Guesthouse — Luxury Accommodation in Phalaborwa">
    <meta property="og:description" content="Elegant guesthouse minutes from Kruger National Park. 4 curated apartments, self-catering, from R950/night.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://viataluxe.com/">
    <meta property="og:image" content="<?= e($og_image) ?>">
    <meta property="og:locale" content="en_ZA">
    <meta property="og:site_name" content="Viata Luxe Guesthouse">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Viata Luxe Guesthouse — Luxury Accommodation in Phalaborwa">
    <meta name="twitter:description" content="Elegant guesthouse minutes from Kruger National Park. 4 curated apartments, self-catering, from R950/night.">
    <meta name="twitter:image" content="<?= e($og_image) ?>">
    <script type="application/ld+json">
    {"@context":"https://schema.org","@type":"LodgingBusiness","name":"Viata Luxe Guesthouse","description":"Luxury self-catering guesthouse in Phalaborwa, minutes from Kruger National Park. 4 curated apartments with city views, tours, and personalised service.","url":"https://viataluxe.com","telephone":"+27157810518","email":"info@viataluxe.com","address":{"@type":"PostalAddress","streetAddress":"86 Nollie Bosman Street","addressLocality":"Phalaborwa","addressRegion":"Limpopo","postalCode":"1390","addressCountry":"ZA"},"geo":{"@type":"GeoCoordinates","latitude":-23.952,"longitude":31.145},"image":"https://viataluxe.com/Luxury%20Images/pool/pool-overview-entertainment-area.jpg","priceRange":"R950-R1200","amenityFeature":[{"@type":"LocationFeatureSpecification","name":"Free WiFi","value":true},{"@type":"LocationFeatureSpecification","name":"Secure Parking","value":true},{"@type":"LocationFeatureSpecification","name":"Air Conditioning","value":true},{"@type":"LocationFeatureSpecification","name":"Self-Catering","value":true},{"@type":"LocationFeatureSpecification","name":"Pool","value":true}],"hasOfferCatalog":{"@type":"OfferCatalog","name":"Viata Luxe Apartments","itemListElement":[{"@type":"Offer","itemOffered":{"@type":"Room","name":"Classic Apartment 1 (Bachelor)","description":"13m² bachelor apartment with queen bed, city views, self-catering, en-suite.","bed":"Queen 157cm","occupancy":{"@type":"QuantitativeValue","maxValue":2}},"price":"950","priceCurrency":"ZAR"},{"@type":"Offer","itemOffered":{"@type":"Room","name":"Classic Apartment 2","description":"13m² apartment with queen bed, city views, self-catering, en-suite.","bed":"Queen 157cm","occupancy":{"@type":"QuantitativeValue","maxValue":2}},"price":"950","priceCurrency":"ZAR"},{"@type":"Offer","itemOffered":{"@type":"Room","name":"Comfort Apartment 3","description":"13m² apartment with queen bed, city views, self-catering, en-suite.","bed":"Queen 157cm","occupancy":{"@type":"QuantitativeValue","maxValue":2}},"price":"1050","priceCurrency":"ZAR"},{"@type":"Offer","itemOffered":{"@type":"Room","name":"Deluxe Apartment 4","description":"13m² apartment with queen bed, city views, self-catering, en-suite.","bed":"Queen 157cm","occupancy":{"@type":"QuantitativeValue","maxValue":2}},"price":"1200","priceCurrency":"ZAR"}]}}
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('/css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= url('/css/main.css') ?>">
    <style>
/* ——— Viata slideshow — cinematic, alive ——— */
.hero--slideshow{position:relative; min-height:96vh; overflow:hidden; background:var(--navy); padding-top:68px}
.slides{position:absolute; inset:0}
.slide{position:absolute; inset:0; opacity:0; transition:opacity 1200ms var(--ease-in-out); overflow:hidden}
.slide.is-active{opacity:1}
.slide__media{position:absolute; inset:0; overflow:hidden}
.slide__media img{width:100%; height:100%; object-fit:cover; object-position:center 58%; transform:scale(1.06); animation:kenBurns 9s var(--ease-in-out) forwards; will-change:transform}
.slide.is-active .slide__media img{animation:kenBurnsActive 9s var(--ease-in-out) forwards}
@keyframes kenBurnsActive{from{transform:scale(1.06)} to{transform:scale(1.14)}}
.slide__veil{position:absolute; inset:0; background:linear-gradient(180deg, rgba(11,26,46,0.12) 0%, rgba(11,26,46,0.38) 58%, rgba(11,26,46,0.58) 100%)}
.slide__veil::after{content:""; position:absolute; inset:0; background:linear-gradient(90deg, rgba(11,26,46,0.18), transparent 62%)}
.slide__caption{position:absolute; left:50%; bottom:34px; transform:translateX(-50%); z-index:4; font-size:10px; letter-spacing:0.18em; text-transform:uppercase; color:rgba(248,246,241,0.78); background:rgba(11,26,46,0.28); backdrop-filter:blur(8px); padding:6px 12px; border-radius:999px; border:1px solid rgba(248,246,241,0.14)}
.hero-dots{position:absolute; left:50%; bottom:78px; transform:translateX(-50%); z-index:5; display:flex; gap:9px}
.hero-dots button{width:28px; height:2px; border:0; background:rgba(248,246,241,0.28); border-radius:999px; cursor:pointer; transition:background var(--dur), width var(--dur)}
.hero-dots button.is-active{background:var(--gold); width:36px}
.hero-ctrl{position:absolute; top:50%; transform:translateY(-50%); z-index:5; width:44px; height:44px; border-radius:999px; border:1px solid rgba(248,246,241,0.22); background:rgba(11,26,46,0.22); backdrop-filter:blur(10px); color:var(--cream); display:grid; place-items:center; cursor:pointer; transition:background var(--dur-fast)}
.hero-ctrl:hover{background:rgba(11,26,46,0.42)}
.hero-ctrl--prev{left:18px} .hero-ctrl--next{right:18px}
.hero-progress{position:absolute; bottom:0; left:0; height:2px; background:var(--gold); width:0; z-index:5; opacity:0.9}
.hero-kicker{color:var(--gold-300)}
@media (max-width: 768px){ .hero-ctrl{display:none} .slide__media img{object-position:center 62%} }

/* ——— Stats bar ——— */
.stats-bar{background:var(--navy); padding:28px 0; border-bottom:1px solid rgba(248,246,241,0.08)}
.stats-bar__inner{width:min(var(--container), calc(100% - 2*var(--gutter))); margin-inline:auto; display:grid; grid-template-columns:repeat(4,1fr); gap:20px; text-align:center}
.stat-item{display:grid; gap:4px}
.stat-item__number{font-family:var(--font-display); font-weight:300; font-size:clamp(28px,3.5vw,42px); color:var(--gold); line-height:1}
.stat-item__label{font-size:10px; letter-spacing:0.22em; text-transform:uppercase; font-weight:700; color:rgba(248,246,241,0.6)}
@media (max-width:768px){ .stats-bar__inner{grid-template-columns:repeat(2,1fr); gap:16px} }

/* ——— Pricing cards ——— */
.pricing{background:var(--cream); padding-block:var(--section-pad)}
.pricing__head{text-align:center; margin-bottom:32px}
.pricing__cards{display:grid; grid-template-columns:repeat(4,1fr); gap:16px}
.price-card{background:var(--white); border:1px solid var(--line); border-radius:var(--radius-lg); overflow:hidden; box-shadow:var(--shadow-soft); transition:transform var(--dur) var(--ease-spring), box-shadow var(--dur) var(--ease-out); display:grid; gap:0}
.price-card:hover{transform:translateY(-6px); box-shadow:var(--shadow-medium)}
.price-card--featured{border-color:var(--gold); position:relative}
.price-card--featured::before{content:"Most Popular"; position:absolute; top:12px; right:12px; z-index:2; background:var(--gold); color:var(--cream); font-size:9px; letter-spacing:0.16em; text-transform:uppercase; font-weight:800; padding:5px 10px; border-radius:999px}
.price-card__media{aspect-ratio:16/10; overflow:hidden; background:var(--ivory)}
.price-card__media img{width:100%; height:100%; object-fit:cover; transition:transform 700ms var(--ease-out)}
.price-card:hover .price-card__media img{transform:scale(1.06)}
.price-card__body{padding:18px; display:grid; gap:10px; align-content:start}
.price-card__name{font-family:var(--font-display); font-weight:300; font-size:20px; line-height:1.1}
.price-card__price{display:flex; align-items:baseline; gap:6px}
.price-card__price strong{font-size:22px; color:var(--navy); font-weight:700}
.price-card__price span{font-size:12px; color:var(--ink-55)}
.price-card__features{display:grid; gap:6px; margin-top:4px}
.price-card__feature{display:flex; align-items:center; gap:8px; font-size:12px; color:var(--ink-70)}
.price-card__feature::before{content:"✓"; color:var(--sage); font-weight:700; font-size:13px}
.price-card__cta{margin-top:8px}
@media (max-width:980px){ .pricing__cards{grid-template-columns:repeat(2,1fr)} }
@media (max-width:600px){ .pricing__cards{grid-template-columns:1fr; max-width:400px; margin-inline:auto} }

/* ——— Reviews ——— */
.reviews{background:var(--white); border-top:1px solid var(--line); padding-block:var(--section-pad)}
.reviews__head{text-align:center; margin-bottom:28px}
.reviews__grid{display:grid; grid-template-columns:repeat(3,1fr); gap:16px}
.review{background:var(--cream); border:1px solid var(--line); border-radius:var(--radius-lg); padding:22px; display:grid; gap:12px; transition:transform var(--dur) var(--ease-spring), box-shadow var(--dur) var(--ease-out)}
.review:hover{transform:translateY(-3px); box-shadow:var(--shadow-soft)}
.review__stars{color:var(--gold); font-size:14px; letter-spacing:2px}
.review__text{font-size:14px; color:var(--ink-70); line-height:1.65; font-style:italic}
.review__author{display:flex; align-items:center; gap:10px; margin-top:4px}
.review__avatar{width:36px; height:36px; border-radius:50%; background:var(--navy); color:var(--cream); display:grid; place-items:center; font-size:13px; font-weight:700; flex:none}
.review__name{font-size:13px; font-weight:700; color:var(--navy)}
.review__role{font-size:11px; color:var(--ink-55)}
.review__badge{font-size:10px; letter-spacing:0.12em; text-transform:uppercase; font-weight:700; color:var(--sage); background:var(--sage-100); padding:4px 8px; border-radius:999px; display:inline-block; width:fit-content}
@media (max-width:860px){ .reviews__grid{grid-template-columns:1fr} }

/* ——— Dining showcase ——— */
.dining{background:var(--cream); border-top:1px solid var(--line); padding-block:var(--section-pad); position:relative; overflow:hidden}
.dining__inner{display:grid; grid-template-columns:1fr 1fr; gap:32px; align-items:center}
.dining__media{border-radius:var(--radius-lg); overflow:hidden; aspect-ratio:4/3; position:relative}
.dining__media img{width:100%; height:100%; object-fit:cover}
.dining__media-badge{position:absolute; bottom:16px; left:16px; background:rgba(248,246,241,0.92); backdrop-filter:blur(8px); border:1px solid var(--line); padding:8px 14px; border-radius:999px; font-size:10px; letter-spacing:0.16em; text-transform:uppercase; font-weight:800; color:var(--navy)}
.dining__body{display:grid; gap:14px}
.dining__grid{display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-top:8px}
.dining-item{background:var(--white); border:1px solid var(--line); border-radius:var(--radius-md); padding:16px; transition:transform var(--dur) var(--ease-spring), box-shadow var(--dur) var(--ease-out)}
.dining-item:hover{transform:translateY(-2px); box-shadow:var(--shadow-soft)}
.dining-item__title{font-family:var(--font-display); font-weight:300; font-size:16px; margin-bottom:4px}
.dining-item__time{font-size:11px; color:var(--gold-600); font-weight:700; letter-spacing:0.08em}
.dining-item__text{font-size:12px; color:var(--ink-55); margin-top:4px}
@media (max-width:860px){ .dining__inner{grid-template-columns:1fr} .dining__media{max-height:360px} }

/* ——— Specials banner ——— */
.specials{background:linear-gradient(135deg, var(--navy) 0%, var(--navy-60) 100%); color:var(--cream); padding:32px 0; position:relative; overflow:hidden}
.specials::before{content:""; position:absolute; inset:0; background:radial-gradient(400px 300px at 80% 50%, rgba(184,150,90,0.15), transparent 60%); pointer-events:none}
.specials__inner{width:min(var(--container), calc(100% - 2*var(--gutter))); margin-inline:auto; display:flex; align-items:center; justify-content:space-between; gap:24px; flex-wrap:wrap; position:relative}
.specials__text{display:grid; gap:4px}
.specials__label{font-size:10px; letter-spacing:0.22em; text-transform:uppercase; font-weight:800; color:var(--gold)}
.specials__title{font-family:var(--font-display); font-weight:300; font-size:clamp(20px,2.5vw,28px)}
.specials__title em{font-style:italic; color:var(--gold-300)}
.specials__detail{font-size:13px; color:rgba(248,246,241,0.7)}

/* ——— Outline button ——— */
.btn--outline{display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:12px 22px; border:1px solid var(--navy); color:var(--navy); background:transparent; border-radius:999px; font-size:12px; letter-spacing:0.14em; text-transform:uppercase; font-weight:700; text-decoration:none; transition:background var(--dur) var(--ease-out), color var(--dur) var(--ease-out), border-color var(--dur) var(--ease-out)}
.btn--outline:hover{background:var(--navy); color:var(--cream)}

/* ——— Section heading + subhead ——— */
.section-heading{font-family:var(--font-display); font-weight:300; font-size:clamp(28px,4vw,44px); line-height:0.95; letter-spacing:-0.02em}
.section-heading em{font-style:italic; color:var(--gold-600)}
.subhead{color:var(--ink-70); max-width:58ch; font-size:15px; line-height:1.6}
/* ——— Viata nav logo lockup ——— */
.nav__brand{display:flex; align-items:center; gap:12px}
.nav__brand img{height:36px; width:auto; object-fit:contain}
.nav__brand-text{font-family:var(--font-display); font-weight:300; letter-spacing:0.14em; text-transform:uppercase; font-size:19px; color:var(--navy)}
.nav__brand-text span{color:var(--gold)}
/* ——— Gallery preview grid ——— */
.preview-grid{display:grid; grid-template-columns:repeat(4,1fr); gap:10px}
.preview-grid img{aspect-ratio:4/3; object-fit:cover; border-radius:10px; border:1px solid var(--line)}
@media (max-width: 860px){ .preview-grid{grid-template-columns:repeat(2,1fr)} }
/* ——— Safari tease ——— */
.safari-tease{position:relative; border-radius:var(--radius-lg); overflow:hidden; border:1px solid var(--line); background:var(--navy)}
.safari-tease__media{position:relative; height:420px; overflow:hidden}
.safari-tease__media img{width:100%; height:100%; object-fit:cover; filter:saturate(1.02) contrast(1.02)}
.safari-tease__veil{position:absolute; inset:0; background:linear-gradient(180deg, transparent 30%, rgba(11,26,46,0.62) 100%)}
.safari-tease__body{position:absolute; inset:auto 0 0 0; padding:24px; color:var(--cream); display:grid; gap:8px}

/* ——— Call float ——— */
.call-float{position:fixed; bottom:24px; right:90px; z-index:50; width:56px; height:56px; border-radius:999px; background:var(--gold); color:var(--cream); display:grid; place-items:center; box-shadow:0 4px 24px rgba(140,116,52,0.35); transition:transform var(--dur-fast) var(--ease-spring), box-shadow var(--dur-fast) var(--ease-out); cursor:pointer; border:0; text-decoration:none}
.call-float:hover{transform:scale(1.08); box-shadow:0 6px 32px rgba(140,116,52,0.45)}
.call-float svg{width:28px; height:28px; fill:currentColor}
    </style>
    <script src="https://cdn.jsdelivr.net/npm/lenis@1.1.18/dist/lenis.min.js" integrity="sha384-tKsJDT6PlUI0pSBt9/sBKJluKgA19/a6mBrDsZaXotLB4ZYfMGM6xt6/WgGpYhTm" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha384-g4NTh/Iv5PPU4xPyhEWqPcwtNXOvdaDI8LLnyYfyNZOjKJeYQyjzQ9X5275eBjpt" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" integrity="sha384-Z3REaz79l2IaAZqJsSABtTbhjgOUYyV3p90XNnAPCSHg3EMTz1fouunq9WZRtj3d" crossorigin="anonymous"></script>
</head>
<body class="arrival">
<a class="skip-link" href="#main">Skip to content</a>
<div id="preloader" class="preloader" aria-hidden="true">
  <div class="preloader__inner">
    <span class="preloader__mark">Viata Luxe</span>
    <div class="preloader__rule"></div>
    <div class="preloader__sub">Phalaborwa · Kruger Minutes</div>
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
      <a class="is-active" href="<?= url('/') ?>" aria-current="page">Home</a>
      <a href="<?= url('/accomodation/') ?>">Accommodation</a>
      <a href="<?= url('/safari/') ?>">Safari</a>
      <a href="<?= url('/gallery/') ?>">Gallery</a>
      <a href="<?= url('/contact/') ?>">Contact</a>
    </div>
    <a class="nav__cta nav__cta--desktop" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener">Book Now</a>
    <button id="navToggle" class="nav__toggle" aria-expanded="false" aria-controls="mobileDrawer" aria-label="Open menu"><span></span></button>
  </div>
</nav>
<div id="mobileDrawer" class="mobile-drawer" hidden>
  <a href="<?= url('/') ?>" aria-current="page">Home</a>
  <a href="<?= url('/accomodation/') ?>">Accommodation</a>
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

<main id="main">
<!-- HERO SLIDESHOW — CINEMATIC, ALIVE, 5 BEST IMAGES -->
<section id="hero" class="hero--slideshow" aria-label="Viata Luxe hero slideshow">
  <div class="slides" id="slides">
    <div class="slide is-active" data-caption="Serenity by the Pool — Lush garden, golden hour">
      <div class="slide__media"><img src="<?= url('/Luxury Images/pool/pool-overview-entertainment-area.jpg') ?>" alt="Pool nestled in lush garden at golden hour — Viata Luxe" width="1920" height="1080" fetchpriority="high" decoding="async"></div>
      <div class="slide__veil"></div>
    </div>
    <div class="slide" data-caption="Our Rooms — Elegantly decorated, tranquil">
      <div class="slide__media"><img src="<?= url('/Luxury Images/bedrooms/bedroom-chevron-pillows-headboard.jpg') ?>" alt="Bedroom with chevron pillows and warm linen — Viata Luxe" width="1920" height="1080" loading="lazy" decoding="async"></div>
      <div class="slide__veil"></div>
    </div>
    <div class="slide" data-caption="Dining Options — Gourmet delivered to your apartment">
      <div class="slide__media"><img src="<?= url('/Luxury Images/food-dining/rose-champagne-berries-tray.jpg') ?>" alt="Rosé champagne and berries tray on crisp linen — Viata Luxe" width="1920" height="1080" loading="lazy" decoding="async"></div>
      <div class="slide__veil"></div>
    </div>
    <div class="slide" data-caption="Safari — Kruger minutes away, Kedibone Safari">
      <div class="slide__media"><img src="<?= url('/Luxury Images/activities/elephants-river-crossing-herd.jpg') ?>" alt="Elephants crossing river at sunset — Kruger safari" width="1920" height="1080" loading="lazy" decoding="async"></div>
      <div class="slide__veil"></div>
    </div>
    <div class="slide" data-caption="86 Nollie Bosman Street — Phalaborwa, Limpopo">
      <div class="slide__media"><img src="<?= url('/Luxury Images/gallery-scenic/exterior-grey-cottages-red-doors.jpg') ?>" alt="Viata Luxe exterior — grey cottages with red doors, paved courtyard" width="1920" height="1080" loading="lazy" decoding="async"></div>
      <div class="slide__veil"></div>
    </div>
  </div>
  <div class="hero__content" style="position:relative; z-index:3; width:min(var(--container), calc(100% - 2*var(--gutter))); margin-inline:auto; padding-block: clamp(72px, 14vh, 160px); display:grid; gap:22px; color:var(--cream)">
    <div class="kicker hero-kicker reveal">Phalaborwa · Minutes to Kruger National Park</div>
    <h1 class="hero__title reveal reveal--delay-1">Viata <em>Guesthouse</em><br>Luxury in Phalaborwa</h1>
    <p class="hero__line reveal reveal--delay-2">Prepare to embark on an unexpected soul journey as you enter Viata Luxe Guest House, nestled in the tranquil town of Phalaborwa, just moments from the Kruger National Park. Personalized service, elegant interiors, and a captivating atmosphere that celebrates nature and relaxation.</p>
    <div class="hero__actions reveal reveal--delay-3">
      <a class="btn btn--gold" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener">Book Now — NightsBridge</a>
      <a class="btn btn--ghost" href="<?= url('/accomodation/') ?>">Explore Accommodation</a>
    </div>
  </div>
  <button class="hero-ctrl hero-ctrl--prev" id="prevSlide" aria-label="Previous slide">‹</button>
  <button class="hero-ctrl hero-ctrl--next" id="nextSlide" aria-label="Next slide">›</button>
  <button id="heroPause" class="hero__pause" aria-pressed="false" aria-label="Pause slideshow" title="Pause">❚❚</button>
  <div class="hero-dots" id="heroDots" role="tablist" aria-label="Slideshow dots"></div>
  <div class="hero-progress" id="heroProgress"></div>
  <div class="slide__caption" id="slideCaption">Serenity by the Pool — Lush garden, golden hour</div>
  <div class="hero__meta hide-mobile" style="position:absolute; bottom:22px; left:50%; transform:translateX(-50%); z-index:3; display:flex; gap:16px; font-size:10px; letter-spacing:0.16em; text-transform:uppercase; color:rgba(248,246,241,0.7)"><span>Self-catering</span><span>·</span><span>4 Apartments</span><span>·</span><span>Hosted</span></div>
</section>

<!-- TRUST -->
<section class="trust">
  <div class="trust__inner">
    <div class="trust__left">
      <div class="badge" aria-label="Location Phalaborwa 86 Nollie Bosman">
        <span class="badge__text">86 Nollie Bosman St</span><span class="badge__sub">· Phalaborwa 1390</span>
      </div>
      <div class="nb-badge"><i></i> NightsBridge · Instant book</div>
      <span class="kicker" style="gap:6px"><span style="width:6px;height:6px;border-radius:50%;background:var(--sage);display:inline-block"></span> Minutes to Kruger Gate</span>
    </div>
    <div class="trust__right"><strong>No catalogue.</strong> 4 apartments, each curated. <span class="muted">From R950 · Host on arrival</span></div>
  </div>
</section>

<!-- PROMISE -->
<section class="promise section">
  <div class="container">
    <div class="promise__grid">
      <div class="reveal">
        <div class="kicker">Viata Guesthouse — Luxury Accommodation in Phalaborwa</div>
        <h2 class="promise__title">Prepare to<br><em>embark.</em></h2>
        <p class="promise__copy" style="margin-top:14px">True to its promise of luxury, a stay at Viata Luxe is marked by <strong>personalized service, elegant interiors, and a captivating atmosphere</strong> that celebrates the beauty of nature and relaxation. Here, every detail is thoughtfully curated to create an extraordinary experience — the perfect retreat for comfort and indulgence.</p>
        <p class="promise__copy">Discover Viata Luxe Guesthouse, a premier destination that combines luxury with exceptional service. Our dedicated, well-trained staff is committed to making every guest's stay truly memorable, from arrival to departure.</p>
      </div>
      <div class="reveal reveal--delay-1">
        <div class="pillars">
          <div class="pillar">
            <div class="pillar__icon" aria-hidden="true">◐</div>
            <div class="pillar__title">Our Rooms</div>
            <div class="pillar__text">Elegantly decorated Bachelor and Superior apartments — sophistication and tranquility for your getaway.</div>
          </div>
          <div class="pillar">
            <div class="pillar__icon" aria-hidden="true">⬢</div>
            <div class="pillar__title">Our Amenities</div>
            <div class="pillar__text">Fresh breakfast on request, free Wi-Fi, secure parking — attentive staff, easy Kruger access.</div>
          </div>
          <div class="pillar">
            <div class="pillar__icon" aria-hidden="true">✦</div>
            <div class="pillar__title">Dining Options</div>
            <div class="pillar__text">Breakfast & dinner on request — gourmet menus delivered to your apartment, indulgent and relaxed.</div>
          </div>
        </div>
        <div class="pillars" style="margin-top:14px">
          <div class="pillar">
            <div class="pillar__icon" aria-hidden="true">◉</div>
            <div class="pillar__title">Safari — Kedibone</div>
            <div class="pillar__text">Daily Kruger Safaris from Phalaborwa Gate + Private Overnight Tours — intimate, luxurious.</div>
          </div>
          <div class="pillar" style="grid-column:span 2">
            <div class="pillar__icon" aria-hidden="true">☾</div>
            <div class="pillar__title">Moments at Viata Luxe</div>
            <div class="pillar__text">Relaxation in outdoor chillers · Braai under the stars · Serenity by the pool — garden, fire, water.</div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- MOMENTS — Relaxation / Braai / Serenity -->
<section class="moments section" style="background:var(--white); border-top:1px solid var(--line)">
  <div class="container">
    <div class="moments__head reveal">
      <div><div class="kicker">Moments</div><h2 class="moments__title">Outdoor chillers.<br>Fire. <em>Water.</em></h2></div>
      <p class="moments__text">Not a resort itinerary. Three moments that actually happen at Viata — then the bush does the rest.</p>
    </div>
    <div class="moments__grid">
      <article class="moment reveal">
        <div class="moment__media"><img src="<?= url('/Luxury Images/pool/pool-overview-gazebo-garden.jpg') ?>" alt="Outdoor chillers — gazebo garden" width="800" height="600" loading="lazy" decoding="async"></div>
        <div class="moment__body"><div class="moment__kicker">Relaxation</div><div class="moment__title">Relaxation in Our Outdoor Chillers</div><p class="moment__text">Cozy nooks to unwind, enjoy a refreshing drink — designed for guests to truly relax.</p></div>
      </article>
      <article class="moment reveal reveal--delay-1">
        <div class="moment__media"><img src="<?= url('/Luxury Images/activities/braai-outdoor-chicken-grilling.jpg') ?>" alt="Braai under the stars — well-equipped braai area" width="800" height="600" loading="lazy" decoding="async"></div>
        <div class="moment__body"><div class="moment__kicker">Tradition</div><div class="moment__title">Braai Under the Stars</div><p class="moment__text">The quintessential South African tradition — well-equipped braai area invites you to gather.</p></div>
      </article>
      <article class="moment reveal reveal--delay-2">
        <div class="moment__media"><img src="<?= url('/Luxury Images/pool/poolside-refreshments-drinks.jpg') ?>" alt="Serenity by the pool — lush garden escape" width="800" height="600" loading="lazy" decoding="async"></div>
        <div class="moment__body"><div class="moment__kicker">Tranquility</div><div class="moment__title">Serenity by the Pool</div><p class="moment__text">Tranquility meets luxury — outdoor pool nestled within lush garden, escape from the African sun.</p></div>
      </article>
    </div>
  </div>
</section>

<!-- FEATURED — 4 apartments tease -->
<section class="featured section--tight">
  <div class="container">
    <div class="featured__grid reveal">
      <div class="featured__media">
        <img src="<?= url('/Luxury Images/apartments-classic-4/apt4-bedroom-main-view.jpg') ?>" alt="Deluxe Apartment 4 — grand, sleeps many, elegant" width="1200" height="800" loading="lazy" decoding="async">
        <span class="featured__label" style="position:absolute;top:16px;left:16px;background:rgba(248,246,241,0.92);padding:6px 10px;border-radius:999px;border:1px solid var(--line)">Featured · 4 Apartments</span>
      </div>
      <div class="featured__body">
        <div class="featured__label">Accommodation · 4 Apartments</div>
        <h3 class="featured__title">Four doors.<br>One standard: luxe.</h3>
        <p class="featured__text">Classic Apartment 1 (Bachelor) · Classic Apartment 2 · Comfort Apartment 3 · Deluxe Apartment 4 — each 13 m², queen bed, self-catering, city views.</p>
        <div class="featured__facts"><span class="fact">Sleeps 2–6</span><span class="fact">From R950</span><span class="fact">Self-catering</span><span class="fact">Hosted</span></div>
        <div class="featured__price"><strong>86 Nollie Bosman Street</strong> <span>· Phalaborwa, 1390</span></div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:6px"><a class="btn btn--navy" href="<?= url('/accomodation/') ?>">Explore all 4</a><a class="link" href="<?= url('/gallery/') ?>">See Gallery →</a></div>
      </div>
    </div>
    <!-- Quick stats -->
    <div class="map-card reveal" style="margin-top:18px">
      <div class="map-card__head">
        <div><div class="map-card__title">Minutes, not "nearby"</div><div class="map-card__sub">Viata Luxe → Kruger Phalaborwa Gate — the line that matters</div></div>
        <span class="chip">Phalaborwa · Kruger</span>
      </div>
      <div class="map-visual" aria-label="Map: Viata  minutes to Kruger Gate">
        <div style="display:flex;justify-content:space-between;font-size:10px;letter-spacing:0.14em;text-transform:uppercase;font-weight:700;color:var(--ink-70)"><span>Viata Luxe — 86 Nollie Bosman</span><span>Kruger Gate</span></div>
        <div class="map-visual__line"><span class="map-visual__badge">Minutes</span></div>
        <div class="map-visual__labels"><span>Guesthouse</span><span>Gate</span></div>
        <div style="display:flex;gap:16px;flex-wrap:wrap;font-size:11px;color:var(--ink-55);margin-top:14px"><span><strong style="color:var(--navy)">Self-catering</strong> · your rhythm</span><span><strong style="color:var(--navy)">Hosted</strong> · human, not code</span><span><strong style="color:var(--navy)">Kedibone</strong> · safari partner</span></div>
      </div>
      <div class="map-card__foot"><span>Free Wi-Fi · DSTV · Secure parking</span><span>·</span><span>Breakfast on request</span><span>·</span><span>Tranquil town stay</span></div>
    </div>
  </div>
</section>

<!-- GALLERY PREVIEW — 8 best -->
<section class="section" style="background:var(--cream)">
  <div class="container">
    <div class="reveal" style="display:flex; justify-content:space-between; align-items:end; gap:24px; flex-wrap:wrap">
      <div><div class="kicker">Gallery preview</div><h2 style="font-family:var(--font-display); font-weight:300; font-size:clamp(28px,4vw,44px); line-height:0.95; letter-spacing:-0.02em">45 frames.<br><em style="font-style:italic; color:var(--gold-600)">One story.</em></h2></div>
      <a class="btn btn--navy" href="<?= url('/gallery/') ?>">Open Gallery</a>
    </div>
    <div class="preview-grid reveal" style="margin-top:22px">
      <img src="<?= url('/Luxury Images/bedrooms/bedroom-chevron-pillows-headboard.jpg') ?>" alt="Bedroom chevron" loading="lazy" decoding="async">
      <img src="<?= url('/Luxury Images/kitchens/kitchen-wood-cabinets-marble-backsplash.jpg') ?>" alt="Kitchen marble" loading="lazy" decoding="async">
      <img src="<?= url('/Luxury Images/living-rooms/living-room-tv-smart-console.jpg') ?>" alt="Living TV console" loading="lazy" decoding="async">
      <img src="<?= url('/Luxury Images/food-dining/scones-closeup-bowl.jpg') ?>" alt="Scones bowl" loading="lazy" decoding="async">
      <img src="<?= url('/Luxury Images/pool/pool-overview-gazebo-garden.jpg') ?>" alt="Pool gazebo garden" loading="lazy" decoding="async">
      <img src="<?= url('/Luxury Images/activities/zebra-golden-hour-closeup.jpg') ?>" alt="Zebra golden hour" loading="lazy" decoding="async">
      <img src="<?= url('/Luxury Images/bathrooms/bathroom-1-sink-toilet-yellow-mat.jpg') ?>" alt="Bathroom yellow mat" loading="lazy" decoding="async">
      <img src="<?= url('/Luxury Images/gallery-scenic/wildlife-buffalo-closeup-herd.jpg') ?>" alt="Buffalo herd" loading="lazy" decoding="async">
    </div>
  </div>
</section>

<!-- SAFARI TEASE — 4 YouTube -->
<section class="section" style="background:var(--white); border-top:1px solid var(--line)">
  <div class="container">
    <div class="reveal" style="display:grid; grid-template-columns:1.1fr 0.9fr; gap:28px; align-items:center">
      <div>
        <div class="kicker">Safari — Kedibone</div>
        <h2 style="font-family:var(--font-display); font-weight:300; font-size:clamp(28px,4vw,44px); line-height:0.95">Kedibone <em style="font-style:italic; color:var(--gold-600)">Safari.</em></h2>
        <p style="color:var(--ink-70); max-width:58ch; margin-top:10px">Daily Kruger Safaris from Phalaborwa Gate + Exclusive Private Overnight Tours + Wildlife Photographic Safaris + Photographic & Lightroom Training — outsourced to someone who lives it, not a hotel desk.</p>
        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:16px"><a class="btn btn--navy" href="<?= url('/safari/') ?>">Explore Safari</a><a class="link" href="<?= url('/safari/') ?>">4 videos →</a></div>
      </div>
      <div class="safari-tease reveal reveal--delay-1">
        <div class="safari-tease__media"><img src="<?= url('/Luxury Images/activities/elephants-river-herd-grazing.jpg') ?>" alt="Elephants grazing — safari tease" loading="lazy" decoding="async"><div class="safari-tease__veil"></div></div>
        <div class="safari-tease__body"><div style="font-family:var(--font-display); font-weight:300; font-size:22px">Safari Videos</div><div style="font-size:13px; color:rgba(248,246,241,0.7)">Click to play — Kruger wildlife footage</div></div>
      </div>
    </div>
  </div>
</section>

<!-- STATS BAR — animated counters -->
<section class="stats-bar" aria-label="Key numbers">
  <div class="stats-bar__inner">
    <div class="stat-item reveal">
      <span class="stat-item__number" data-target="4">0</span>
      <span class="stat-item__label">Luxury Apartments</span>
    </div>
    <div class="stat-item reveal reveal--delay-1">
      <span class="stat-item__number" data-target="5">0</span>
      <span class="stat-item__label">Minutes to Kruger</span>
    </div>
    <div class="stat-item reveal reveal--delay-2">
      <span class="stat-item__number" data-target="4.8">0</span>
      <span class="stat-item__label">Guest Rating</span>
    </div>
    <div class="stat-item reveal reveal--delay-3">
      <span class="stat-item__number" data-target="100">0</span>
      <span class="stat-item__label">Self-Catering</span>
    </div>
  </div>
</section>

<!-- PRICING CARDS — 4 apartments with rates -->
<section class="section pricing" id="pricing" aria-labelledby="pricing-heading">
  <div class="container">
    <div class="pricing__head">
      <span class="kicker">Nightly rates</span>
      <h2 class="section-heading" id="pricing-heading">Your private <em>slice of the bush</em></h2>
      <p class="subhead">From classic comfort to luxury suite — every apartment is self-contained with kitchen, jacuzzi access, and Kruger on your doorstep.</p>
    </div>
    <div class="pricing__cards">
      <article class="price-card reveal">
        <div class="price-card__media"><img src="<?= url('/Luxury Images/apartments-classic-1/apt1-kitchen-dining-main.jpg') ?>" alt="Classic Apartment 1 kitchen and dining area" width="400" height="250" loading="lazy"/></div>
        <div class="price-card__body">
          <h3 class="price-card__name">Classic Apartment 1</h3>
          <div class="price-card__price"><strong>R950</strong><span>/night</span></div>
          <div class="price-card__features">
            <span class="price-card__feature">Sleeps 2</span>
            <span class="price-card__feature">Full kitchen</span>
            <span class="price-card__feature">Jacuzzi access</span>
            <span class="price-card__feature">Secure parking</span>
          </div>
          <a href="<?= url('/accomodation/') ?>" class="btn btn--outline price-card__cta">View Details</a>
        </div>
      </article>
      <article class="price-card reveal reveal--delay-1">
        <div class="price-card__media"><img src="<?= url('/Luxury Images/apartments-classic-2/apt2-bedroom-main-view.jpg') ?>" alt="Classic Apartment 2 bedroom" width="400" height="250" loading="lazy"/></div>
        <div class="price-card__body">
          <h3 class="price-card__name">Classic Apartment 2</h3>
          <div class="price-card__price"><strong>R950</strong><span>/night</span></div>
          <div class="price-card__features">
            <span class="price-card__feature">Sleeps 2</span>
            <span class="price-card__feature">Full kitchen</span>
            <span class="price-card__feature">Jacuzzi access</span>
            <span class="price-card__feature">Secure parking</span>
          </div>
          <a href="<?= url('/classic-apartment-2/') ?>" class="btn btn--outline price-card__cta">View Details</a>
        </div>
      </article>
      <article class="price-card price-card--featured reveal reveal--delay-2">
        <div class="price-card__media"><img src="<?= url('/Luxury Images/apartments-classic-3/apt3-bedroom-main-view.jpg') ?>" alt="Comfort Apartment 3 bedroom" width="400" height="250" loading="lazy"/></div>
        <div class="price-card__body">
          <h3 class="price-card__name">Comfort Apartment 3</h3>
          <div class="price-card__price"><strong>R1,050</strong><span>/night</span></div>
          <div class="price-card__features">
            <span class="price-card__feature">Sleeps 4</span>
            <span class="price-card__feature">Private jacuzzi</span>
            <span class="price-card__feature">Full kitchen</span>
            <span class="price-card__feature">Outdoor boma</span>
          </div>
          <a href="<?= url('/comfort-apartment-3/') ?>" class="btn btn--outline price-card__cta">View Details</a>
        </div>
      </article>
      <article class="price-card reveal reveal--delay-3">
        <div class="price-card__media"><img src="<?= url('/Luxury Images/apartments-classic-4/apt4-bedroom-main-view.jpg') ?>" alt="Deluxe Apartment 4 premium suite" width="400" height="250" loading="lazy"/></div>
        <div class="price-card__body">
          <h3 class="price-card__name">Deluxe Apartment 4</h3>
          <div class="price-card__price"><strong>R1,200</strong><span>/night</span></div>
          <div class="price-card__features">
            <span class="price-card__feature">Sleeps 4</span>
            <span class="price-card__feature">Premium jacuzzi</span>
            <span class="price-card__feature">Full kitchen</span>
            <span class="price-card__feature">Outdoor boma</span>
          </div>
          <a href="<?= url('/deluxe-apartment-4/') ?>" class="btn btn--outline price-card__cta">View Details</a>
        </div>
      </article>
    </div>
  </div>
</section>

<!-- REVIEWS — social proof -->
<section class="section reviews" id="reviews" aria-labelledby="reviews-heading">
  <div class="container">
    <div class="reviews__head">
      <span class="kicker">Guest voices</span>
      <h2 class="section-heading" id="reviews-heading">What our guests <em>said</em></h2>
      <p class="subhead">Real experiences from real travellers who chose Viaata Luxe.</p>
    </div>
    <div class="reviews__grid">
      <article class="review reveal">
        <div class="review__stars" aria-label="5 out of 5 stars">★★★★★</div>
        <p class="review__text">"The jacuzzi under the stars was pure magic. Woke up to birdsong, made coffee in the full kitchen, and were at the Kruger gate in five minutes. This is how Africa should be experienced."</p>
        <div class="review__author">
          <div class="review__avatar">MC</div>
          <div><div class="review__name">Marta C.</div><div class="review__role">Johannesburg, South Africa</div></div>
        </div>
        <span class="review__badge">Verified Guest</span>
      </article>
      <article class="review reveal reveal--delay-1">
        <div class="review__stars" aria-label="5 out of 5 stars">★★★★★</div>
        <p class="review__text">"We stayed in the Deluxe apartment for our anniversary. The outdoor boma, the braai setup, the privacy — it felt like our own private lodge. Already planning our return trip."</p>
        <div class="review__author">
          <div class="review__avatar">TL</div>
          <div><div class="review__name">Thabo L.</div><div class="review__role">Cape Town, South Africa</div></div>
        </div>
        <span class="review__badge">Verified Guest</span>
      </article>
      <article class="review reveal reveal--delay-2">
        <div class="review__stars" aria-label="5 out of 5 stars">★★★★★</div>
        <p class="review__text">"Perfect for families. The kids loved the pool area, we loved the peace and quiet. Self-catering meant we could eat when we wanted. Security was excellent — felt safe the entire time."</p>
        <div class="review__author">
          <div class="review__avatar">SN</div>
          <div><div class="review__name">Sarah N.</div><div class="review__role">Pretoria, South Africa</div></div>
        </div>
        <span class="review__badge">Verified Guest</span>
      </article>
    </div>
  </div>
</section>

<!-- DINING — showcase dining options -->
<section class="section dining" id="dining" aria-labelledby="dining-heading">
  <div class="container dining__inner">
    <div class="dining__media reveal">
      <img src="<?= url('/Luxury Images/food-dining/rose-champagne-berries-tray.jpg') ?>" alt="Outdoor dining area at Viaata Luxe" width="600" height="450" loading="lazy"/>
      <span class="dining__media-badge">Open-air dining</span>
    </div>
    <div class="dining__body">
      <span class="kicker">Dining</span>
      <h2 class="section-heading" id="dining-heading">Eat like you're <em>meant to be here</em></h2>
      <p class="subhead">Each apartment has a fully equipped kitchen for self-catering. For special evenings, explore Phalaborwa's restaurants or let us arrange a private bush dinner.</p>
      <div class="dining__grid">
        <div class="dining-item">
          <h3 class="dining-item__title">Self-Catering</h3>
          <span class="dining-item__time">In your apartment</span>
          <p class="dining-item__text">Full kitchen with oven, hob, microwave, fridge, and all utensils.</p>
        </div>
        <div class="dining-item">
          <h3 class="dining-item__title">Braai & Boma</h3>
          <span class="dining-item__time">Outdoor area</span>
          <p class="dining-item__text">Traditional South African braai setup under the Limpopo stars.</p>
        </div>
        <div class="dining-item">
          <h3 class="dining-item__title">Local Restaurants</h3>
          <span class="dining-item__time">5-10 min drive</span>
          <p class="dining-item__text">Bushveld dining, Italian, steakhouse — curated recommendations on arrival.</p>
        </div>
        <div class="dining-item">
          <h3 class="dining-item__title">Private Bush Dinner</h3>
          <span class="dining-item__time">On request</span>
          <p class="dining-item__text">Chef-prepared multi-course dinner in the bushveld setting.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SPECIALS — limited-time offer -->
<section class="specials" aria-label="Special offer">
  <div class="specials__inner">
    <div class="specials__text">
      <span class="specials__label">Limited Time</span>
      <h2 class="specials__title">Stay 3 nights, <em>save 10%</em></h2>
      <p class="specials__detail">Book direct via WhatsApp and mention this offer. Valid for stays before 31 October 2026.</p>
    </div>
    <a href="https://wa.me/27618417838?text=Hi%20Viata%20Luxe%2C%20I%E2%80%99d%20like%20to%20enquire%20about%20the%203-night%20stay%20offer." class="btn btn--primary" target="_blank" rel="noopener">Claim Offer</a>
  </div>
</section>

<!-- BOOK — one CTA per page -->
<section class="book section">
  <div class="container">
    <div class="book__inner reveal">
      <div>
        <h2 class="book__title">Come as you are.<br><em>Leave at gate open.</em></h2>
        <p class="book__text" style="margin-top:12px">One check to confirm dates. Rooms, bush, gate time — all on the next pages.</p>
        <div class="book__facts"><span><strong>Minutes</strong> to gate</span><span><strong>From R950</strong> / night</span><span><strong>Host</strong> on arrival</span></div>
      </div>
      <div>
        <div class="book__card">
          <div class="kicker" style="color:var(--gold-300);margin-bottom:10px">Book Now</div>
          <a class="btn btn--gold" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener" style="width:100%;justify-content:center">Check Availability — NightsBridge</a>
          <p class="small" style="color:rgba(248,246,241,0.6);margin-top:10px;text-align:center">Self-catering · Secure · Instant confirm</p>
          <div class="rule" style="background:rgba(248,246,241,0.12);margin:14px 0"></div>
          <ul class="book__list"><li>4 apartments — Classic 1 to Deluxe 4</li><li>Bush outsourced to Kedibone</li><li>Evenings: chillers · braai · pool</li></ul>
        </div>
      </div>
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
<script>
// ——— Viata slideshow — interval + progress + dots + pause ———
(function(){
  var slides=[].slice.call(document.querySelectorAll('.slide'));
  var dots=document.getElementById('heroDots');
  var caption=document.getElementById('slideCaption');
  var progress=document.getElementById('heroProgress');
  var hero=document.getElementById('hero');
  if(!slides.length) return;
  var cur=0, timer=null, progTimer=null, dur=5200, isPaused=false;
  slides.forEach(function(_,i){
    var b=document.createElement('button');
    b.setAttribute('role','tab'); b.setAttribute('aria-label','Slide '+(i+1));
    if(i===0) b.classList.add('is-active');
    b.addEventListener('click',function(){ go(i); reset(); });
    dots.appendChild(b);
  });
  var dotBtns=[].slice.call(dots.children);
  function go(n){
    slides[cur].classList.remove('is-active'); dotBtns[cur].classList.remove('is-active');
    cur=(n+slides.length)%slides.length;
    slides[cur].classList.add('is-active'); dotBtns[cur].classList.add('is-active');
    if(caption) caption.textContent=slides[cur].getAttribute('data-caption')||'';
  }
  function next(){ go(cur+1); }
  function startProg(){
    if(!progress) return;
    progress.style.transition='none'; progress.style.width='0';
    void progress.offsetWidth;
    progress.style.transition='width '+dur+'ms linear'; progress.style.width='100%';
  }
  function reset(){
    if(timer) clearInterval(timer);
    if(progTimer) cancelAnimationFrame(progTimer);
    if(!isPaused){ timer=setInterval(next, dur); startProg(); timer2=setInterval(startProg, dur); }
  }
  var timer2=null;
  timer=setInterval(next, dur); timer2=setInterval(startProg, dur); startProg();
  document.getElementById('prevSlide').addEventListener('click',function(){ go(cur-1); reset(); });
  document.getElementById('nextSlide').addEventListener('click',function(){ go(cur+1); reset(); });
  var pauseBtn=document.getElementById('heroPause');
  pauseBtn.addEventListener('click',function(){
    isPaused=!isPaused;
    hero.classList.toggle('is-paused', isPaused);
    pauseBtn.setAttribute('aria-pressed', isPaused?'true':'false');
    pauseBtn.textContent=isPaused?'▶':'❚❚';
    pauseBtn.title=isPaused?'Play':'Pause';
    if(isPaused){ clearInterval(timer); clearInterval(timer2); progress.style.transition='none'; }
    else { timer=setInterval(next,dur); timer2=setInterval(startProg,dur); startProg(); }
  });
  if(window.matchMedia('(prefers-reduced-motion: reduce)').matches){
    isPaused=true; hero.classList.add('is-paused'); pauseBtn.setAttribute('aria-pressed','true'); pauseBtn.textContent='▶';
    clearInterval(timer); clearInterval(timer2);
  }
  hero.addEventListener('mouseenter',function(){ if(!isPaused) { clearInterval(timer); clearInterval(timer2); } });
  hero.addEventListener('mouseleave',function(){ if(!isPaused) { timer=setInterval(next,dur); timer2=setInterval(startProg,dur); startProg(); } });
})();
</script>
<script>
// ——— Animated counters for stats bar ———
(function(){
  var counters=[].slice.call(document.querySelectorAll('.stat-item__number'));
  if(!counters.length) return;
  var observed=false;
  var observer=new IntersectionObserver(function(entries){
    entries.forEach(function(entry){
      if(entry.isIntersecting && !observed){
        observed=true;
        counters.forEach(function(el){
          var target=parseFloat(el.getAttribute('data-target'));
          var isDecimal=String(target).indexOf('.')!==-1;
          var duration=1600;
          var start=performance.now();
          function update(now){
            var elapsed=now-start;
            var progress=Math.min(elapsed/duration,1);
            var eased=1-Math.pow(1-progress,3);
            var current=eased*target;
            if(isDecimal){el.textContent=current.toFixed(1);}
            else if(target>=100){el.textContent=Math.round(current)+'%';}
            else{el.textContent=Math.round(current);}
            if(progress<1) requestAnimationFrame(update);
          }
          requestAnimationFrame(update);
        });
      }
    });
  },{threshold:0.3});
  var statsSection=document.querySelector('.stats-bar');
  if(statsSection) observer.observe(statsSection);
})();
</script>
</body>
</html>
