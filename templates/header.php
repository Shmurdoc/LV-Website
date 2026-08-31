<?php
/**
 * Header Template — Viata Luxe Guesthouse
 * Uses design tokens (navy/gold/sage/taupe/cream) + main.css primitives.
 * Matches main.js expectations: #navToggle + #mobileDrawer, .reveal, Lenis, GSAP.
 * Variables: $page, $nav, $settings, $contact
 */

$meta_title = $page['meta_title'] ?? setting('seo_title_home', ($page['title'] ?? 'Viata Luxe Guesthouse'));
$meta_description = $page['meta_description'] ?? setting('seo_description_home', setting('meta_description_home', ''));
$og_image = $page['og_image'] ?? setting('seo_og_image', setting('og_image_home', '/Luxury Images/pool/pool-overview-entertainment-area.jpg'));
$og_title = setting('seo_og_title', $meta_title);
$og_desc = setting('seo_og_description', $meta_description);
$og_type = setting('seo_og_type', 'website');
$og_url = setting('seo_og_url', setting('seo_canonical', 'https://viataluxe.com/'));
$canonical = url($page['slug'] === 'home' ? '' : trim($page['slug'] ?? '', '/'));
if (setting('seo_canonical', '') !== '' && ($page['slug'] ?? '') === 'home') { $canonical = e(setting('seo_canonical', $canonical)); } else { $canonical = e($canonical); }
$current_slug = $page['slug'] ?? current_slug();

// page_seo — fetched via helper (no DB logic in template)
$pageSeo = !empty($page['id']) ? get_page_seo((int)$page['id']) : null;
$siteBrand = setting('site_name_brand', 'Viata Luxe Guesthouse');
$favicon = setting('favicon', '/Luxury Images/logos/logo-viata-monogram-gold.png');
$logoDark = setting('logo_dark', '/Luxury Images/logos/logo-kruger-national-park.png');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <meta name="theme-color" content="#0B1A2E">
    <title><?= e($meta_title) ?></title>
    <meta name="description" content="<?= e($meta_description) ?>">
    <link rel="canonical" href="<?= e($canonical) ?>">
    <?php if ($pageSeo && !empty($pageSeo['schema_json'])): ?>
    <script type="application/ld+json"><?= $pageSeo['schema_json'] ?></script>
    <?php endif; ?>

    <!-- Open Graph — Track B seo.* keys, single source -->
    <meta property="og:title" content="<?= e($og_title) ?>">
    <meta property="og:description" content="<?= e($og_desc) ?>">
    <meta property="og:image" content="<?= e(image_url($og_image)) ?>">
    <meta property="og:url" content="<?= e(setting('seo_og_url', $canonical)) ?>">
    <meta property="og:type" content="<?= e($og_type) ?>">
    <meta property="og:locale" content="<?= e(setting('seo_og_locale', 'en_ZA')) ?>">
    <meta property="og:site_name" content="<?= e(setting('seo_site_name', $siteBrand)) ?>">
    <meta name="twitter:card" content="<?= e(setting('seo_twitter_card', 'summary_large_image')) ?>">
    <meta name="twitter:title" content="<?= e($og_title) ?>">
    <meta name="twitter:description" content="<?= e($og_desc) ?>">
    <meta name="twitter:image" content="<?= e(image_url($og_image)) ?>">

    <!-- Preconnect — performance (fonts) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Fonts: editorial Fraunces (display) + Manrope (body) -->
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,500;9..144,600;9..144,700&family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons — Lucide -->
    <script src="https://unpkg.com/lucide@0.344.0/dist/umd/lucide.min.js" defer></script>

    <!-- Styles — hand-rolled, no Tailwind, editorial measure -->
    <link rel="stylesheet" href="<?= url('/css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= url('/css/main.css') ?>">

    <!-- Favicon — Track B via setting('favicon') -->
    <link rel="icon" type="image/png" href="<?= e(url($favicon)) ?>">
</head>
<body>
<div class="grain" aria-hidden="true"></div>

<!-- Preloader — Track B via setting('preloader_*'), quiet 1.2s max -->
<div class="preloader" id="preloader" aria-hidden="true">
    <div class="preloader__inner">
        <span class="preloader__mark"><?= e(setting('preloader_mark', 'Viata Luxe')) ?></span>
        <div class="preloader__rule"></div>
        <span class="preloader__sub"><?= e(setting('preloader_sub', 'Phalaborwa · Kruger Minutes')) ?></span>
        <div class="preloader__bar"><i></i></div>
    </div>
