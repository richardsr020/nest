<?php
// nest/app/models/Project.php
require_once __DIR__ . '/../data/database.php';

class Project {
    public static function all($filters = [], $limit = null, $offset = 0) {
        $db = Database::getConnection();
        $sql = "SELECT * FROM projects WHERE 1=1";
        $params = [];

        if (!empty($filters['is_active'])) {
            $sql .= " AND is_active = 1";
        }
        if (!empty($filters['category'])) {
            $sql .= " AND category = ?";
            $params[] = $filters['category'];
        }
        if (!empty($filters['featured'])) {
            $sql .= " AND is_featured = 1";
        }

        $sql .= " ORDER BY is_featured DESC, created_at DESC";

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
        $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO projects (title, slug, description, category, client, year, image_path, link, tags, is_featured, is_active)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'], $data['slug'], $data['description'], $data['category'],
            $data['client'] ?? null, $data['year'] ?? null, $data['image_path'] ?? null,
            $data['link'] ?? null, $data['tags'] ?? null,
            $data['is_featured'] ? 1 : 0, $data['is_active'] ? 1 : 0
        ]);
    }

    public static function update($id, $data) {
        $db = Database::getConnection();
        $fields = [];
        $params = [];
        $allowed = ['title', 'slug', 'description', 'category', 'client', 'year', 'image_path', 'link', 'tags', 'is_featured', 'is_active'];
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $fields[] = "`$field` = ?";
                $params[] = $data[$field];
            }
        }
        if (empty($fields)) return false;
        $params[] = $id;
        $stmt = $db->prepare("UPDATE projects SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($params);
    }

    public static function delete($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM projects WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function countAll() {
        $db = Database::getConnection();
        return (int)$db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    }
}
