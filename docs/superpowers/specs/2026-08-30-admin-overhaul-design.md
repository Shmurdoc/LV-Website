# Admin Overhaul Design Spec

**Date:** 2026-08-30
**Approach:** Modular (incremental, safe)
**Status:** Approved

---

## Scope

Four work streams, each independently shippable:

1. **Admin Sidebar Grouping** — organize 15 flat nav items into collapsible groups
2. **Image Browser** — reusable modal for browsing `uploads/` and `Luxury Images/`
3. **Public Taxonomy** — category system for apartments, gallery, and safari
4. **Bug Fixes & UX Improvements** — fix all identified poor implementations

---

## 1. Admin Sidebar Grouping

### Current State
15 flat nav items in `layout.php` via `get_admin_nav()`. Hard to scan, no hierarchy.

### Target State
Collapsible grouped sidebar:

```
Dashboard
─── Content ───
  Pages
  Sections
  Hero Slides
  Navigation
─── Listings ───
  Apartments
  Dining
  Safari
  Gallery
─── Engagement ───
  Testimonials
  Contact
  Promises
  Moments
─── System ───
  FAQs
  Settings
  Trash
```

### Implementation

**New file:** `admin/includes/admin-nav.php`
- Defines nav as associative array with groups
- Each group has `label`, `items` array
- Each item has `path`, `label`, `icon` (Lucide icon name), `badge` (optional callback)

**Modified:** `admin/layout.php`
- Include `admin-nav.php`
- Render groups with `<details open>` / `<summary>` for collapsibility
- Active state detection unchanged (prefix matching on `$currentPage`)
- Collapsed state persists in `localStorage`

**CSS additions:** `admin/css/admin.css`
- `.nav-group` — group container
- `.nav-group__label` — subtle divider label (uppercase, small, muted)
- `.nav-group__items` — nested list
- `details[open]` / `details:not([open])` transitions

**No routing changes** — same paths, visual reorganization only.

---

## 2. Image Browser

### Current State
All image inputs are plain text fields. Users must type exact file paths. `handle_upload()` exists but is never called.

### Target State
Reusable modal component attached to any image input via `[data-image-browser]` attribute.

### UI Layout

```
┌─────────────────────────────────────────────────┐
│ Image Browser                            [×]    │
├──────────────┬──────────────────────────────────┤
│ Directory    │  Image Grid                      │
│ Tree         │  ┌────┐ ┌────┐ ┌────┐ ┌────┐   │
│              │  │    │ │    │ │    │ │    │   │
│ ▼ uploads/   │  └────┘ └────┘ └────┘ └────┘   │
│   about/     │  ┌────┐ ┌────┐ ┌────┐ ┌────┐   │
│   apartments/│  │    │ │    │ │    │ │    │   │
│   gallery/   │  └────┘ └────┘ └────┘ └────┘   │
│   hero/      │                                  │
│   safari/    │  Preview:                        │
│              │  ┌──────────────────┐            │
│ ▼ Luxury     │  │                  │            │
│   Images/    │  │   [large image]  │            │
│   activities/│  │                  │            │
│   bedrooms/  │  └──────────────────┘            │
│   dining/    │                                  │
│   ...        │  [Upload New]                    │
├──────────────┴──────────────────────────────────┤
│ Selected: uploads/gallery/bush-1.jpg            │
│                              [Select] [Cancel]  │
└─────────────────────────────────────────────────┘
```

### API Endpoint

**New file:** `admin/api/images.php`

| Method | Params | Returns |
|--------|--------|---------|
| GET | `?action=list&dir=uploads` | JSON: `{ dirs: [...], files: [...] }` |
| GET | `?action=list&dir=Luxury Images/bedrooms` | Nested browsing |
| POST | `action=upload`, multipart file | JSON: `{ path: "uploads/new/file.jpg" }` |

**Security:**
- Directory traversal prevention: `realpath()` check, must be within `ROOT_PATH`
- Only list files with allowed extensions (`jpg`, `jpeg`, `png`, `webp`)
- Upload uses existing `handle_upload()` logic
- CSRF token required for upload

### JavaScript

**New file:** `admin/js/image-browser.js`

- `ImageBrowser` class — singleton modal
- `ImageBrowser.open(inputElement)` — opens modal, links to specific input
- Directory tree: expandable/collapsible folders via AJAX
- Image grid: thumbnail grid with click-to-select
- Preview: hover shows large preview
- Upload tab: drag-and-drop zone, progress bar, calls upload API
- Keyboard: Escape closes, Enter selects, arrow keys navigate grid

### CSS

**New file:** `admin/css/image-browser.css`

- `.ib-overlay` — backdrop
- `.ib-modal` — centered modal
- `.ib-tree` — directory tree sidebar
- `.ib-grid` — image thumbnail grid
- `.ib-preview` — large preview panel
- `.ib-upload` — drag-and-drop zone
- Responsive: collapses tree on narrow screens

### Integration

Each image input gets a Browse button:
```html
<div class="input-group">
  <input type="text" name="hero_image" data-image-browser>
  <button type="button" class="btn btn--sm" onclick="ImageBrowser.open(this.previousElementSibling)">Browse</button>
</div>
```

**Modified files:**
- `admin/pages/apartment-edit.php` — hero image + apartment images
- `admin/pages/section-edit.php` — section image field
- `admin/pages/gallery-images.php` — image path field
- `admin/pages/hero-slide-edit.php` — slide image
- `admin/pages/dining-edit.php` — dining image
- `admin/pages/safari-edit.php` — safari image
- `admin/pages/moment-edit.php` — moment image

