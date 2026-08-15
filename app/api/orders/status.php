<?php
// nest/app/api/orders/status.php (update status - admin)
require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../../middleware/authMiddleware.php';
AuthMiddleware::requireAdmin();

require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/AdminLog.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';

if (!$id || !in_array($status, ['pending', 'confirmed'])) {
    echo json_encode(['success' => false, 'message' => 'Paramètres invalides']);
    exit;
}

$result = Order::setStatus($id, $status);
AdminLog::create((int)$_SESSION['user_id'], 'order_status', "Commande #$id -> $status");
echo json_encode(['success' => (bool)$result, 'message' => $result ? 'Statut mis à jour' : 'Erreur de mise à jour']);
