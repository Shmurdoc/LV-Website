# Viata Luxe Guesthouse — Feature Enhancement Design

**Date:** 2026-08-29
**Approach:** B — Extend single-CRUD with handler split + schedule service
**Status:** Approved

---

## Overview

Enhance the existing CMS with professional content management capabilities while maintaining the current architecture's simplicity. Four workstreams, executed sequentially.

---

## Section 1: Core Infrastructure

### 1.1 Soft-Delete System

All content tables get `deleted_at TIMESTAMP NULL` column. Instead of `DELETE FROM`, set `deleted_at = NOW()`.

**Affected tables:** pages, sections, apartments, apartment_images, apartment_amenities, testimonials, faqs, gallery_categories, gallery_images, navigation, safari_activities, page_seo

**Implementation:**
- `admin/api/crud.php` — every `DELETE FROM` becomes `UPDATE ... SET deleted_at = NOW()`
- Admin list pages add a "Trash" filter toggle to view soft-deleted items
- New `admin/api/crud.php?action=restore` endpoint to restore deleted items
- New `admin/api/crud.php?action=permanently_delete` for admin cleanup
- Frontend queries add `WHERE deleted_at IS NULL` via a helper `active_only($query)`
- Add index on `deleted_at` for each affected table

**Migration:**
```sql
ALTER TABLE pages ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at;
ALTER TABLE sections ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at;
-- ... repeat for all 12 tables
ALTER TABLE pages ADD INDEX idx_deleted (deleted_at);
-- ... repeat indexes
```

### 1.2 Visibility & Scheduling

Add `visible_from` and `visible_until` datetime columns for time-based publishing.

**Affected tables:** pages, sections, apartments, testimonials, faqs, gallery_images, navigation, safari_activities

**Implementation:**
- `admin/api/crud.php` save handlers accept `visible_from` and `visible_until` (nullable datetime)
- Admin edit forms get date/time picker inputs
- Admin list pages show a "Scheduled" status badge (gray) alongside Published/Draft
- Frontend `active_only()` helper adds:
  ```sql
  AND (visible_from IS NULL OR visible_from <= NOW())
  AND (visible_until IS NULL OR visible_until >= NOW())
  ```
- Cron-free: visibility checked at query time (no background job needed for a guesthouse site)

**Migration:**
```sql
ALTER TABLE pages ADD COLUMN visible_from DATETIME NULL DEFAULT NULL AFTER is_published;
ALTER TABLE pages ADD COLUMN visible_until DATETIME NULL DEFAULT NULL AFTER visible_from;
-- ... repeat for affected tables
```

---

## Section 2: Image System

### 2.1 Logo Management

Centralize all logo variants in `global_settings` (already partially done). Build a dedicated admin UI.

**Logo variants stored in `global_settings`:**
| Key | Purpose |
|-----|---------|
| `logo` | Default logo (dark version) |
| `logo_dark` | Logo for light backgrounds |
| `logo_light` | Logo for dark backgrounds |
| `logo_favicon` | Favicon (32x32 / 180x180) |
| `logo_og` | Open Graph share image |

**Admin page:** `admin/pages/logo.php`
- Visual upload zone for each variant
- Preview of how each logo renders on light/dark backgrounds
- Crop/resize guidance (stored at original, CSS handles display)
- Saves to `global_settings` table, `uploads/logos/` directory

**File handling:**
- Upload to `uploads/logos/` with timestamp prefix
- Validate: PNG/SVG/WEBP, max 2MB
- Generate thumbnail at 400px width for admin previews
- Old file retained until new upload confirmed

### 2.2 File Browser

Replace all `hero_image` text inputs with a clickable file browser.

**Implementation:**
- New `admin/api/files.php` — lists `uploads/` directory recursively
- Returns JSON: `{ path, name, type, size, modified }`
- Admin modal component: searchable grid of existing uploads
- Click to select → populates hidden input + shows thumbnail preview
- "Upload New" button in the modal for fresh uploads
- Search/filter by folder (`uploads/hero/`, `uploads/apartments/`, `uploads/gallery/`, etc.)
- Drag-and-drop zone in modal for quick upload

**CSS/JS additions:**
- `admin/css/admin.css` — `.file-browser` modal, `.file-browser__grid`, `.file-browser__item`
- `admin/js/app.js` — `openFileBrowser(inputId, filter)` function

**Security:**
- Only serves files under `uploads/` directory
- Path traversal prevention: validate resolved path starts with `uploads/`
- Admin-only access (require_admin check)

---

## Section 3: Admin Architecture

### 3.1 CRUD Handler Split

The current `admin/api/crud.php` is 586 lines with 12 entity handlers in one file. Split into individual handler files while keeping the single entry point.

**New structure:**
```
admin/api/
  crud.php              ← Entry point only (~40 lines): auth, CSRF, entity dispatch
  handlers/
    page.php            ← handlePage()
    section.php         ← handleSection()
    apartment.php       ← handleApartment()
    apartment_image.php
    apartment_amenity.php
    faq.php
    testimonial.php
    navigation.php
    safari.php
    gallery_category.php
    gallery_image.php
    page_seo.php
    setting.php
    contact_submission.php
```

