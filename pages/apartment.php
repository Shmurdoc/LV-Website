<?php
/**
 * Single Apartment Detail — Viata Luxe Guesthouse
 * Dynamic page based on URL slug. Matches reference layout:
 * page-head → room card → brand-mark → info cards + testimonial → gallery extras → book CTA.
 */

// Determine which apartment from the URI
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\');
$baseDir = str_replace('\\', '/', dirname(__DIR__));
$basePath = '/' . ltrim(str_replace($docRoot, '', $baseDir), '/');
if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
$slug = ltrim(rtrim($uri, '/'), '/');
$apartment = get_apartment($slug);

if (!$apartment) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    exit;
}

$images = get_apartment_images($apartment['id']);
$amenities = get_apartment_amenities($apartment['id']);
$testimonials = get_apartment_testimonials($apartment['id']);
$allTestimonial = get_featured_testimonials();

$page = [
    'id' => $apartment['page_id'],
    'slug' => $apartment['slug'],
    'title' => $apartment['name'],
    'meta_title' => $apartment['meta_title'] ?? $apartment['name'] . ' — Viata Luxe Guesthouse',
    'meta_description' => $apartment['meta_description'] ?? ($apartment['subtitle'] . '. ' . $apartment['description']),
    'og_image' => $apartment['og_image'] ?? $apartment['hero_image'],
];

$nav = $nav ?? get_navigation();
$settings = $settings ?? settings_group('branding');
$contact = $contact ?? settings_group('contact');
$booking = settings_group('booking');

// Build amenity notice string from DB
$amenityNames = array_map(fn($a) => e($a['amenity_name']), $amenities);
$amenityNotice = implode(' • ', $amenityNames);

// Pick testimonial: apartment-specific first, then fallback to featured
$aptReview = array_filter($testimonials, fn($t) => $t['is_published']);
$review = !empty($aptReview) ? reset($aptReview) : (!empty($allTestimonial) ? reset($allTestimonial) : null);

// Grade number from sort_order (1–4)
$grade = (int)($apartment['sort_order'] ?? $apartment['id']);

require __DIR__ . '/../templates/header.php';
?>

<header class="page-head">
  <div class="page-head__inner">
    <div class="kicker reveal">Accommodation — <?= e($apartment['name']) ?><?php if (!empty($apartment['room_size_m2'])): ?> · <?= e($apartment['room_size_m2']) ?> m²<?php endif; ?><?php if (!empty($apartment['max_guests'])): ?> · Sleeps <?= e($apartment['max_guests']) ?><?php endif; ?></div>
    <h1 class="page-head__title reveal"><?= e($apartment['name']) ?><br><em><?= e($apartment['subtitle'] ?? 'Luxe.') ?></em></h1>
    <p class="page-head__lead reveal"><?= e($apartment['description']) ?></p>
    <div class="page-head__meta reveal">
      <?php if (!empty($apartment['room_size_m2'])): ?><span class="chip"><?= e($apartment['room_size_m2']) ?> m²</span><?php endif; ?>
      <?php if (!empty($apartment['beds_description'])): ?><span class="chip"><?= e($apartment['beds_description']) ?></span><?php endif; ?>
      <?php if (!empty($apartment['max_guests'])): ?><span class="chip">Max <?= e($apartment['max_guests']) ?> guests</span><?php endif; ?>
      <span class="chip">City view · Private bathroom</span>
    </div>
  </div>
</header>

