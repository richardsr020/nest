<?php
// nest/app/models/Category.php
require_once __DIR__ . '/../data/database.php';

class Category {
    public static function all($activeOnly = true) {
        $db = Database::getConnection();
        $sql = "SELECT * FROM categories";
        if ($activeOnly) {
            $sql .= " WHERE is_active = 1";
        }
        $sql .= " ORDER BY display_order ASC, name ASC";
        return $db->query($sql)->fetchAll();
    }

    public static function find($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function findBySlug($slug) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM categories WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public static function create($name, $slug, $description = null, $icon = null, $color = '#0066FF', $displayOrder = 0) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO categories (name, slug, description, icon, color, display_order) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $slug, $description, $icon, $color, $displayOrder]);
    }

    public static function update($id, $data) {
        $db = Database::getConnection();
        $fields = [];
        $params = [];
        foreach (['name', 'slug', 'description', 'icon', 'color', 'display_order', 'is_active'] as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "`$field` = ?";
                $params[] = $data[$field];
            }
        }
        if (empty($fields)) return false;
        $params[] = $id;
        $stmt = $db->prepare("UPDATE categories SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($params);
    }

    public static function delete($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
