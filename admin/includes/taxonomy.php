<?php
/**
 * Taxonomy DB Schema + Helpers — Viata Luxe Guesthouse
 * Adds a public category taxonomy for apartments, gallery, and safari content.
 */

// Migration SQL — run once via CLI or admin setup
function get_taxonomy_migration_sql(): string
{
    return "
CREATE TABLE IF NOT EXISTS public_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    entity_type ENUM('apartment','gallery','safari') NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_entity_slug (entity_type, slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add category_id foreign keys to existing tables
ALTER TABLE apartments
    ADD COLUMN category_id INT UNSIGNED DEFAULT NULL AFTER page_id,
    ADD CONSTRAINT fk_apt_category FOREIGN KEY (category_id) REFERENCES public_categories(id) ON DELETE SET NULL;

ALTER TABLE gallery_images
    ADD COLUMN category_id INT UNSIGNED DEFAULT NULL AFTER id,
    ADD CONSTRAINT fk_gallery_category FOREIGN KEY (category_id) REFERENCES public_categories(id) ON DELETE SET NULL;

-- Seed default categories
INSERT IGNORE INTO public_categories (entity_type, name, slug, sort_order) VALUES
    ('apartment', 'Classic', 'classic', 1),
    ('apartment', 'Luxury', 'luxury', 2),
    ('apartment', 'Family', 'family', 3),
    ('gallery', 'Bedrooms', 'bedrooms', 1),
    ('gallery', 'Bathrooms', 'bathrooms', 2),
    ('gallery', 'Kitchen & Dining', 'kitchen-dining', 3),
    ('gallery', 'Pool & Entertainment', 'pool-entertainment', 4),
    ('gallery', 'Scenic Views', 'scenic-views', 5),
    ('safari', 'Game Drives', 'game-drives', 1),
    ('safari', 'Bush Walks', 'bush-walks', 2),
    ('safari', 'Photography', 'photography', 3),
    ('safari', 'Cultural', 'cultural', 4);
";
}

/**
 * Run the taxonomy migration (CLI only).
 */
function run_taxonomy_migration(): void
{
    $db = Database::get();
    $sql = get_taxonomy_migration_sql();
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    $count = 0;
    foreach ($statements as $stmt) {
        if ($stmt === '') continue;
        try {
            $db->exec($stmt);
            $count++;
        } catch (PDOException $e) {
            // Table/column already exists — skip
            if (strpos($e->getMessage(), 'Duplicate') === false
                && strpos($e->getMessage(), 'already exists') === false
                && strpos($e->getMessage(), 'Duplicate column') === false) {
                echo "Migration warning: " . $e->getMessage() . "\n";
            }
        }
    }
    echo "Taxonomy migration complete. Statements executed: $count\n";
}

/**
 * Get all public categories for a given entity type.
 */
function get_public_categories(string $entityType, bool $activeOnly = true): array
{
    $db = Database::get();
    $sql = 'SELECT * FROM public_categories WHERE entity_type = :type';
    if ($activeOnly) {
        $sql .= ' AND is_active = 1';
    }
    $sql .= ' ORDER BY sort_order ASC, name ASC';
    $stmt = $db->prepare($sql);
    $stmt->execute(['type' => $entityType]);
    return $stmt->fetchAll();
}

/**
 * Get a single public category by ID.
 */
function get_public_category(int $id): ?array
{
    $db = Database::get();
    $stmt = $db->prepare('SELECT * FROM public_categories WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/**
 * Create a new public category.
 */
function create_public_category(array $data): int
{
    $db = Database::get();
    $stmt = $db->prepare('
        INSERT INTO public_categories (entity_type, name, slug, description, sort_order, is_active)
        VALUES (:entity_type, :name, :slug, :description, :sort_order, :is_active)
    ');
    $stmt->execute([
        'entity_type' => $data['entity_type'],
        'name'        => $data['name'],
        'slug'        => $data['slug'],
        'description' => $data['description'] ?? null,
        'sort_order'  => $data['sort_order'] ?? 0,
        'is_active'   => $data['is_active'] ?? 1,
    ]);
    return (int) $db->lastInsertId();
}

/**
 * Update an existing public category.
 */
function update_public_category(int $id, array $data): bool
{
    $db = Database::get();
    $stmt = $db->prepare('
        UPDATE public_categories
        SET name = :name, slug = :slug, description = :description,
            sort_order = :sort_order, is_active = :is_active
        WHERE id = :id
    ');
    return $stmt->execute([
        'id'          => $id,
        'name'        => $data['name'],
        'slug'        => $data['slug'],
        'description' => $data['description'] ?? null,
        'sort_order'  => $data['sort_order'] ?? 0,
        'is_active'   => $data['is_active'] ?? 1,
    ]);
}

/**
 * Delete a public category (does not delete linked content).
 */
function delete_public_category(int $id): bool
{
    $db = Database::get();
    $stmt = $db->prepare('DELETE FROM public_categories WHERE id = :id');
    return $stmt->execute(['id' => $id]);
}

/**
 * Count items in a category.
 */
function count_category_items(int $categoryId): int
{
    $db = Database::get();
    // Check all three entity tables
    $tables = [
        ['table' => 'apartments',     'col' => 'category_id'],
        ['table' => 'gallery_images', 'col' => 'category_id'],
        ['table' => 'safari_activities', 'col' => 'category_id'],
    ];
    foreach ($tables as $t) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM {$t['table']} WHERE {$t['col']} = :id");
        $stmt->execute(['id' => $categoryId]);
        $count = (int) $stmt->fetchColumn();
        if ($count > 0) return $count;
    }
    return 0;
}
