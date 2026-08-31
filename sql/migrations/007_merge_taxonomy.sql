-- Migration 007: Merge public_categories → gallery_categories
-- public_categories is the single taxonomy source (entity_type discriminator).
-- gallery_images FK remapped to public_categories.id by slug match.
-- gallery_categories archived (renamed, not dropped) for rollback safety.

-- 1. Add target column (nullable, no FK yet)
ALTER TABLE gallery_images ADD COLUMN public_category_id INT UNSIGNED NULL AFTER category_id;

-- 2. Map old gallery_categories IDs → public_categories IDs by slug
UPDATE gallery_images gi
JOIN gallery_categories gc ON gi.category_id = gc.id
JOIN public_categories pc ON pc.slug = gc.slug AND pc.entity_type = 'gallery'
SET gi.public_category_id = pc.id;

-- 3. Verify zero unmapped rows (should return 0)
-- SELECT COUNT(*) AS unmapped FROM gallery_images WHERE public_category_id IS NULL;

-- 4. Make non-nullable + add FK
ALTER TABLE gallery_images MODIFY COLUMN public_category_id INT UNSIGNED NOT NULL;
ALTER TABLE gallery_images ADD CONSTRAINT fk_gi_public_cat
    FOREIGN KEY (public_category_id) REFERENCES public_categories(id)
    ON DELETE RESTRICT ON UPDATE CASCADE;

-- 5. Drop old FK + rename old column + archive table
ALTER TABLE gallery_images DROP FOREIGN KEY gallery_images_ibfk_1;
ALTER TABLE gallery_images CHANGE COLUMN category_id _archived_category_id INT UNSIGNED NULL;
RENAME TABLE gallery_categories TO _archive_gallery_categories;

-- 6. Update schema.sql (manual step — update FK definition)
