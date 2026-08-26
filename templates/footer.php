<?php
/**
 * Footer Template — Viata Luxe Guesthouse
 * Uses main.css .footer system (not .site-footer) — editorial, cream, taupe lines.
 */

$contact = $contact ?? settings_group('contact');
?>
<footer class="footer" role="contentinfo">
    <div class="footer__inner">
        <div class="footer__top">
            <a href="<?= url('/') ?>" class="footer__brand" aria-label="Viata Luxe Guesthouse — Home">
                <img src="<?= e(setting('logo_light', '/Luxury Images/logos/logo-viata-full-light.png')) ?>" alt="Viata Luxe Guesthouse" width="140" height="34" style="height:30px;width:auto;display:inline-block;vertical-align:middle;margin-right:8px" loading="lazy">
                Viata <span>Luxe</span>
            </a>
            <nav class="footer__nav" aria-label="Footer">
                <a href="<?= url('/') ?>">Home</a>
                <a href="<?= url('/accomodation/') ?>">Accommodation</a>
                <a href="<?= url('/gallery/') ?>">Gallery</a>
                <a href="<?= url('/safari/') ?>">Safari</a>
                <a href="<?= url('/contact/') ?>">Contact</a>
            </nav>
        </div>

        <div style="display:grid;grid-template-columns:1.1fr 0.9fr 1fr;gap:24px;align-items:start">
            <div>
                <p class="muted small" style="max-width:52ch"><?= e(setting('site_tagline', 'Luxury self-catering in Phalaborwa — bushveld views, secure parking, jacuzzi.')) ?></p>
                <p class="small" style="margin-top:8px;color:var(--ink-55)"><?= e(setting('address_full', '86 Nollie Bosman Street, Phalaborwa, 1390')) ?></p>
            </div>
            <div class="small">
                <div style="display:grid;gap:6px">
                    <a href="tel:<?= e(setting('phone_tel', '+27157810518')) ?>" style="color:var(--ink-70)"><strong style="color:var(--navy)">Tel:</strong> <?= e(setting('phone_tel_display', '015 781 0518')) ?></a>
                    <a href="tel:<?= e(setting('phone_mobile', '+27794182077')) ?>" style="color:var(--ink-70)"><strong style="color:var(--navy)">Mobile:</strong> <?= e(setting('phone_mobile_display', '079 418 2077')) ?></a>
                    <a href="https://wa.me/<?= e(preg_replace('/[^0-9]/','', setting('whatsapp', '27794182077'))) ?>" target="_blank" rel="noopener" style="color:var(--ink-70)"><strong style="color:var(--navy)">WhatsApp:</strong> Chat with us</a>
                    <a href="mailto:<?= e(setting('email', 'info@viataluxe.com')) ?>" style="color:var(--ink-70)"><?= e(setting('email', 'info@viataluxe.com')) ?></a>
                </div>
            </div>
            <div>
                <a href="<?= e(setting('booking_url', 'https://book.nightsbridge.com/38331')) ?>" class="btn btn--navy" target="_blank" rel="noopener" style="width:100%;justify-content:center"><?= e(setting('booking_cta_text', 'Check Availability — NightsBridge')) ?></a>
                <p class="small" style="color:var(--ink-55);margin-top:8px;text-align:center">Instant confirm · Secure · Best rate</p>
            </div>
        </div>

        <div class="footer__legal">
            <span><?= e(setting('footer_copyright', '© 2026 Viata Luxe Guesthouse. All rights reserved.')) ?></span>
            <span><?= e(setting('footer_credit', 'Built with pride by Recast Media')) ?></span>
        </div>
        <div class="footer__logos" aria-label="Trusted partners">
            <img src="<?= url('/Luxury Images/logos/logo-kruger-national-park.png') ?>" alt="Kruger National Park" loading="lazy" width="120" height="48">
            <img src="<?= url('/Luxury Images/logos/logo-viata-monogram-gold.png') ?>" alt="Viata Luxe monogram" loading="lazy" width="48" height="48">
        </div>
    </div>
</footer>

<!-- Floating actions — accessible, not competing with content -->
<a href="tel:<?= e(setting('phone_mobile', '+27794182077')) ?>" class="call-float" aria-label="Call Viata Luxe" title="Call us">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
</a>
<a href="https://wa.me/<?= e(preg_replace('/[^0-9]/','', setting('whatsapp', '27794182077'))) ?>" class="wa-float" aria-label="Chat on WhatsApp" title="WhatsApp us" target="_blank" rel="noopener">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19.05 4.91A9.82 9.82 0 0 0 12.03 2C6.48 2 2 6.46 2 12.02c0 1.77.46 3.49 1.34 5.01L2 22l5.11-1.34a9.86 9.86 0 0 0 4.92 1.32h.01c5.55 0 10.03-4.48 10.03-10.03 0-2.68-1.04-5.2-2.94-7.1l.92-.94zM12.03 19.86h-.01a8.22 8.22 0 0 1-4.2-1.15l-.3-.18-3.02.79.8-2.94-.2-.3a8.22 8.22 0 0 1-1.27-4.36c0-4.54 3.7-8.24 8.25-8.24 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.42 5.82c0 4.54-3.7 8.24-8.24 8.24l-.25 0z"/></svg>
</a>

<!-- Scripts — GSAP + Lenis are optional, main.js degrades gracefully -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.1.18/dist/lenis.min.js" defer></script>
<script src="<?= url('/js/main.js') ?>" defer></script>

</body>
</html>
