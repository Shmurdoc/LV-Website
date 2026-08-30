<?php
/**
 * Footer Template — Viata Luxe Guesthouse
 * Uses main.css .footer system (not .site-footer) — editorial, cream, taupe lines.
 */

$contact = $contact ?? settings_group('contact');
$footerBrand = setting('footer_brand', setting('site_name_brand', 'Viata Luxe · Phalaborwa'));
$footerLogoLight = setting('logo_light', '/Luxury Images/logos/logo-kruger-national-park-text.png');
$footerMonogram = setting('logo_monogram', '/Luxury Images/logos/logo-kruger-national-park.png');
$footerDark = setting('logo_dark', '/Luxury Images/logos/logo-kruger-national-park.png');
$navFooter = $nav ?? get_navigation();
?>
<footer class="footer" role="contentinfo">
    <div class="footer__inner">
        <div class="footer__top">
            <a href="<?= url('/') ?>" class="footer__brand" aria-label="<?= e($footerBrand) ?> — Home">
                <img src="<?= e(url($footerLogoLight)) ?>" alt="<?= e($footerBrand) ?>" width="119" height="34" class="footer__brand-logo" loading="lazy">
                <?php
                $parts = explode(' ', trim($footerBrand), 2);
                if (count($parts)===2) { echo e($parts[0]).' <span>'.e($parts[1]).'</span>'; } else { echo e($footerBrand); }
                ?>
            </a>
            <nav class="footer__nav" aria-label="Footer">
                <?php foreach ($navFooter as $fItem):
                    // Only top-level, skip Book Now button type (open_in_new_tab) for footer
                    if (!empty($fItem['open_in_new_tab'])) continue;
                    $fHref = $fItem['url'] ?? (!empty($fItem['page_slug']) ? $fItem['page_slug'] : '#');
                    if ($fHref === '#') { $fUrl = '#'; }
                    elseif (preg_match('#^https?://#i', $fHref)) { $fUrl = $fHref; }
                    else { $fUrl = url(ltrim($fHref,'/')); }
                ?>
                <a href="<?= e($fUrl) ?>"><?= e($fItem['label']) ?></a>
                <?php endforeach; ?>
            </nav>
        </div>

        <div class="footer__grid">
            <div>
                <p class="muted small footer__tagline"><?= e(setting('site_tagline', 'Luxury self-catering in Phalaborwa — bushveld views, secure parking, jacuzzi.')) ?></p>
                <p class="small footer__address"><?= e(setting('address_full', '86 Nollie Bosman Street, Phalaborwa, 1390')) ?></p>
            </div>
            <div class="small">
                <div class="footer__contact-list">
                    <a href="tel:<?= e(setting('phone_tel', '+27157810518')) ?>" class="footer__contact-link"><strong>Tel:</strong> <?= e(setting('phone_tel_display', '015 781 0518')) ?></a>
                    <a href="tel:<?= e(setting('phone_mobile', '+27794182077')) ?>" class="footer__contact-link"><strong>Mobile:</strong> <?= e(setting('phone_mobile_display', '079 418 2077')) ?></a>
                    <a href="https://wa.me/<?= e(preg_replace('/[^0-9]/','', setting('whatsapp', '27794182077'))) ?>" target="_blank" rel="noopener" class="footer__contact-link"><strong>WhatsApp:</strong> Chat with us</a>
                    <a href="mailto:<?= e(setting('email', 'info@viataluxe.com')) ?>" class="footer__contact-link"><?= e(setting('email', 'info@viataluxe.com')) ?></a>
                </div>
            </div>
            <div>
                <a href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" class="btn btn--navy footer__cta" target="_blank" rel="noopener"><?= e(setting('booking_cta_text', 'Check Availability — NightsBridge')) ?></a>
                <p class="small footer__cta-sub">Instant confirm · Secure · Best rate</p>
            </div>
        </div>

        <div class="footer__legal">
            <span><?= e(setting('footer_copyright', '© 2026 Viata Luxe Guesthouse. All rights reserved.')) ?></span>
            <span><?= e(setting('footer_credit', 'Built with pride by Recast Media')) ?></span>
            <a class="footer__admin-btn" href="<?= e(url('/admin/login')) ?>" rel="nofollow" aria-label="Admin login"><i data-lucide="settings" style="width:12px;height:12px;vertical-align:-0.1em;margin-right:4px"></i> Admin</a>
        </div>
        <div class="footer__logos" aria-label="Trusted partners">
            <img src="<?= e(url($footerDark)) ?>" alt="<?= e($footerBrand) ?>" loading="lazy" width="120" height="48">
            <img src="<?= e(url($footerMonogram)) ?>" alt="Monogram" loading="lazy" width="48" height="48">
        </div>
    </div>
</footer>

<!-- Floating actions — accessible, not competing with content -->
<a href="tel:<?= e(setting('phone_mobile', '+27794182077')) ?>" class="call-float" aria-label="Call Viata Luxe" title="Call us">
    <i data-lucide="phone" class="icon--float"></i>
</a>
<a href="https://wa.me/<?= e(preg_replace('/[^0-9]/','', setting('whatsapp', '27794182077'))) ?>" class="wa-float" aria-label="Chat on WhatsApp" title="WhatsApp us" target="_blank" rel="noopener">
    <i data-lucide="message-circle" class="icon--float"></i>
</a>

<!-- Lucide icons init -->
<script>
document.addEventListener('DOMContentLoaded',function(){
  if(window.lucide) lucide.createIcons();
});
</script>

<!-- Scripts — GSAP + Lenis are optional, main.js degrades gracefully -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" integrity="sha384-g4NTh/Iv5PPU4xPyhEWqPcwtNXOvdaDI8LLnyYfyNZOjKJeYQyjzQ9X5275eBjpt" crossorigin="anonymous" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" integrity="sha384-Z3REaz79l2IaAZqJsSABtTbhjgOUYyV3p90XNnAPCSHg3EMTz1fouunq9WZRtj3d" crossorigin="anonymous" defer></script>
<script src="https://cdn.jsdelivr.net/npm/lenis@1.1.18/dist/lenis.min.js" integrity="sha384-tKsJDT6PlUI0pSBt9/sBKJluKgA19/a6mBrDsZaXotLB4ZYfMGM6xt6/WgGpYhTm" crossorigin="anonymous" defer></script>
<script src="<?= url('/js/main.js') ?>" defer></script>

</body>
</html>