---

## 3. Public Taxonomy

### Current State
- Gallery has its own `gallery_categories` table
- Apartments linked to pages via `page_id`
- Safari activities have no categorization
- No cross-content tagging system

### Target State
Unified category system for apartments, gallery, and safari.

### Database Schema

```sql
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(120) NOT NULL,
  type ENUM('apartment', 'gallery', 'safari') NOT NULL,
  description TEXT,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY (slug, type)
);

CREATE TABLE entity_categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  entity_type ENUM('apartment', 'gallery', 'safari') NOT NULL,
  entity_id INT NOT NULL,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
  UNIQUE KEY (entity_type, entity_id, category_id)
);
```

### Admin Side

**New files:**
- `admin/pages/categories-list.php` — lists all categories grouped by type with counts
- `admin/pages/category-edit.php` — create/edit form: name, slug (auto-generated), type, description, sort_order

**Modified files:**
- `admin/pages/apartment-edit.php` — add "Categories" section with checkboxes
- `admin/pages/gallery-images.php` — add category filter
- `admin/pages/safari-edit.php` — add "Categories" section with checkboxes

**Modified:** `admin/api/crud.php`
- New handlers: `handleCategory()`, `handleEntityCategory()`

**Modified:** `admin/index.php`
- Add routes: `/categories` → `categories-list.php`, `/categories/edit` → `category-edit.php`

**Modified:** `admin/includes/admin-nav.php`
- "Categories" added under "System" group

### Public Side

**Modified:** `pages/accommodation.php`
- Add filter tabs: All | [category names]
- JS filter: show/hide apartment cards by `data-category` attribute
- URL param: `?category=luxury` for direct linking

**Modified:** `pages/gallery.php`
- Add category filter sidebar/dropdown
- Filter images by category

**Modified:** `pages/safari.php` (or safari section template)
- Add filter tabs

**New JS:** `js/category-filter.js`
- Generic filter component: reads `data-category` attributes, shows/hides elements
- Updates URL params without page reload
- Remembers selection in sessionStorage

---

## 4. Bug Fixes & UX Improvements

### Dashboard (`admin/pages/dashboard.php`)
- [ ] Remove duplicate `sections` key — replace "Book Now CTA" with `testimonials` count
- [ ] Fix "View all" link target

### Section Edit (`admin/pages/section-edit.php`)
- [ ] Add missing form fields: `background_image`, `padding_top`, `padding_bottom`, `max_width`, `vertical_alignment`, `responsive_stack`
- [ ] Clarify `section_type` (semantic) vs `layout` (visual) — remove overlapping values

### Gallery Images (`admin/pages/gallery-images.php`)
- [ ] Fix redirect after delete/restore: stay on `/admin/gallery/images?category_id=X`
- [ ] Add inline edit for alt text, caption, sort order
- [ ] Add image browser button

### Apartment Edit (`admin/pages/apartment-edit.php`)
- [ ] Add image browser buttons to hero image + apartment images
- [ ] Add image preview next to path inputs
- [ ] Replace JSON textarea for features with structured list input (add/remove rows)
- [ ] Fix redirect after save: stay on edit page

### Inline CSS Extraction
- [ ] `apartment-edit.php`: replace `style="margin-top:20px"` → `.mt-3`, etc.
- [ ] `gallery-images.php`: replace `style="margin-bottom:20px"` → `.mb-3`, etc.
- [ ] `login.php`: extract ~100 lines inline `<style>` → `admin/css/login.css`

### CRUD API (`admin/api/crud.php`)
- [ ] Format minified handlers (`handleHeroSlide`, `handlePromisePillar`, `handleMoment`, `handleDiningItem`)
- [ ] Fix missing `created_at` in `gallery_image` INSERT
- [ ] Fix missing `updated_at` in `testimonial` UPDATE

---

## File Manifest

### New Files
| File | Purpose |
|------|---------|
| `admin/includes/admin-nav.php` | Grouped nav configuration |
| `admin/api/images.php` | Image browser API (list dirs, upload) |
| `admin/js/image-browser.js` | Image browser modal component |
| `admin/css/image-browser.css` | Image browser styles |
| `admin/css/login.css` | Extracted login page styles |
| `admin/pages/categories-list.php` | Category listing |
| `admin/pages/category-edit.php` | Category create/edit |
| `js/category-filter.js` | Public category filter component |
| `sql/categories.sql` | DB migration for categories + entity_categories |

### Modified Files
| File | Changes |
|------|---------|
| `admin/layout.php` | Grouped sidebar rendering |
| `admin/pages/dashboard.php` | Fix duplicate stat, fix link |
| `admin/pages/section-edit.php` | Add missing fields, clarify type/layout |
| `admin/pages/gallery-images.php` | Fix redirect, add inline edit, add browser |
| `admin/pages/apartment-edit.php` | Add browser, preview, structured features, fix redirect |
| `admin/pages/hero-slide-edit.php` | Add image browser |
| `admin/pages/dining-edit.php` | Add image browser |
| `admin/pages/safari-edit.php` | Add image browser, add categories |
| `admin/pages/moment-edit.php` | Add image browser |
| `admin/api/crud.php` | Format handlers, fix timestamps, add category handlers |
| `admin/index.php` | Add category routes |
| `admin/css/admin.css` | Add nav group styles, utility classes |
| `admin/login.php` | Remove inline styles |
| `pages/accommodation.php` | Add category filter tabs |
| `pages/gallery.php` | Add category filter |
| `includes/functions.php` | Add category helper functions |