**crud.php entry point becomes:**
```php
<?php
require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/admin-functions.php';

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_error('Method not allowed', 405);
require_admin();
if (!csrf_verify()) json_error('Invalid CSRF token', 403);

$action = $_POST['action'] ?? '';
$entity = $_POST['entity'] ?? '';

$handlers = [
    'page', 'section', 'apartment', 'apartment_image', 'apartment_amenity',
    'faq', 'testimonial', 'navigation', 'safari', 'gallery_category',
    'gallery_image', 'page_seo', 'setting', 'contact_submission'
];

if (!in_array($entity, $handlers)) json_error('Unknown entity');

require_once __DIR__ . "/handlers/{$entity}.php";
$fn = 'handle' . str_replace('_', '', ucwords($entity, '_'));
$fn($action);
```

**Each handler file follows the same pattern:**
```php
<?php
// admin/api/handlers/page.php
function handlePage(string $action): void {
    $db = Database::get();
    switch ($action) {
        case 'save':   handlePageSave($db); break;
        case 'delete': handlePageDelete($db); break;
        case 'restore': handlePageRestore($db); break;
        case 'permanently_delete': handlePageForceDelete($db); break;
        default: json_error('Invalid action');
    }
}
// ... private helper functions
```

### 3.2 Admin Navigation Updates

Add new menu items to `admin/layout.php` sidebar:

```
Content
  ├── Pages
  ├── Sections
  ├── Apartments
  ├── Testimonials
  ├── FAQs
  └── Safari Activities

Media
  ├── Gallery
  ├── Logo Manager        ← NEW
  └── File Browser        ← NEW

Settings
  ├── General Settings
  ├── SEO Defaults
  ├── Contact Form
  ├── Navigation
  └── Activity Log

Trash                     ← NEW (shows soft-deleted items across all entities)
```

---

## Section 4: New Content Types

### 4.1 Events Table

Special offers, seasonal packages, local events.

```sql
CREATE TABLE events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    summary TEXT,
    content LONGTEXT,
    image VARCHAR(500),
    event_date DATE,
    event_end_date DATE,
    event_type ENUM('offer','event','package','news') DEFAULT 'offer',
    is_published BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    visible_from DATETIME NULL,
    visible_until DATETIME NULL,
    deleted_at TIMESTAMP NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (event_type),
    INDEX idx_date (event_date),
    INDEX idx_published (is_published),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Admin pages:** `admin/pages/events.php` (list), `admin/pages/event-edit.php` (create/edit)
**Handler:** `admin/api/handlers/event.php`
**Frontend:** `templates/sections/events.php` — carousel/grid on homepage, dedicated `/events` page

### 4.2 Blog/News Table

Long-form content, travel tips, area guides.

```sql
CREATE TABLE blog_posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT,
    content LONGTEXT,
    featured_image VARCHAR(500),
    author VARCHAR(100) DEFAULT 'Viata Luxe',
    category VARCHAR(100),
    tags JSON,
    meta_title VARCHAR(255),
    meta_description TEXT,
    is_published BOOLEAN DEFAULT TRUE,
    published_at DATETIME NULL,
    visible_from DATETIME NULL,
    visible_until DATETIME NULL,
    deleted_at TIMESTAMP NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_published (is_published, published_at),
    INDEX idx_category (category),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Admin pages:** `admin/pages/blog.php` (list), `admin/pages/blog-edit.php` (create/edit with rich text)
**Handler:** `admin/api/handlers/blog_post.php`
**Frontend:** `templates/sections/blog.php` — latest posts on homepage, `/blog` listing, `/blog/{slug}` detail

### 4.3 Special Offers Table

Time-limited promotions with pricing.

```sql
CREATE TABLE special_offers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    summary TEXT,
    content LONGTEXT,
    image VARCHAR(500),
    original_price DECIMAL(10,2),
    offer_price DECIMAL(10,2),
    currency VARCHAR(3) DEFAULT 'ZAR',
    valid_from DATE,
    valid_until DATE,
    apartment_id INT UNSIGNED DEFAULT NULL,
    is_published BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    visible_from DATETIME NULL,
    visible_until DATETIME NULL,
    deleted_at TIMESTAMP NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (apartment_id) REFERENCES apartments(id) ON DELETE SET NULL,
    INDEX idx_valid (valid_from, valid_until),
    INDEX idx_featured (is_featured),
    INDEX idx_deleted (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Admin pages:** `admin/pages/offers.php` (list), `admin/pages/offer-edit.php`
**Handler:** `admin/api/handlers/special_offer.php`
**Frontend:** `templates/sections/specials.php` — current offers on homepage, `/offers` listing

---

## Execution Order

| Phase | Scope | Files Changed |
|-------|-------|---------------|
| 1 | Soft-delete + visibility columns | schema.sql, crud.php, all admin list pages, includes/functions.php |
| 2 | CRUD handler split | New `admin/api/handlers/` directory, crud.php refactor |
| 3 | File browser | New `admin/api/files.php`, admin CSS/JS, hero_image inputs across admin |
| 4 | Logo management | New `admin/pages/logo.php`, layout.php sidebar, global_settings |
| 5 | Events content type | schema.sql, CRUD handlers, admin pages, frontend template |
| 6 | Blog content type | schema.sql, CRUD handlers, admin pages, frontend template |
| 7 | Special offers content type | schema.sql, CRUD handlers, admin pages, frontend template |

Each phase is independently deployable. No breaking changes between phases.

---

## Testing Strategy

- **Phase 1:** Verify soft-delete hides items from frontend, restore works, trash filter shows deleted items
- **Phase 2:** All existing CRUD operations continue working after handler split
- **Phase 3:** File browser opens, lists uploads, selects file, populates input
- **Phase 4:** Logo upload saves to correct setting key, frontend renders new logo
- **Phases 5-7:** Full CRUD cycle for each new content type + frontend rendering
