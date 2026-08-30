<?php
/**
 * Contact — Viata Luxe Guesthouse
 * Full DB-driven template following the home.php pattern.
 * Bypasses section renderer, renders HTML directly.
 */

$page = get_page('contact');
if (!$page) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$nav       = get_navigation();
$settings  = settings_group('branding');
$contact   = settings_group('contact');

$phone     = setting('phone', '+27 15 781 0518');
$phone_mob = setting('phone_mobile', '+27 79 418 2077');
$email     = setting('email', 'info@viataluxe.com');
$address   = setting('address', '86 Nollie Bosman Street, Phalaborwa 1390');
$hours     = setting('hours', 'Mon – Sun: 07:00 – 21:00');
$map_query = setting('map_query', '86+Nollie+Bosman+Street+Phalaborwa');
$whatsapp  = setting('whatsapp', '+27794182077');

$meta_title       = $page['meta_title']       ?: 'Contact — Viata Luxe Guesthouse';
$meta_description = $page['meta_description']  ?: 'Get in touch with Viata Luxe Guesthouse. Phone, email, or visit us at 86 Nollie Bosman Street, Phalaborwa.';
$og_image         = $page['hero_image']        ?: '/Luxury Images/gallery-scenic/exterior-grey-cottages-red-doors.jpg';
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
    <meta property="og:title" content="<?= e($meta_title) ?>">
    <meta property="og:description" content="Get in touch with Viata Luxe Guesthouse. Phone, email, or visit us at 86 Nollie Bosman Street, Phalaborwa.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= url('/contact/') ?>">
    <meta property="og:image" content="<?= url($og_image) ?>">
    <meta property="og:site_name" content="Viata Luxe Guesthouse">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('/css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= url('/css/main.css') ?>">
    <style>
/* ——— Page hero ——— */
.page-hero{position:relative; min-height:52vh; display:grid; place-items:center; overflow:hidden; background:var(--navy)}
.page-hero__media{position:absolute; inset:0}
.page-hero__media img{width:100%; height:100%; object-fit:cover; object-position:center 40%}
.page-hero__veil{position:absolute; inset:0; background:linear-gradient(180deg, rgba(11,26,46,0.15) 0%, rgba(11,26,46,0.6) 100%)}
.page-hero__content{position:relative; z-index:2; text-align:center; padding:clamp(72px,14vh,160px) var(--gutter) 0; display:grid; gap:14px; color:var(--cream)}
.page-hero__kicker{font-size:11px; letter-spacing:0.22em; text-transform:uppercase; font-weight:700; color:var(--gold-300)}
.page-hero__title{font-family:var(--font-display); font-weight:300; font-size:clamp(32px,5vw,52px); line-height:1}
.page-hero__title em{font-style:italic; color:var(--gold-300)}
.page-hero__lead{color:rgba(248,246,241,0.78); max-width:52ch; font-size:15px; line-height:1.6; margin-inline:auto}

/* ——— Contact grid ——— */
.contact-grid{display:grid; grid-template-columns:1fr 1fr; gap:18px}
@media (max-width:880px){ .contact-grid{grid-template-columns:1fr} }

/* ——— Top info cards ——— */
.info-cards{display:grid; grid-template-columns:repeat(3,1fr); gap:14px}
@media (max-width:760px){ .info-cards{grid-template-columns:1fr} }
.info-card{background:var(--white); border:1px solid var(--line); border-radius:var(--radius-lg); padding:22px; display:grid; gap:6px; box-shadow:var(--shadow-soft); transition:transform var(--dur) var(--ease-spring), box-shadow var(--dur) var(--ease-out)}
.info-card:hover{transform:translateY(-2px); box-shadow:var(--shadow-medium)}
.info-card__label{font-size:10px; letter-spacing:0.22em; text-transform:uppercase; font-weight:700; color:var(--gold-600)}
.info-card__value{font-size:15px; font-weight:700; color:var(--navy)}
.info-card__value a{color:inherit; text-decoration:none}
.info-card__value a:hover{text-decoration:underline}
.info-card__detail{font-size:13px; color:var(--ink-70); line-height:1.55}

/* ——— Connect form ——— */
.connect-form{display:grid; gap:14px; background:var(--white); border:1px solid var(--line); border-radius:var(--radius-lg); padding:22px; box-shadow:var(--shadow-soft)}
.connect-form__msg{padding:12px 14px; border-radius:10px; font-size:13px; line-height:1.5}
.connect-form__msg--ok{background:rgba(122,140,98,0.12); border:1px solid rgba(122,140,98,0.22); color:var(--sage-600)}
.connect-form__msg--err{background:rgba(180,40,40,0.08); border:1px solid rgba(180,40,40,0.16); color:#8a2a2a}
.honey{position:absolute; left:-9999px; top:auto; width:1px; height:1px; overflow:hidden}

/* ——— Maps facade ——— */
.maps-facade{position:relative; border:1px solid var(--line); border-radius:var(--radius-lg); overflow:hidden; background:var(--ivory); cursor:pointer; min-height:360px; display:grid; place-items:center; text-align:center; padding:24px}
.maps-facade img{position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:0.22}

/* ——— NightsBridge card ——— */
.nb-card{background:var(--navy); color:var(--cream); border-radius:var(--radius-lg); padding:22px; display:grid; gap:8px}
.nb-card .kicker{color:var(--gold-300)}
.nb-card__title{font-family:var(--font-display); font-weight:300; font-size:22px; color:var(--cream)}
.nb-card__title em{font-style:italic; color:var(--gold-300)}

/* ——— Outline button ——— */
.btn--outline{display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:12px 22px; border:1px solid var(--navy); color:var(--navy); background:transparent; border-radius:999px; font-size:12px; letter-spacing:0.14em; text-transform:uppercase; font-weight:700; text-decoration:none; transition:background var(--dur) var(--ease-out), color var(--dur) var(--ease-out), border-color var(--dur) var(--ease-out)}
.btn--outline:hover{background:var(--navy); color:var(--cream)}

/* ——— Section heading ——— */
.section-heading{font-family:var(--font-display); font-weight:300; font-size:clamp(28px,4vw,44px); line-height:0.95; letter-spacing:-0.02em}
.section-heading em{font-style:italic; color:var(--gold-600)}
.subhead{color:var(--ink-70); max-width:58ch; font-size:15px; line-height:1.6}

/* ——— Nav brand lockup ——— */
.nav__brand{display:flex; align-items:center; gap:10px}
.nav__brand img{height:48px; width:auto; object-fit:contain}
.nav__brand-text{font-family:var(--font-display); font-weight:300; letter-spacing:0.14em; text-transform:uppercase; font-size:19px; color:var(--navy)}
.nav__brand-text span{color:var(--gold)}

/* ——— Call float ——— */
.call-float{position:fixed; bottom:24px; right:90px; z-index:50; width:56px; height:56px; border-radius:999px; background:var(--gold); color:var(--cream); display:grid; place-items:center; box-shadow:0 4px 24px rgba(140,116,52,0.35); transition:transform var(--dur-fast) var(--ease-spring), box-shadow var(--dur-fast) var(--ease-out); cursor:pointer; border:0; text-decoration:none}
.call-float:hover{transform:scale(1.08); box-shadow:0 6px 32px rgba(140,116,52,0.45)}
.call-float svg{width:28px; height:28px; fill:currentColor}
    </style>
    <script src="https://cdn.jsdelivr.net/npm/lenis@1.1.18/dist/lenis.min.js" integrity="sha384-tKsJDT6PlUI0pSBt9/sBKJluKgA19/a6mBrDsZaXotLB4ZYfMGM6xt6/WgGpYhTm" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha384-g4NTh/Iv5PPU4xPyhEWqPcwtNXOvdaDI8LLnyYfyNZOjKJeYQyjzQ9X5275eBjpt" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" integrity="sha384-Z3REaz79l2IaAZqJsSABtTbhjgOUYyV3p90XNnAPCSHg3EMTz1fouunq9WZRtj3d" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/lucide@0.344.0/dist/umd/lucide.min.js" defer></script>
</head>
<body class="arrival">
<a class="skip-link" href="#main">Skip to content</a>
<div id="preloader" class="preloader" aria-hidden="true">
  <div class="preloader__inner">
    <span class="preloader__mark">Viata Luxe</span>
    <div class="preloader__rule"></div>
    <div class="preloader__sub">Contact · 86 Nollie Bosman</div>
    <div class="preloader__bar"><i></i></div>
  </div>
</div>
<div class="grain" aria-hidden="true"></div>

<nav class="nav" aria-label="Primary">
  <div class="nav__inner">
    <a class="nav__brand" href="<?= url('/') ?>" aria-label="Viata Luxe Guesthouse — Home">
      <img src="<?= e(url(setting('logo_dark', '/Luxury Images/logos/logo-kruger-national-park.png'))) ?>" alt="" width="136" height="135" style="height:48px;width:auto;display:block" fetchpriority="high" decoding="async">
      <span class="nav__brand-text">Viata <span>Luxe</span></span>
    </a>
    <div class="nav__links" role="navigation">
      <a href="<?= url('/') ?>">Home</a>
      <a href="<?= url('/accomodation/') ?>">Accommodation</a>
      <a href="<?= url('/safari/') ?>">Safari</a>
      <a href="<?= url('/gallery/') ?>">Gallery</a>
      <a class="is-active" href="<?= url('/contact/') ?>" aria-current="page">Contact</a>
    </div>
    <a class="nav__cta nav__cta--desktop" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener">Book Now</a>
    <a class="nav__admin" href="<?= e(url('/admin/login')) ?>" rel="nofollow" aria-label="Admin login">Admin</a>
    <button id="navToggle" class="nav__toggle" aria-expanded="false" aria-controls="mobileDrawer" aria-label="Open menu"><span></span></button>
  </div>
</nav>
<div id="mobileDrawer" class="mobile-drawer" hidden>
  <a href="<?= url('/') ?>">Home</a>
  <a href="<?= url('/accomodation/') ?>">Accommodation</a>
  <a href="<?= url('/safari/') ?>">Safari</a>
  <a href="<?= url('/gallery/') ?>">Gallery</a>
  <a href="<?= url('/contact/') ?>" aria-current="page">Contact</a>
  <a class="cta" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener">Book Now</a>
  <a href="<?= e(url('/admin/login')) ?>" rel="nofollow" class="cta cta--outline">Admin</a>
</div>
<script>
  document.getElementById('mobileDrawer').hidden=false;
  document.getElementById('mobileDrawer').style.display='none';
  var _t=document.getElementById('navToggle'),_d=document.getElementById('mobileDrawer');
  _t.addEventListener('click',function(){ _d.style.display=_d.classList.contains('is-open')?'grid':'none'; });
  new MutationObserver(function(){ _d.style.display=_d.classList.contains('is-open')?'grid':'none'; }).observe(_d,{attributes:true, attributeFilter:['class']});
</script>

<!-- PAGE HERO -->
<section class="page-hero">
  <div class="page-hero__media">
    <img src="<?= url($page['hero_image'] ?: '/Luxury Images/gallery-scenic/exterior-grey-cottages-red-doors.jpg') ?>" alt="" width="1920" height="800" fetchpriority="high" decoding="async">
  </div>
  <div class="page-hero__veil"></div>
  <div class="page-hero__content">
    <p class="page-hero__kicker reveal"><?= e($page['hero_kicker'] ?: 'Contact — Reach Us Anytime') ?></p>
    <h1 class="page-hero__title reveal">Contact <em>Us</em></h1>
    <p class="page-hero__lead reveal"><?= e($page['hero_lead'] ?: 'We would love to hear from you. Reach us by phone, email, or visit us in person.') ?></p>
  </div>
</section>

<main id="main" class="container" style="padding-bottom: var(--section-pad)">

  <!-- TOP INFO CARDS — Phone / Email / Address -->
  <section class="info-cards reveal" style="margin-top:28px">
    <div class="info-card">
      <div class="info-card__label">Phone</div>
      <p class="info-card__value">
        <a href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>"><?= e($phone) ?></a>
        <span style="color:var(--ink-55)"> | </span>
        <a href="tel:<?= e(preg_replace('/\s+/', '', $phone_mob)) ?>"><?= e($phone_mob) ?></a>
      </p>
      <a class="link" href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>" style="margin-top:4px; display:inline-block">Call Tel</a> · <a class="link" href="tel:<?= e(preg_replace('/\s+/', '', $phone_mob)) ?>">Call Mobile</a>
    </div>
    <div class="info-card">
      <div class="info-card__label">Email</div>
      <p class="info-card__value"><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></p>
      <a class="link" href="mailto:<?= e($email) ?>" style="margin-top:4px; display:inline-block">Send Email</a>
    </div>
    <div class="info-card">
      <div class="info-card__label">Address</div>
      <p class="info-card__detail"><?= nl2br(e($address)) ?></p>
      <a class="link" href="https://www.google.com/maps/search/<?= e($map_query) ?>" target="_blank" rel="noopener" style="margin-top:4px; display:inline-block">Open in Maps →</a>
    </div>
  </section>

  <!-- CONTACT GRID — Form + Map/Booking -->
  <section class="contact-grid" style="margin-top:18px">
    <div class="reveal">
      <h2 style="font-family:var(--font-display); font-weight:300; font-size:22px">Send us a message</h2>
      <p class="caption" style="margin-top:6px; max-width:52ch">Please enable JavaScript in your browser to complete this form.</p>
      <form id="connectForm" class="connect-form" style="margin-top:14px" novalidate>
        <div class="honey" aria-hidden="true"><label for="website">Website (leave blank)</label><input id="website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
        <div class="field"><label class="field__label" for="fName">Name *</label><input class="field__input" id="fName" name="name" required placeholder="Your name"></div>
        <div class="field"><label class="field__label" for="fEmail">Email *</label><input class="field__input" id="fEmail" name="email" type="email" required placeholder="you@example.com"></div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px">
          <div class="field"><label class="field__label" for="fArrival">Arrival *</label><input class="field__input" id="fArrival" name="arrival" type="date" required></div>
          <div class="field"><label class="field__label" for="fDeparture">Departure *</label><input class="field__input" id="fDeparture" name="departure" type="date" required></div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px">
          <div class="field"><label class="field__label" for="fPhone">Phone</label><input class="field__input" id="fPhone" name="phone" placeholder="+27 ..."></div>
          <div class="field"><label class="field__label" for="fGuests">Guests</label><select class="field__input" id="fGuests" name="guests"><option value="2 guests">2 guests</option><option value="3 guests">3 guests</option><option value="4 guests">4 guests</option><option value="5 guests">5 guests</option><option value="6 guests">6 guests</option></select></div>
        </div>
        <div class="field"><label class="field__label" for="fNotes">Comment or Message</label><textarea class="field__input" id="fNotes" name="notes" rows="4" placeholder="Comment or Message"></textarea></div>
        <button type="submit" class="btn btn--navy" style="width:100%; justify-content:center">Submit <span style="font-weight:400; opacity:0.7">(mailto fallback)</span></button>
        <div id="formMsg" class="connect-form__msg" hidden></div>
      </form>
    </div>

    <div class="reveal reveal--delay-1" style="display:grid; gap:14px; align-content:start">
      <!-- Map facade -->
      <div class="maps-facade" id="mapsFacade" tabindex="0" role="button" aria-label="Load Google Maps">
        <img src="<?= url($page['hero_image'] ?: '/Luxury Images/gallery-scenic/exterior-grey-cottages-red-doors.jpg') ?>" alt="Map poster — exterior cottages" loading="lazy" decoding="async">
        <div style="position:relative; z-index:2; display:grid; gap:10px; place-items:center; padding:18px">
          <div style="font-family:var(--font-display); font-weight:300; font-size:20px; color:var(--navy)"><?= e($address) ?></div>
          <div style="font-size:12px; color:var(--ink-55)">Phalaborwa 1390 — Corner 13 Prinsloo &amp; Nollie Bosman</div>
          <button class="maps-facade__btn" id="mapsBtn">Load Map — Google Maps</button>
        </div>
      </div>
      <div id="mapsFrame" hidden style="border:1px solid var(--line); border-radius:var(--radius-lg); overflow:hidden; height:360px; background:var(--ivory)">
        <iframe src="https://www.google.com/maps?q=<?= e($map_query) ?>&z=16&output=embed" width="100%" height="100%" style="border:0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Google Maps — <?= e($address) ?>"></iframe>
      </div>

      <!-- NightsBridge card -->
      <div class="nb-card">
        <div class="kicker">NightsBridge — Instant book</div>
        <h3 class="nb-card__title">Book direct — <em>38331</em></h3>
        <p style="color:rgba(248,246,241,0.7); font-size:13px; margin-top:4px">Book direct via NightsBridge — instant confirmation.</p>
        <div style="margin-top:12px; border:1px solid rgba(248,246,241,0.16); border-radius:12px; overflow:hidden; height:320px; background:var(--cream)">
          <iframe src="https://book.nightsbridge.com/38331" title="NightsBridge — Viata Luxe 38331" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" style="width:100%; height:100%; border:0"></iframe>
        </div>
        <a href="https://book.nightsbridge.com/38331" target="_blank" rel="noopener" class="btn btn--gold" style="width:100%; justify-content:center; margin-top:12px">Open NightsBridge — 38331</a>
      </div>
    </div>
  </section>

  <!-- EMERGENCY / HOURS -->
  <section class="reveal" style="margin-top:18px">
    <div class="card card__pad">
      <div class="micro">Business — via Home context</div>
      <h3 style="font-family:var(--font-display); font-weight:300; margin-top:6px">Minutes to Kruger</h3>
      <p style="color:var(--ink-70); margin-top:6px; font-size:14px">We Would Love To Hear From You — <?= e($phone) ?> Tel | <?= e($phone_mob) ?> Mobile. Host on arrival, self-catering, secure parking.</p>
    </div>
  </section>

</main>

<footer class="footer">
  <div class="footer__inner">
    <div class="footer__top">
      <div class="footer__brand">Viata <span>Luxe</span> · Phalaborwa</div>
      <nav class="footer__nav" aria-label="Footer">
        <a href="<?= url('/') ?>">Home</a>
        <a href="<?= url('/accomodation/') ?>">Accommodation</a>
        <a href="<?= url('/safari/') ?>">Safari</a>
        <a href="<?= url('/gallery/') ?>">Gallery</a>
        <a href="<?= url('/contact/') ?>" aria-current="page">Contact</a>
      </nav>
    </div>
    <div class="footer__legal">
      <span>&copy; 2026 Viata Luxe Guesthouse. <?= e($address) ?>.</span>
      <span><?= e($phone) ?> · <?= e($email) ?></span>
      <a class="footer__admin-btn" href="<?= e(url('/admin/login')) ?>" rel="nofollow" aria-label="Admin login">✳ Admin</a>
    </div>
    <div class="footer__logos">
      <img src="<?= e(url(setting('logo_dark', '/Luxury Images/logos/logo-kruger-national-park.png'))) ?>" alt="Viata Luxe Guesthouse" loading="lazy" decoding="async">
    </div>
  </div>
</footer>

<div id="lightbox" class="lightbox" aria-hidden="true"><button class="lightbox__close" aria-label="Close">✕</button><img alt=""><span class="lightbox__counter"></span><button class="lightbox__nav lightbox__nav--prev" aria-label="Previous">‹</button><button class="lightbox__nav lightbox__nav--next" aria-label="Next">›</button></div>
<a href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>" class="call-float" aria-label="Call Viata Luxe"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.01-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.07 21 3 13.93 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.1.31.03.66-.25 1.02l-2.2 2.2z"/></svg></a>
<a href="https://wa.me/<?= e(preg_replace('/[^0-9]/', '', $whatsapp)) ?>?text=Hi%20Viata%20Luxe%2C%20I%27d%20like%20to%20enquire%20about%20availability." target="_blank" rel="noopener" class="wa-float" aria-label="Chat on WhatsApp"><svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></a>
<script src="<?= url('/js/main.js') ?>"></script>
<script>
var mf=document.getElementById('mapsFacade'), mframe=document.getElementById('mapsFrame');
function loadMaps(){ if(!mf||!mframe) return; mf.style.display='none'; mframe.hidden=false; if(window.ScrollTrigger) ScrollTrigger.refresh(); }
if(mf){ mf.addEventListener('click', loadMaps); mf.addEventListener('keydown', function(e){ if(e.key==='Enter'||e.key===' '){ e.preventDefault(); loadMaps(); } }); var mb=document.getElementById('mapsBtn'); if(mb) mb.addEventListener('click', function(e){ e.stopPropagation(); loadMaps(); }); }
var form=document.getElementById('connectForm');
if(form){
  var hp=document.getElementById('website'), msg=document.getElementById('formMsg');
  function showMsg(t,ok){ if(!msg) return; msg.hidden=false; msg.textContent=t; msg.className='connect-form__msg '+(ok?'connect-form__msg--ok':'connect-form__msg--err'); }
  var today=new Date().toISOString().slice(0,10);
  var arr=document.getElementById('fArrival'), dep=document.getElementById('fDeparture');
  if(arr) arr.min=today; if(dep) dep.min=today;
  if(arr&&dep) arr.addEventListener('change', function(){ dep.min=arr.value||today; if(dep.value && dep.value <= arr.value){ dep.value=''; showMsg('Departure must be after arrival.', false); }});
  form.addEventListener('submit', function(e){
    if(hp && hp.value.trim()!==''){ e.preventDefault(); showMsg('Spam detected — not sent.', false); return; }
    if(!form.checkValidity()){ showMsg('Please fill required: Name, Email, Arrival, Departure.', false); return; }
    var a=arr.value, d=dep.value;
    if(a && d && d <= a){ e.preventDefault(); showMsg('Departure must be after arrival.', false); return; }
    e.preventDefault();
    var name=(document.getElementById('fName')||{}).value||'', email=(document.getElementById('fEmail')||{}).value||'', phone=(document.getElementById('fPhone')||{}).value||'', guests=(document.getElementById('fGuests')||{}).value||'', notes=(document.getElementById('fNotes')||{}).value||'';
    var subject=encodeURIComponent('Viata Luxe enquiry — '+name+' · '+a+' → '+d);
    var body=encodeURIComponent('Name: '+name+'\nEmail: '+email+'\nPhone: '+phone+'\nGuests: '+guests+'\nArrival: '+a+'\nDeparture: '+d+'\nNotes: '+notes+'\n\n— via viata luxe contact form (mailto fallback)');
    var mailto='mailto:<?= e($email) ?>?subject='+subject+'&body='+body;
    showMsg('Opening mail app… If nothing opens, email <?= e($email) ?>. NightsBridge above is instant.', true);
    setTimeout(function(){ window.location.href=mailto; }, 400);
  });
}
</script>
<script>document.addEventListener('DOMContentLoaded',function(){if(typeof lucide!=='undefined')lucide.createIcons();});</script>
</body>
</html>
