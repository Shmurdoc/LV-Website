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
                <p class="muted small footer__tagline"><?= e(setting('site_tagline', 'Luxury self-catering in Phalaborwa — bushveld views, secure parking, swimming pool.')) ?></p>
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
                <p class="small footer__cta-sub"><?= e(setting('footer_fine_print', 'Instant confirm · Secure · Best rate')) ?></p>
            </div>
        </div>

        <div class="footer__legal">
            <span><?= e(setting('footer_copyright', '© 2026 Viata Luxe Guesthouse. All rights reserved.')) ?></span>
            <span><?= e(setting('footer_credit', 'Built with pride by Recast Media')) ?></span>
            <a class="footer__admin-btn" href="<?= e(url('/admin/login')) ?>" rel="nofollow" aria-label="Admin login"><i data-lucide="settings" style="width:12px;height:12px;vertical-align:-0.1em;margin-right:4px"></i> Admin</a>
        </div>
        <div class="footer__logos" aria-label="Trusted partners">
            <img src="<?= e(url($footerDark)) ?>" alt="<?= e($footerBrand) ?>" loading="lazy" width="120" height="48">
        </div>
    </div>
</footer>

<!-- Floating actions — accessible, not competing with content -->
<a href="tel:<?= e(setting('phone_mobile', '+27794182077')) ?>" class="call-float" aria-label="Call Viata Luxe" title="Call us">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
</a>
<a href="https://wa.me/<?= e(preg_replace('/[^0-9]/','', setting('whatsapp', '27794182077'))) ?>" class="wa-float" aria-label="Chat on WhatsApp" title="WhatsApp us" target="_blank" rel="noopener">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28"><path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
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
<script src="<?= url('/js/category-filter.js') ?>" defer></script>

</body>
</html>
