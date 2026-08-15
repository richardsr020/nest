<?php
// nest/app/models/Order.php
require_once __DIR__ . '/../data/database.php';

class Order {
    public static function create($data) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO orders (user_id, product_id, product_name, pricing_type, subscription_period, amount, currency, status, ip_address, user_agent)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'] ?? null, $data['product_id'], $data['product_name'],
            $data['pricing_type'], $data['subscription_period'] ?? null,
            $data['amount'] ?? 0, $data['currency'] ?? APP_CURRENCY,
            $data['status'] ?? 'pending', $data['ip_address'] ?? null, $data['user_agent'] ?? null
        ]);
    }

    public static function all($limit = 100, $offset = 0) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT o.*, u.name as user_name, u.email as user_email
                              FROM orders o LEFT JOIN users u ON u.id = o.user_id
                              ORDER BY o.created_at DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function countAll() {
        $db = Database::getConnection();
        return (int)$db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    }

    public static function countToday() {
        $db = Database::getConnection();
        return (int)$db->query("SELECT COUNT(*) FROM orders WHERE DATE(created_at) = CURDATE()")->fetchColumn();
    }

    public static function totalRevenue() {
        $db = Database::getConnection();
        return (float)$db->query("SELECT IFNULL(SUM(amount),0) FROM orders WHERE status = 'confirmed'")->fetchColumn();
    }

    public static function setStatus($id, $status) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public static function last7Days() {
        $db = Database::getConnection();
        $rows = $db->query("SELECT DATE(created_at) as day, COUNT(*) as total
                            FROM orders WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                            GROUP BY DATE(created_at)")->fetchAll();
        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = date('Y-m-d', strtotime("-$i days"));
            $found = false;
            foreach ($rows as $row) {
                if ($row['day'] === $day) {
                    $result[] = ['day' => $day, 'total' => (int)$row['total']];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $result[] = ['day' => $day, 'total' => 0];
            }
        }
        return $result;
    }
}
