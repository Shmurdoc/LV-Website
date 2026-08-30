<?php
/**
 * Section: Trust Bar — Viata Luxe Guesthouse
 * Trust strip with location, NightsBridge badge, kicker + right text.
 * Variables: $section
 * Single source — no duplication in pages/home.php
 */
?>
<div class="trust__inner">
    <div class="trust__left">
        <div class="badge" aria-label="Location Phalaborwa 86 Nollie Bosman">
            <i data-lucide="map-pin" style="width:12px;height:12px;color:var(--gold)"></i>
            <span class="badge__text"><?= e(setting('trust_badge_text', $section['title'] ?? '86 Nollie Bosman St')) ?></span><span class="badge__sub"><?= e(setting('trust_badge_sub', '· Phalaborwa 1390')) ?></span>
        </div>
        <div class="nb-badge"><i data-lucide="calendar-check" style="width:12px;height:12px;color:var(--sage)"></i> <?= e(setting('trust_nightsbridge', 'NightsBridge · Instant book')) ?></div>
        <span class="kicker" style="gap:6px"><span style="width:6px;height:6px;border-radius:50%;background:var(--sage);display:inline-block"></span> <?= e(setting('trust_kicker', $section['subtitle'] ?? 'Minutes to Kruger Gate')) ?></span>
    </div>
    <div class="trust__right"><strong><?= e(setting('trust_right_bold', 'No catalogue.')) ?></strong> <?= e(setting('trust_right_text', '4 apartments, each curated.')) ?> <span class="muted"><?= e(setting('trust_right_muted', 'From R950 · Host on arrival')) ?></span></div>
</div>
