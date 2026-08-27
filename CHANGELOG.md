# Changelog

## [unreleased] — Production-Readiness + Content Fidelity (v2)
### Fixed
- **Admin backend crash** — `admin/api/crud.php` required `admin/config/app.php` + `admin/includes/functions.php` that never existed (require path was one level too shallow). Now loads root config/includes/admin-functions correctly. `handle_upload()` returns `/uploads/...` (matches `UPLOAD_DIR`), not stale `assets/uploads`.
- **Admin SPA API 404** — `admin/index.php` appended `.php` to `/api/crud.php` → `crud.php.php` → 404. Now respects an existing `.php` suffix.
- **Apartment detail 404** — DB slugs (`bilateral/classic/…`) never matched the public route slugs (`bachelor-apartment` etc). Slugs aligned to public routes; `pages/apartment.php` now strips the document-root base path (nested `/work/final website` deployments).
- **Generic page 404** — `pages/default.php` added so any page with `template=default` (e.g. `/about`) renders its sections instead of 404.
- **Broken images / wrong BASE_URL** — `.env` `BASE_URL` pointed at `localhost/viata-luxe` (site lives at `/work/final website`); `UPLOAD_DIR` mispointed to `assets/uploads`. Both corrected; `uploads/` populated with all 47 DB-referenced images mapped from `Luxury Images/`.
- **Fabricated content replaced** — testimonials, contact coordinates, safari (fake `youtube.com/watch?v=example1` → 4 real Kedibone IDs), apartments, home copy and gallery all restored to the original verbatim content (`sql/content-fidelity.sql` + `sql/gallery-fidelity.sql`; also idempotent in `sql/seed.sql`).
- **Admin read/write UI** — 18 stub admin pages rebuilt as real list + edit pages against the CRUD API (pages, sections, apartments, testimonials, FAQs, gallery, navigation, safari, contact, settings). Admin CSRF token now available in the layout shell for deterministic API tests.

### Added
- `templates/sections/safari-activities.php` — renders the 4 safari activities with YouTube facades.
- `tests/fidelity.spec.ts` — route 200s, verbatim testimonials, contact details, 4 YouTube IDs, no broken images, apartment links.
- `tests/admin-crud.spec.ts` — authorised CRUD create → public render → admin list → update → delete round-trip against the real DB.

## [2885a3e] — Ship Viata Luxe CMS
- 12 section partials, schema + seed (4 apartments, gallery, FAQs, safari)