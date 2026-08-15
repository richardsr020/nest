<?php
// nest/app/models/AdminLog.php
require_once __DIR__ . '/../data/database.php';

class AdminLog {
    public static function create($user_id, $action, $description) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO admin_logs (user_id, action, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$user_id, $action, $description, $_SERVER['REMOTE_ADDR'] ?? null, $_SERVER['HTTP_USER_AGENT'] ?? null]);
    }

    public static function all($limit = 50) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT l.*, u.name as user_name FROM admin_logs l JOIN users u ON u.id = l.user_id ORDER BY l.created_at DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
