<?php
/**
 * Header Template — Viata Luxe Guesthouse
 * Uses design tokens (navy/gold/sage/taupe/cream) + main.css primitives.
 * Matches main.js expectations: #navToggle + #mobileDrawer, .reveal, Lenis, GSAP.
 * Variables: $page, $nav, $settings, $contact
 */

$meta_title = $page['meta_title'] ?? ($page['title'] ?? 'Viata Luxe Guesthouse');
$meta_description = $page['meta_description'] ?? setting('meta_description_home', '');
$og_image = $page['og_image'] ?? setting('og_image_home', '');
$canonical = url($page['slug'] === 'home' ? '' : trim($page['slug'] ?? '', '/'));
$current_slug = $page['slug'] ?? current_slug();
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

    <!-- Open Graph -->
    <meta property="og:title" content="<?= e($meta_title) ?>">
    <meta property="og:description" content="<?= e($meta_description) ?>">
    <meta property="og:image" content="<?= e(image_url($og_image)) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:type" content="website">

    <!-- Preconnect — performance (fonts) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Fonts: editorial Fraunces (display) + Manrope (body) -->
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,500;9..144,600;9..144,700&family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles — hand-rolled, no Tailwind, editorial measure -->
    <link rel="stylesheet" href="<?= url('/css/tokens.css') ?>">
    <link rel="stylesheet" href="<?= url('/css/main.css') ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= url('/Luxury Images/logos/logo-viata-monogram-gold.png') ?>">
    <style>
        /* Skip link — WCAG 2.1 AA, keyboard-only */
        .skip-link{position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;background:var(--navy);color:var(--cream);padding:10px 14px;border-radius:8px;z-index:100}
        .skip-link:focus{left:12px;top:12px;width:auto;height:auto}
    </style>
</head>
<body>
<div class="grain" aria-hidden="true"></div>

<!-- Preloader — quiet 1.2s max, matches main.css / main.js -->
<div class="preloader" id="preloader" aria-hidden="true">
    <div class="preloader__inner">
        <span class="preloader__mark">Viata Luxe</span>
        <div class="preloader__rule"></div>
        <span class="preloader__sub">Limpopo · Phalaborwa</span>
        <div class="preloader__bar"><i></i></div>
    </div>
</div>

<a href="#main-content" class="skip-link">Skip to main content</a>

<!-- Navigation — uses main.css .nav system (blur, 68px, gold underline, mobile drawer) -->
<nav class="nav" aria-label="Primary">
    <div class="nav__inner">
        <a href="<?= url('/') ?>" class="nav__brand" aria-label="Viata Luxe Guesthouse — Home">
            <img src="<?= e(setting('logo_dark', '/Luxury Images/logos/logo-viata-full-dark-official.png')) ?>" alt="Viata Luxe Guesthouse" width="160" height="34" style="height:34px;width:auto;display:block" loading="eager" decoding="async">
        </a>

        <div class="nav__links" role="navigation" aria-label="Main">
            <?php foreach ($nav as $item):
                $href = $item['url'] ?? (!empty($item['page_slug']) ? $item['page_slug'] : '#');
                // Normalize: page_slug without leading slash -> url()
                $url = ($href === '#') ? '#' : url(ltrim($href, '/'));
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

        <button class="nav__toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="mobileDrawer">
            <span aria-hidden="true"></span>
        </button>
    </div>
</nav>

<!-- Mobile Drawer — controlled by main.js #mobileDrawer + .is-open -->
<div class="mobile-drawer" id="mobileDrawer" aria-hidden="true" role="dialog" aria-label="Menu">
    <?php foreach ($nav as $item):
        $href = $item['url'] ?? (!empty($item['page_slug']) ? $item['page_slug'] : '#');
        $url = ($href === '#') ? '#' : url(ltrim($href, '/'));
    ?>
        <a href="<?= e($url) ?>"<?= !empty($item['open_in_new_tab']) ? ' target="_blank" rel="noopener"' : '' ?>><?= e($item['label']) ?></a>
    <?php endforeach; ?>
    <a href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" class="cta" target="_blank" rel="noopener"><?= e(setting('booking_cta_text', 'BOOK NOW')) ?></a>
</div>