</div>

<a href="#main-content" class="skip-link">Skip to main content</a>

<!-- Navigation — uses main.css .nav system (blur, 68px, gold underline, mobile drawer) -->
<nav class="nav" aria-label="Primary">
    <div class="nav__inner">
        <a href="<?= url('/') ?>" class="nav__brand" aria-label="<?= e($siteBrand) ?> — Home">
            <img src="<?= e(url($logoDark)) ?>" alt="<?= e($siteBrand) ?>" width="136" height="135" class="nav__brand-logo" loading="eager" decoding="async">
            <?php
            // site_name_brand split for gold accent (e.g., "Viata Luxe" -> Viata <span>Luxe</span>)
            $brandParts = explode(' ', trim($siteBrand), 2);
            if (count($brandParts) === 2) {
                echo '<span class="nav__brand-text">'.e($brandParts[0]).' <span>'.e($brandParts[1]).'</span></span>';
            } else {
                echo '<span class="nav__brand-text">'.e($siteBrand).'</span>';
            }
            ?>
        </a>

        <div class="nav__links" role="navigation" aria-label="Main">
            <?php foreach ($nav as $item):
                $href = $item['url'] ?? (!empty($item['page_slug']) ? $item['page_slug'] : '#');
                // External URLs (https://) must NOT go through url() — use safe_url directly
                if ($href === '#') { $url = '#'; }
                elseif (preg_match('#^https?://#i', $href)) { $url = $href; }
                else { $url = url(ltrim($href, '/')); }
                $isActive = ($current_slug === ($item['page_slug'] ?? $item['url'] ?? ''));
                // Treat 'home' as active on '/' as well
                if ($current_slug === 'home' && ($item['page_slug'] === 'home' || $href === '' || $href === '/')) $isActive = true;
            ?>
                <a href="<?= e($url) ?>"<?= $isActive ? ' class="is-active" aria-current="page"' : '' ?><?= !empty($item['open_in_new_tab']) ? ' target="_blank" rel="noopener"' : '' ?>>
                    <?= e($item['label']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <a href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" class="nav__cta nav__cta--desktop" target="_blank" rel="noopener">
            <?= e(setting('booking_cta_text', 'BOOK NOW')) ?>
        </a>

        <a class="nav__admin" href="<?= e(url('/admin/login')) ?>" rel="nofollow" aria-label="Admin login"><i data-lucide="settings" class="icon--nav"></i> Admin</a>

        <button class="nav__toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="mobileDrawer">
            <span aria-hidden="true"></span>
        </button>
    </div>
</nav>

<!-- Mobile Drawer — Track B: apartment sub-links via get_apartments(), no hardcoding -->
<div class="mobile-drawer" id="mobileDrawer" aria-hidden="true" role="dialog" aria-label="Menu">
    <?php foreach ($nav as $item):
        $href = $item['url'] ?? (!empty($item['page_slug']) ? $item['page_slug'] : '#');
        if ($href === '#') { $url = '#'; }
        elseif (preg_match('#^https?://#i', $href)) { $url = $href; }
        else { $url = url(ltrim($href, '/')); }
    ?>
        <a href="<?= e($url) ?>"<?= !empty($item['open_in_new_tab']) ? ' target="_blank" rel="noopener"' : '' ?>><?= e($item['label']) ?></a>
        <?php
        // H-04: 4 apartment sub-links under Accommodation (mobile only)
        $isAccom = (strtolower($item['label'] ?? '') === 'accommodation') || (($item['page_slug'] ?? '') === 'accommodation') || (strpos(strtolower($href ?? ''), 'accomodation') !== false);
        if ($isAccom) {
            $apts = function_exists('get_apartments') ? get_apartments() : [];
            foreach ($apts as $apt) {
                $aptUrl = url(ltrim($apt['slug'] ?? '', '/'));
                echo '<a href="'.e($aptUrl).'" class="mobile-drawer__sub">'.e($apt['name'])."</a>\n";
            }
        }
        ?>
    <?php endforeach; ?>
    <a href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" class="cta" target="_blank" rel="noopener"><?= e(setting('booking_cta_text', 'BOOK NOW')) ?></a>
</div>
