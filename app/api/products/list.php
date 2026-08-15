<?php
// nest/app/api/products/list.php
require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../../models/Product.php';

$filters = ['is_active' => true];
if (!empty($_GET['type']) && in_array($_GET['type'], ['desktop', 'saas', 'android', 'hardware'])) {
    $filters['category'] = $_GET['type'];
}
if (!empty($_GET['pricing']) && in_array($_GET['pricing'], ['free', 'one_time', 'subscription'])) {
    $filters['pricing'] = $_GET['pricing'];
}
if (!empty($_GET['featured'])) {
    $filters['featured'] = true;
}
if (!empty($_GET['q'])) {
    $filters['search'] = trim($_GET['q']);
}

$products = Product::all($filters);
echo json_encode(['success' => true, 'products' => $products]);
