# Viata Luxe Guesthouse — CMS

Editorial luxury accommodation CMS (PHP 8.1+, MySQL 8.0, PDO). 12 section types via `sql/schema.sql` + `templates/sections/*.php` dispatched by `templates/render-section.php`.

## Quick start

```bash
cp .env.example .env   # set DB_PASS, BASE_URL=https://viataluxe.com
mysql -u root -p < sql/schema.sql
mysql -u root -p viata_luxe < sql/seed.sql   # admin admin/ViataLuxe2025!
php -S localhost:8000 -t .
# smoke: / /accomodation/ /gallery/ /safari/ /contact/ /api/health
```

## Commands

| cmd | what |
|---|---|
| `php -S localhost:8000` | dev server |
| `mysql < sql/schema.sql` | apply migrations (idempotent CREATE IF NOT EXISTS) |
| `curl /api/health` | health JSON `{"ok":true}` |

## Architecture

- `config/app.php` loads `.env` (`APP_ENV`, `BASE_URL`, `DB_*`), hardened session (`httponly/secure/Strict`).
- `includes/functions.php` — single JOIN `get_sections()` (no N+1), `setting()` static cache, `e()` esc, `csrf_*`.
- `index.php` front controller, `.htaccess` CSP/HSTS/gzip/30d expires + HTTPS force (non-localhost).
- `api/contact.php` validates + rate-limits contact → `contact_submissions`; `api/health.php` for monitoring.
- Admin skeleton `admin/index.php` + `admin/pages/*.php` stubs; real CRUD incremental per `api/contact.php` pattern.

## Security

- `admin/login.php` CSRF + 5/15min IP throttle, `password_hash` bcrypt12, `session_regenerate_id`, `httponly`.
- `.htaccess` `X-Content-Type-Options/SAMEORIGIN/CSP` + `HSTS max-age=31536000` (env=HTTPS).
- `.env` never committed (see `.gitignore`), `config/database.php` reads `env()`.

## Production checklist

- Set `APP_ENV=production APP_DEBUG=false BASE_URL=https://viataluxe.com` in `.env`
- Create MySQL user `viata_user` (not root) with strong `DB_PASS`
- `git log --oneline` ships `2885a3e` + `d5f874f` + `da18506`; push to `origin/master`
- Enable backups, error log forwarding, and HTTPS cert.
