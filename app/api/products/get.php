<?php
// nest/app/api/products/get.php
require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../../models/Product.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = Product::find($id);

if (!$product) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Produit introuvable']);
    exit;
}

$product['features'] = Product::features($id);
$product['linked'] = Product::linkedProducts($id);
$product['parent'] = Product::parent($id);

echo json_encode(['success' => true, 'product' => $product]);
