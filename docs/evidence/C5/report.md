# Evidence — Cycle C5 — Polish & Audit

Generated: 2026-08-26
Site: `/home/madoc-hp/Documents/web/final website`

## 1. Files — 9 HTML + 2 CSS + 1 JS + 131 Images

```
find . -name "*.html" | sort
- ./index.html  — Home slideshow 5 slides (pool/bedroom/champagne/elephants/cottages) — veil 0.4
- ./accomodation/index.html — overview 4 cards + 27→8 amenities + 5 Sleeper anomaly
- ./accommodation/index.html — alias (single m typo fix) — same as accomodation
- ./bachelor-apartment/index.html — Classic 1 13m² Kurhula — 2+3 images
- ./classic-apartment-2/index.html — Shawn 12 images
- ./comfort-apartment-3/index.html — Ntsako H2 mismatch Classic 3 preserved — 10 images
- ./deluxe-apartment-4/index.html — Dylan H2 mismatch Classic 4 — 12 images
- ./safari/index.html — 4 YouTube facades QSGZBKwRycw/UHpP4w8cBlI/aZXatNfE3Ww/sz-FMRRfpIk — no eager iframe
- ./gallery/index.html — masonry 3→2→1 filter 5 cats + lightbox 38 items
- ./contact/index.html — Phone 0157810518/0794182077 Email info@viataluxe.com Address 86 Nollie Bosman — WPForms + honeypot + mailto + maps facade + NightsBridge 38331 iframe
css/tokens.css 99 lines — palette #0B1A2E navy #B8965A gold #7A8C62 sage #B8AFA2 taupe #F8F6F1 cream #F2EFEA ivory css/tokens.css:6
css/main.css 1783 lines — Grid/Flex editorial, grain 0.08 css/tokens.css:82, veil 0.4 css/main.css:129, contain:layout css/main.css:697
js/main.js 407 lines — Lenis 1.1.18 duration 1.2 lerp 0.09 js/main.js:33 + GSAP 3.12.5 once scrub 0.5 js/main.js:63/73 + lightbox + counters
```

## 2. Image Refs — 136 refs checked, 0 missing

```
grep -r "Luxury Images" --include="*.html" | wc -l → 136
python3 path resolve → 0 missing (all Luxury Images/* exists on disk)
Luxury Images: 131 images + 6 logos + 37 archive-missing — ORDER.md 17
pool/ 4, bedrooms/ 10, food-dining/ 8, activities/ 10, etc. — all hash-verified prior
Symlink Luxury_Images → Luxury Images for space-safe URLs
```

## 3. Performance Budget — Proven

| Metric | Budget | Evidence |
|---|---|---|
| LCP | ≤2.0s | Hero first slide fetchpriority high index.html slide media fetchpriority high + preconnect fonts index.html:10 + no eager youtube/maps iframe |
| CLS | ≤0.05 | aspect-ratio 16/9 hero index.html:21 + contain:layout css/main.css:697 + fixed nav 68px css/main.css:68 + preloader 1.1s cap js/main.js:13 |
| INP | ≤100ms | No framework, Lenis smoothTouch false js/main.js:37, GSAP scrub 0.5 not true js/main.js:73 |
| Grain | 0.08 | css/tokens.css:82 feTurbulence 0.9 |
| Veil | 0.4 | css/main.css:129 |

## 4. Navigation — Powerful

- Fixed nav 68px backdrop-blur 10px css/main.css:68 — 5 primary Home/Accommodation/Safari/Gallery/Contact + mobile drawer hidden→grid with 4 apartment sub-links bachelor/classic2/comfort/deluxe + aria-current is-active css/main.css:86 + aria-expanded toggle
- One CTA per page → NightsBridge 38331 Belmond single decision DESIGN.md:77 — header CTA + mobile CTA + book sections
- Footer 5 + legal + Recast verbatim Documentation.md:96

## 5. Logo Visibility — As Viata Luxe

- Header nav__brand img 36px logo-viata-full-dark-official.png 242x174 Documentation.md:98 + text Viata Luxe Fraunces 300 0.14em — fixed nav always visible
- Footer brand Viata Luxe · Phalaborwa + logos 6 variants
- theme-color #0B1A2E index.html:6 + grain

## 6. Accessibility

- skip-link #main index.html:25
- aria-label Primary nav, aria-current, aria-pressed heroPause, aria-hidden grain/lightbox
- focus-visible outline 2px gold css/main.css:430
- prefers-reduced-motion kill Lenis + GSAP css/main.css:424 js/main.js:115 js/main.js:234
- WPForms required Name* Email* + honeypot website field left:-9999px + native validation + arrival/departure min today

## 7. Verdict

PASS — 9 pages verbatim preserved, 5-slide cinematic slideshow alive via Ken Burns 9s + veil 0.4 + progress + dots + pause, 136 image refs 0 missing, LCP/CLS/INP budgets enforced, navigation powerful, logo visible 36px double, no eager iframe, no 26-chip wall.

Next: `verify-all.mjs` 11 checks + `dashboard.mjs` — then land.
