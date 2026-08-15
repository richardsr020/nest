<?php
// nest/app/models/Product.php
require_once __DIR__ . '/../data/database.php';

class Product {

    public static function all($filters = [], $limit = null, $offset = 0) {
        $db = Database::getConnection();
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon, c.color as category_color
                FROM products p
                JOIN categories c ON c.id = p.category_id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['is_active'])) {
            $sql .= " AND p.is_active = 1";
        }
        if (!empty($filters['category'])) {
            $sql .= " AND c.slug = ?";
            $params[] = $filters['category'];
        }
        if (!empty($filters['category_id'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = (int)$filters['category_id'];
        }
        if (!empty($filters['pricing'])) {
            $sql .= " AND p.pricing_type = ?";
            $params[] = $filters['pricing'];
        }
        if (!empty($filters['featured'])) {
            $sql .= " AND p.is_featured = 1";
        }
        if (!empty($filters['parent_id'])) {
            $sql .= " AND p.parent_id = ?";
            $params[] = (int)$filters['parent_id'];
        }
        if (!empty($filters['link_type'])) {
            $sql .= " AND p.link_type = ?";
            $params[] = $filters['link_type'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE ? OR p.short_description LIKE ? OR p.description LIKE ?)";
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY p.is_featured DESC, p.name ASC";

        if ($limit !== null) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = (int)$limit;
            $params[] = (int)$offset;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function find($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon, c.color as category_color
                              FROM products p JOIN categories c ON c.id = p.category_id WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function findBySlug($slug) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT p.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon, c.color as category_color
                              FROM products p JOIN categories c ON c.id = p.category_id WHERE p.slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public static function linkedProducts($productId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM products WHERE parent_id = ? AND is_active = 1 ORDER BY link_type, name");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public static function parent($productId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM products WHERE id = (SELECT parent_id FROM products WHERE id = ?)");
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }

    public static function features($productId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT feature_text FROM product_features WHERE product_id = ? ORDER BY display_order ASC");
        $stmt->execute([$productId]);
        return array_column($stmt->fetchAll(), 'feature_text');
    }

    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO products (
            name, slug, description, short_description, category_id, pricing_type, price,
            subscription_period, trial_days, version, developer, website_url, documentation_url,
            play_store_url, app_store_url, icon_path, image_path, file_path, file_size,
            parent_id, link_type, is_featured, is_active, release_date
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )");
        return $stmt->execute([
            $data['name'], $data['slug'], $data['description'], $data['short_description'] ?? null,
            $data['category_id'], $data['pricing_type'], $data['price'] ?? 0,
            $data['subscription_period'] ?? null, $data['trial_days'] ?? 0,
            $data['version'] ?? '1.0.0', $data['developer'] ?? 'Nest Corporation',
            $data['website_url'] ?? null, $data['documentation_url'] ?? null,
            $data['play_store_url'] ?? null, $data['app_store_url'] ?? null,
            $data['icon_path'] ?? null, $data['image_path'] ?? null,
            $data['file_path'] ?? null, $data['file_size'] ?? 0,
            $data['parent_id'] ?? null, $data['link_type'] ?? null,
            $data['is_featured'] ? 1 : 0, $data['is_active'] ? 1 : 0,
            $data['release_date'] ?? null
        ]);
    }

    public static function update($id, $data) {
        $db = Database::getConnection();
        $fields = [];
        $params = [];
        $allowed = ['name', 'slug', 'description', 'short_description', 'category_id', 'pricing_type',
            'price', 'subscription_period', 'trial_days', 'version', 'developer', 'website_url',
            'documentation_url', 'play_store_url', 'app_store_url', 'icon_path', 'image_path',
            'file_path', 'file_size', 'parent_id', 'link_type', 'is_featured', 'is_active', 'release_date'];
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "`$field` = ?";
                $params[] = $data[$field];
            }
        }
        if (empty($fields)) return false;
        $params[] = $id;
        $stmt = $db->prepare("UPDATE products SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($params);
    }

    public static function delete($id) {
        $db = Database::getConnection();
        $db->beginTransaction();
        try {
            $db->prepare("UPDATE products SET parent_id = NULL WHERE parent_id = ?")->execute([$id]);
            $db->prepare("DELETE FROM product_features WHERE product_id = ?")->execute([$id]);
            $db->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            error_log("Delete product error: " . $e->getMessage());
            return false;
        }
    }

    public static function setFeatures($productId, $features) {
        $db = Database::getConnection();
        $db->prepare("DELETE FROM product_features WHERE product_id = ?")->execute([$productId]);
        $stmt = $db->prepare("INSERT INTO product_features (product_id, feature_text, display_order) VALUES (?, ?, ?)");
        $order = 0;
        foreach ($features as $feature) {
            $feature = trim($feature);
            if ($feature === '') continue;
            $stmt->execute([$productId, $feature, ++$order]);
        }
    }

    public static function incrementView($id) {
        $db = Database::getConnection();
        $db->prepare("UPDATE products SET view_count = view_count + 1 WHERE id = ?")->execute([$id]);
    }

    public static function incrementDownload($id) {
        $db = Database::getConnection();
        $db->prepare("UPDATE products SET download_count = download_count + 1 WHERE id = ?")->execute([$id]);
    }

    public static function countAll() {
        $db = Database::getConnection();
        return (int)$db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    }

    public static function countDownloads() {
        $db = Database::getConnection();
        return (int)$db->query("SELECT IFNULL(SUM(download_count),0) FROM products")->fetchColumn();
    }

    public static function countByCategory() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT c.id, c.name, c.slug, c.icon, c.color, COUNT(p.id) as total
                            FROM categories c LEFT JOIN products p ON p.category_id = c.id AND p.is_active = 1
                            GROUP BY c.id ORDER BY c.display_order");
        return $stmt->fetchAll();
    }
}
