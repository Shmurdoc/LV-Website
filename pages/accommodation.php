<?php
/**
 * Accommodation Overview — Viata Luxe Guesthouse
 * Matches reference HTML design: page-head header, room cards, amenities card, book CTA.
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
$db = Database::get();

// Taxonomy categories for filter tabs
require_once __DIR__ . '/../admin/includes/taxonomy.php';
$aptCategories = get_public_categories('apartment');

// Build apartment-to-category mapping
$aptCategoryMap = [];
foreach ($apartments as $apt) {
    $stmt = $db->prepare('SELECT pc.slug FROM public_categories pc WHERE pc.id = :cat_id');
    $stmt->execute(['cat_id' => $apt['category_id'] ?? 0]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $aptCategoryMap[$apt['id']] = $row ? $row['slug'] : '';
}

// Compute dynamic min price for chips
$minPrice = null;
foreach ($apartments as $ap) {
    $p = (float)($ap['price_from'] ?? $ap['price_per_night'] ?? 0);
    if ($p > 0) {
        $minPrice = $minPrice === null ? $p : min($minPrice, $p);
    }
}
$minPriceDisplay = $minPrice ? 'From R' . number_format($minPrice, 0) : 'From R950';

// Header handles meta/OG/canonical/fonts/tokens/preloader/grain/nav
require __DIR__ . '/../templates/header.php';
?>

<!-- ====== HERO ====== -->
<section class="page-hero">
  <div class="page-hero__media">
    <?php $accHeroSrc = !empty($page['hero_image']) ? $page['hero_image'] : '/Luxury Images/apartments-classic-4/apt4-bedroom-main-view.jpg'; ?>
    <img src="<?= e(url($accHeroSrc)) ?>" alt="Deluxe apartment — elegant interior with city views" width="1920" height="1080" fetchpriority="high" decoding="async">
  </div>
  <div class="page-hero__veil"></div>
  <div class="page-hero__content">
    <p class="page-hero__kicker">Accommodation — 4 Apartments · Viata Luxe</p>
    <h1 class="page-hero__title">Four apartments.<br><em>One standard: luxe.</em></h1>
    <p class="page-hero__lead">One Bedroom Apartment · 5 Sleeper Apartment — both with <strong>City Views</strong>, <strong>Tours</strong>, <strong>Drinks &amp; Food</strong>, <strong>Wifi</strong>, <strong>DSTV</strong>, <strong>Spacious Rooms</strong>. All apartments feature City Views, Tours, Drinks &amp; Food, Wifi, DSTV, and Spacious Rooms.</p>
    <div class="page-hero__meta">
      <span class="chip">13 m² · Queen beds</span>
      <span class="chip">Max 2–6 guests</span>
      <span class="chip"><?= e($minPriceDisplay) ?></span>
      <a class="btn btn--primary" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener">Book Now</a>
    </div>
  </div>
</section>

<main id="main-content" class="container" style="padding-bottom: var(--section-pad)">

  <!-- ====== CATEGORY FILTER TABS ====== -->
  <?php if (!empty($aptCategories)): ?>
  <div class="filter-bar" data-category-filter="apartment">
    <button data-cat="all" class="filter-btn active">All</button>
    <?php foreach ($aptCategories as $cat): ?>
      <button data-cat="<?= e($cat['slug']) ?>" class="filter-btn"><?= e($cat['name']) ?></button>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- ====== ROOM CARDS (DB-driven) ====== -->
  <section class="rooms" style="margin-top:14px">

    <?php
    // Fetch all apartment amenities grouped by apartment_id
    try {
        $allAptAmenities = $db->query("
            SELECT apartment_id, amenity_name, amenity_icon
            FROM apartment_amenities
            WHERE deleted_at IS NULL
            ORDER BY apartment_id ASC, sort_order ASC
        ")->fetchAll(PDO::FETCH_ASSOC);
        $amenitiesByApt = [];
        foreach ($allAptAmenities as $am) {
            $amenitiesByApt[(int)$am['apartment_id']][] = $am;
        }
    } catch (Throwable $e) {
        $amenitiesByApt = [];
    }

    $amenityIconFallback = [
        'wifi' => '⬢', 'tv' => '✦', 'kitchen' => '◐', 'car' => '⚑',
        'hot-tub' => '♡', 'snowflake' => '☾', 'balcony' => '◉',
        'bath' => '≋', 'dishwasher' => '◐', 'patio' => '◉',
        'mountain' => '◎', 'bed' => '☾', 'droplets' => '≋',
    ];

    $fallbackImages = [
        'bachelor-apartment'   => '/Luxury Images/apartments-classic-1/apt1-kitchen-dining-main.jpg',
        'classic-apartment-2'  => '/Luxury Images/apartments-classic-2/apt2-bedroom-main-view.jpg',
        'comfort-apartment-3'  => '/Luxury Images/apartments-classic-3/apt3-bedroom-main-view.jpg',
        'deluxe-apartment-4'   => '/Luxury Images/apartments-classic-4/apt4-bedroom-main-view.jpg',
    ];

    foreach ($apartments as $idx => $apt):
        $aptId      = (int)$apt['id'];
        $aptSlug    = $apt['slug'];
        $aptName    = $apt['name'];
        $aptDesc    = $apt['description'] ?? '';
        $aptPrice   = (float)($apt['price_from'] ?? $apt['price_per_night'] ?? 950);
        $aptGuests  = (int)($apt['max_guests'] ?? 2);
        $aptSize    = (float)($apt['room_size_m2'] ?? 13);
        $aptBeds    = $apt['beds_description'] ?? 'Queen 157cm';
        $aptGrade   = (int)$apt['sort_order'];

        // Image: hero_image from DB, fallback to known paths
        $firstImg = $apt['hero_image'] ?? '';
        if (empty($firstImg)) {
            $firstImg = $fallbackImages[$aptSlug] ?? '/Luxury Images/apartments-classic-1/apt1-kitchen-dining-main.jpg';
        }
        $imgUrl = url($firstImg);
        $imgAlt = e($apt['tagline'] ?? $aptName);

        // Category slug for filtering
        $catSlug = $aptCategoryMap[$aptId] ?? '';

        // Amenities from DB (up to 4 per card)
        $aptAmenities = array_slice($amenitiesByApt[$aptId] ?? [], 0, 4);

        // Alternate layout: even indices reverse
        $reverse = ($idx % 2 === 1);

        // Badge & kicker
        $badge = $aptName . ' · Sleeps ' . $aptGuests . ' · ' . $aptSize . ' m²';
        $kicker = str_pad($aptGrade, 2, '0', STR_PAD_LEFT) . ' — ' . $aptName;

        // Specs
        $specs = [
            '<strong>' . $aptSize . ' m²</strong>',
            'Sleeps <strong>' . $aptGuests . '</strong>',
            $aptBeds,
            'City Views',
        ];

        // Price
        $price = '<strong>From R' . number_format($aptPrice, 0) . '</strong><span>per night · Cancellation 0–7 days 100%</span>';
    ?>
    <article class="room<?= $reverse ? ' room--reverse' : '' ?> reveal" data-grade="<?= $aptGrade ?>" data-category="<?= e($catSlug) ?>">
      <div class="room__media" data-lightbox href="<?= e($imgUrl) ?>">
        <img src="<?= e($imgUrl) ?>" alt="<?= e($imgAlt) ?>" width="1200" height="800" loading="lazy" decoding="async">
        <div class="room__grade room__grade--<?= $aptGrade ?>"></div>
        <span class="room__badge"><?= e($badge) ?></span>
      </div>
      <div class="room__body">
        <div class="room__kicker"><?= e($kicker) ?></div>
        <h2 class="room__title"><?= e($aptName) ?></h2>
        <div class="room__specs">
          <?php foreach ($specs as $spec): ?>
            <span class="spec"><?= $spec ?></span>
          <?php endforeach; ?>
        </div>
        <p class="room__copy"><?= e($aptDesc) ?></p>
        <div class="room__price"><?= $price ?></div>
        <?php if (!empty($aptAmenities)): ?>
          <div class="amenities">
            <?php foreach ($aptAmenities as $amenity): ?>
              <?php $icon = $amenityIconFallback[$amenity['amenity_icon']] ?? '◉'; ?>
              <div class="amenity">
                <span class="amenity__icon"><?= $icon ?></span>
                <span class="amenity__text"><strong><?= e($amenity['amenity_name']) ?></strong></span>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <div class="room__actions">
          <a class="btn btn--navy" href="<?= e(url("/{$aptSlug}/")) ?>">View <?= e($aptName) ?> detail →</a>
          <a class="link" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener">Book Now</a>
        </div>
      </div>
    </article>
    <?php endforeach; ?>

  </section>

  <!-- ====== AMENITIES CARD (DB-driven) ====== -->
  <?php
  // Fetch unique amenities across all apartments from DB
  $amenityIconMap = [
      'wifi' => '⬢', 'tv' => '✦', 'kitchen' => '◐', 'car' => '⚑',
      'hot-tub' => '♡', 'snowflake' => '☾', 'balcony' => '◉',
      'bath' => '≋', 'dishwasher' => '◐', 'patio' => '◉',
      'mountain' => '◎', 'bed' => '☾',
  ];
  try {
      $allAmenities = $db->query("
          SELECT amenity_name, amenity_icon, MIN(sort_order) AS min_sort
          FROM apartment_amenities
          WHERE deleted_at IS NULL
          GROUP BY amenity_name, amenity_icon
          ORDER BY min_sort ASC
      ")->fetchAll(PDO::FETCH_ASSOC);
  } catch (Throwable $e) {
      $allAmenities = [];
  }
  ?>
  <section class="reveal" style="margin-top:28px; background:var(--white); border:1px solid var(--line); border-radius:var(--radius-lg); padding: clamp(18px, 3vw, 28px); display:grid; gap:16px">
    <div class="kicker">Amenities</div>
    <div class="amenities" style="max-width:100%">
      <?php if (!empty($allAmenities)): ?>
        <?php foreach ($allAmenities as $am): ?>
          <?php $iconChar = $amenityIconMap[$am['amenity_icon']] ?? '◉'; ?>
          <div class="amenity"><span class="amenity__icon"><?= $iconChar ?></span><span class="amenity__text"><strong><?= e($am['amenity_name']) ?></strong></span></div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="amenity"><span class="amenity__icon">⬢</span><span class="amenity__text"><strong>Wifi + DSTV</strong><br>Complimentary WiFi · Flat-screen DSTV</span></div>
        <div class="amenity"><span class="amenity__icon">♡</span><span class="amenity__text"><strong>Spacious</strong><br>Large en-suite, comfortable, curated</span></div>
      <?php endif; ?>
    </div>
    <div class="rate-table" style="margin-top:8px">
      <table>
        <thead><tr><th>Policy</th><th>Detail</th></tr></thead>
        <tbody>
          <tr><td><strong>Cancelation Policy</strong></td><td>0–7 days within stay: 100% charged </td></tr>
          <tr><td><strong>Check</strong></td><td>Wifi complimentary in room · DSTV flat-screen · Spacious en-suite</td></tr>
        </tbody>
      </table>
    </div>
  </section>

  <!-- ====== 5 SLEEPER CARD ====== -->
  <section class="reveal" style="margin-top:18px; background:var(--ivory); border:1px solid var(--line); border-radius:12px; padding:16px">
    <div style="font-size:11px; letter-spacing:0.14em; text-transform:uppercase; font-weight:800; color:var(--ink-55)">5 Sleeper Apartment</div>
    <p style="font-size:13px; color:var(--ink-70); margin-top:6px; max-width:68ch"><strong>5 Sleeper:</strong> 1 bedroom queen-sized bed · 1 bedroom 3 single beds · Maximum 2 guests · Room size: 13 m².</p>
  </section>

  <!-- ====== BOOK CTA ====== -->
  <section class="book reveal" style="margin-top:22px; border-radius:22px; overflow:hidden">
    <div class="grid-2col" style="padding: clamp(24px, 4vw, 36px); gap:24px; align-items:center">
      <div>
        <h3 style="font-family:var(--font-display);font-weight:300;font-size:28px;line-height:0.95;color:var(--cream)">Ready to stay?<br><em style="color:var(--gold-300);font-style:italic">One check.</em></h3>
        <p style="color:rgba(248,246,241,0.7);max-width:52ch;margin-top:8px">Pick Classic 1–4, pick a date — NightsBridge instant confirms.</p>
      </div>
      <div>
        <a class="btn btn--gold" href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" target="_blank" rel="noopener" style="width:100%;justify-content:center">Check Availability — NightsBridge</a>
        <p class="small" style="color:rgba(248,246,241,0.6);text-align:center;margin-top:8px"><?= e($minPriceDisplay) ?></p>
      </div>
    </div>
  </section>

</main>

<?php require __DIR__ . '/../templates/footer.php'; ?>
