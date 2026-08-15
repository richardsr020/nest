<?php
// nest/app/api/products/delete.php
require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../../middleware/authMiddleware.php';
AuthMiddleware::requireAdmin();

require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/AdminLog.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$existing = Product::find($id);
if (!$existing) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Produit introuvable']);
    exit;
}

if (Product::delete($id)) {
    AdminLog::create((int)$_SESSION['user_id'], 'product_deleted', "Suppression du produit : {$existing['name']}");
    echo json_encode(['success' => true, 'message' => 'Produit supprimé']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
}
