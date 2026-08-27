# Viata Luxe Guesthouse — CMS

Editorial luxury accommodation CMS (PHP 8.1+, MySQL 8.0, PDO). Section-based pages driven by `sql/schema.sql` + `templates/sections/*.php`, dispatched by `templates/render-section.php`. Full admin panel reads/writes the same database the frontend renders.

## Quick start

```bash
cp .env.example .env           # set DB_PASS, BASE_URL=https://viataluxe.com
mysql -u root < sql/schema.sql            # create DB + tables (idempotent)
mysql -u root viata_luxe < sql/seed.sql    # seed real content + admin account
php -S 127.0.0.1:8012 index.php            # dev server (Playwright webServer does this)
# smoke: /  /accomodation/  /bachelor-apartment/  /gallery/  /safari/  /contact/  /about/  /api/health
# admin:  /admin/login   -> admin / ViataLuxe2025!
```

> **Content fidelity:** `sql/seed.sql` carries the original verbatim business content (real guests Kurhula/Shawn/Ntsako/Dylan, real contact details `015 781 0518` / `info@viataluxe.com`, 4 real Kruger YouTube IDs, original 5 gallery categories). Do NOT hand-edit the running DB — edit the SQL and re-seed, or use the admin panel (which writes to the same tables the frontend reads).

## Commands

| cmd | what |
|---|---|
| `php -S 127.0.0.1:8012 index.php` | dev server (front controller) |
| `mysql -u root < sql/schema.sql` | apply migrations (CREATE IF NOT EXISTS) |
| `mysql -u root viata_luxe < sql/seed.sql` | reset content + admin to known-good state |
| `curl /api/health` | health JSON `{"ok":true,"checks":{"db":true}}` |
| `npx playwright test` | full verification suite (17 tests) |

## Page routing (`index.php` front controller)

```
/                    -> pages/home.php          (template 'home')
/accomodation        -> pages/accommodation.php (single-m typo alias is canonical, per live site)
/bachelor-apartment
/classic-apartment-2
/comfort-apartment-3
/deluxe-apartment-4  -> pages/apartment.php      (dynamic, keyed on apartments.slug)
/gallery  /safari  /contact                -> dedicated pages
/about              -> pages/default.php         (any DB page with template='default')
/api/health  /api/contact                    -> REST helpers
/admin/*            -> admin/index.php          (admin SPA front controller)
```

Apartments are keyed by their **public slug** (`bachelor-apartment`, `classic-apartment-2`, …) — this is what makes the inner pages resolve. `pages/apartment.php` strips the document-root base path so it works under nested deployments (`/work/final website`).

## Architecture

- `config/app.php` loads `.env` (`APP_ENV`, `BASE_URL`, `DB_*`), hardened session (`httponly/secure/Strict`). `UPLOAD_DIR` = `/uploads` (not `/assets/uploads`).
- `includes/functions.php` — `get_sections()` (single JOIN, no N+1), `get_apartments/_images/_amenities`, `get_gallery_categories/_images`, `get_safari_activities`, `get_faqs`, `setting()` static cache, `e()` esc, `csrf_*`, `image_url()`/`url()`.
- `templates/render-section.php` + `templates/sections/*.php` — 14 section partials (hero, image-text, gallery, testimonials, pricing, safari-activities, …).
- Admin: `admin/index.php` SPA front controller → `admin/pages/*.php` (real list + edit pages for pages, sections, apartments, testimonials, FAQs, gallery, navigation, safari, contact, settings) → `admin/api/crud.php` (auth + CSRF + `require_fields` + `log_activity`) → **the same tables the frontend renders**.
- `api/contact.php` validates + rate-limits contact → `contact_submissions` (read by admin).

## Security

- `admin/index.php` + `require_admin()` enforce session; `csrf_verify()` on every POST; login bcrypt12 + 5/15-min IP throttle; `session_regenerate_id`; httponly cookies.
- All admin page output escaped via `e()`; admin API requires CSRF + auth (`admin-crud.spec.ts` proves create/read/update/delete round-trip).
- `.htaccess` `X-Content-Type-Options/SAMEORIGIN/CSP` + HSTS; `.env` never committed (see `.gitignore`).

## Testing (verify claims, not vibes)

`tests/` uses Playwright against a real `php -S` server + the real MySQL DB:

- `audit.spec.ts` — homepage/no-fatal, hero+nav+footer, health, admin login CSRF, auth redirect, section rendering, a11y.
- `fidelity.spec.ts` — **proves original content fidelity**: all routes 200, verbatim testimonials, real contact details, 4 real YouTube IDs, no broken `<img src>` across all pages, correct apartment detail links.
- `admin-crud.spec.ts` — **proves backend writes land in the DB**: login → create → appears on public page + admin list → update → delete, all via the real CRUD API with CSRF.

## Production checklist

- `APP_ENV=production APP_DEBUG=false BASE_URL=https://viataluxe.com` in `.env`
- Create a non-root MySQL user with a strong `DB_PASS` (see `config/database.php`).
- Web server document root → repository root so the front controller runs (then `/Luxury Images`, `/uploads` resolve).
- Enable backups + error-log forwarding; HTTPS cert + HSTS are already in `.htaccess`.