<main id="main" class="container" style="padding-bottom: var(--section-pad)">
  <div class="rooms">
    <article class="room reveal" data-grade="<?= $grade ?>" id="<?= e($slug) ?>">
      <div class="room__media" data-lightbox href="<?= url($apartment['hero_image']) ?>">
        <img src="<?= url($apartment['hero_image']) ?>" alt="<?= e($apartment['name']) ?>" width="1200" height="800" fetchpriority="high" decoding="async">
        <div class="room__grade room__grade--<?= $grade ?>"></div>
        <span class="room__badge"><?= e($apartment['name']) ?><?php if (!empty($apartment['room_size_m2'])): ?> · <?= e($apartment['room_size_m2']) ?> m²<?php endif; ?></span>
      </div>
      <div class="room__body">
        <div class="room__kicker">0<?= $grade ?> — <?= e($apartment['name']) ?><?php if (!empty($apartment['max_guests'])): ?> · Sleeps <?= e($apartment['max_guests']) ?><?php endif; ?></div>
        <h2 class="room__title"><?= e($apartment['subtitle'] ?? $apartment['name']) ?></h2>
        <div class="room__specs">
          <?php if (!empty($apartment['room_size_m2'])): ?><span class="spec"><strong><?= e($apartment['room_size_m2']) ?> m²</strong></span><?php endif; ?>
          <?php if (!empty($apartment['max_guests'])): ?><span class="spec">Sleeps <strong><?= e($apartment['max_guests']) ?></strong></span><?php endif; ?>
          <?php if (!empty($apartment['beds_description'])): ?><span class="spec"><?= e($apartment['beds_description']) ?></span><?php endif; ?>
          <span class="spec">City Views</span>
        </div>
        <p class="room__copy"><?= e($apartment['description']) ?></p>
        <div class="room__price"><strong>From <?= format_price((float)$apartment['price_per_night']) ?></strong><span>per night · 0–7 days cancellation 100%</span></div>
        <?php if ($amenityNotice): ?>
        <div class="room__notice">Additional Amenities · <?= e($amenityNotice) ?></div>
        <?php endif; ?>
        <?php if (count($images) > 1): ?>
        <div class="room__gallery">
          <?php foreach (array_slice($images, 1, 3) as $img): ?>
          <img src="<?= e(image_url($img['image_path'])) ?>" alt="<?= e($img['alt_text'] ?? $apartment['name']) ?>" data-lightbox href="<?= e(image_url($img['image_path'])) ?>" loading="lazy" decoding="async">
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </article>
  </div>

  <div class="brand-mark reveal" aria-hidden="true"><img src="<?= url('Luxury Images/logos/logo-viata-full-dark.png') ?>" alt=""></div>

  <!-- Info cards + testimonial -->
  <section class="reveal" style="margin-top:22px; display:grid; gap:18px">
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:14px">
      <div class="card card__pad"><div class="eyebrow">City Views</div><h3 style="font-family:var(--font-display); font-weight:300; margin-top:6px">Breathtaking Phalaborwa</h3><p style="color:var(--ink-70); margin-top:8px; font-size:14px">Every window frames acacia — especially enchanting at night.</p></div>
      <div class="card card__pad"><div class="eyebrow">Tours</div><h3 style="font-family:var(--font-display); font-weight:300; margin-top:6px">Explore local culture</h3><p style="color:var(--ink-70); margin-top:8px; font-size:14px">Curated tours immersing you in local culture and stunning landscapes — Kedibone Safari minutes away.</p></div>
      <div class="card card__pad"><div class="eyebrow">Drinks &amp; Food</div><h3 style="font-family:var(--font-display); font-weight:300; margin-top:6px">Gourmet delivered</h3><p style="color:var(--ink-70); margin-top:8px; font-size:14px">Breakfast on request + affiliated exclusive restaurants, convenient terrace dining — indulgent, relaxed.</p></div>
    </div>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px">
      <div class="card card__pad">
        <?php foreach ($amenities as $i => $amenity): ?>
        <div class="micro"<?php if ($i > 0): ?> style="margin-top:14px"<?php endif; ?>><?= e($amenity['amenity_name']) ?></div>
        <p style="font-size:14px; color:var(--ink-70); margin-top:6px"><?= e($amenity['amenity_name']) ?> available in this apartment.</p>
        <?php endforeach; ?>
      </div>
      <div class="card card__pad" style="background:var(--ivory)">
        <?php if ($review): ?>
        <blockquote style="border-left:2px solid var(--gold); padding-left:16px; color:var(--ink-70); font-style:italic">"<?= e($review['review_text']) ?>"</blockquote>
        <div style="font-size:11px; letter-spacing:0.14em; text-transform:uppercase; font-weight:800; color:var(--ink-55); margin-top:8px"><?= e($review['reviewer_name']) ?></div>
        <?php endif; ?>
        <div class="rate-table" style="margin-top:16px">
          <table>
            <thead><tr><th>Policy</th><th>Detail</th></tr></thead>
            <tbody>
              <tr><td><strong>Cancelation</strong></td><td>0–7 days within stay: 100% charged</td></tr>
              <tr><td><strong>Bedding</strong></td><td><?= e($apartment['bedrooms'] ?? 1) ?> bedroom <?= e($apartment['beds_description'] ?? 'queen') ?> · Max <?= e($apartment['max_guests'] ?? 2) ?> · <?= e($apartment['room_size_m2'] ?? 13) ?> m²</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <!-- Gallery extras -->
  <?php if (count($images) > 2): ?>
  <section class="reveal" style="margin-top:18px">
    <div class="kicker">Gallery — <?= e($apartment['name']) ?> extras</div>
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-top:12px">
      <?php foreach (array_slice($images, 0, 3) as $img): ?>
      <img src="<?= e(image_url($img['image_path'])) ?>" alt="<?= e($img['alt_text'] ?? $apartment['name']) ?>" style="aspect-ratio:4/3; object-fit:cover; border-radius:10px; border:1px solid var(--line)" data-lightbox href="<?= e(image_url($img['image_path'])) ?>" loading="lazy" decoding="async">
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- Booking CTA -->
  <section class="book reveal" style="margin-top:22px; border-radius:22px; overflow:hidden">
    <div style="padding: clamp(24px, 4vw, 36px); display:grid; grid-template-columns: 1.2fr 0.8fr; gap:24px; align-items:center">
      <div>
        <h3 style="font-family:var(--font-display);font-weight:300;font-size:28px;line-height:0.95;color:var(--cream)"><?= e($apartment['name']) ?> awaits.<br><em style="color:var(--gold-300);font-style:italic">Book direct.</em></h3>
        <p style="color:rgba(248,246,241,0.7);max-width:52ch;margin-top:8px"><?= e($apartment['room_size_m2'] ?? 13) ?> m² · <?= e($apartment['beds_description'] ?? 'Queen 157cm') ?> · City views · Host on arrival</p>
      </div>
      <div>
        <a class="btn btn--gold" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener" style="width:100%;justify-content:center">Book <?= e($apartment['name']) ?> — NightsBridge</a>
        <p class="small" style="color:rgba(248,246,241,0.6);text-align:center;margin-top:8px">Also via <a href="https://www.booking.com/hotel/za/viata-luxe-guesthouse-phalaborwa.en-gb.html" target="_blank" rel="noopener" style="text-decoration:underline">Booking.com</a></p>
      </div>
    </div>
  </section>
</main>

<?php
require __DIR__ . '/../templates/footer.php';