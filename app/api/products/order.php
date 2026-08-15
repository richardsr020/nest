<?php
// nest/app/api/products/order.php
require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . APP_URL);

require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Order.php';

// Only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$product_id = isset($input['product_id']) ? (int)$input['product_id'] : 0;

if (!$product_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Produit requis']);
    exit;
}

$product = Product::find($product_id);
if (!$product || !(int)$product['is_active']) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Produit introuvable']);
    exit;
}

$user_id = isAuthenticated() ? (int)$_SESSION['user_id'] : null;

// Enregistrer une statistique de téléchargement/commande
try {
    $db = Database::getConnection();
    $stmt = $db->prepare("INSERT INTO product_stats (product_id, stat_type, user_id, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
    $statType = $product['pricing_type'] === PRICING_FREE ? 'download' : 'order';
    $stmt->execute([$product_id, $statType, $user_id, getClientIp(), $_SERVER['HTTP_USER_AGENT'] ?? null]);
} catch (Exception $e) {
    error_log("product_stats error: " . $e->getMessage());
}

if ($product['pricing_type'] === PRICING_FREE) {
    Product::incrementDownload($product_id);
    echo json_encode([
        'success' => true,
        'message' => 'Téléchargement enregistré',
        'download_url' => $product['file_path'] ? UPLOADS_URL . $product['file_path'] : null
    ]);
    exit;
}

// Produit payant : enregistrer la commande
$result = Order::create([
    'user_id' => $user_id,
    'product_id' => $product_id,
    'product_name' => $product['name'],
    'pricing_type' => $product['pricing_type'],
    'subscription_period' => $product['subscription_period'],
    'amount' => $product['price'],
    'status' => 'pending',
    'ip_address' => getClientIp(),
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
]);

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => $product['pricing_type'] === PRICING_SUBSCRIPTION
            ? 'Demande d\'abonnement enregistrée. Un conseiller vous contactera pour le paiement.'
            : 'Commande enregistrée. Un conseiller vous contactera pour finaliser le paiement.',
        'order_status' => 'pending'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement de la commande']);
}
