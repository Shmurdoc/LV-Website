# Changelog

## [unreleased]
### Added
- `templates/sections/faqs.php` + `contact-form.php`, `api/contact.php` + `api/health.php` — closes phantom DB drift
- `.env` + `.env.example`, HSTS + HTTPS redirect in `.htaccess`
- `admin/pages/*.php` stubs (dashboard + 9 resources) — no more admin 404
- `templates/header.php` JSON-LD for `page_seo`
### Fixed
- `image-text.php` enum (`text-left/right/image-top/text-top`), `pricing.php`/`apartment-cards.php` schema (`hero_image`, `room_size_m2`)
- `admin/login.php` CSRF + rate limit, `header.php` skip-link + `.nav` parity

## [2885a3e] — Ship Viata Luxe CMS
- 12 section partials, schema + seed (4 apartments, gallery, FAQs, safari)
