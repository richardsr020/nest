<?php
// nest/app/api/products/create.php
require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../../middleware/authMiddleware.php';
AuthMiddleware::requireAdmin();

require_once __DIR__ . '/../../core/helpers.php';
require_once __DIR__ . '/../../core/upload.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/AdminLog.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$name = trim($_POST['name'] ?? '');
if ($name === '') {
    echo json_encode(['success' => false, 'message' => 'Le nom est requis']);
    exit;
}

// Uploads
$icon = handleFileUpload('icon', 'icons');
if (!$icon['success']) { echo json_encode($icon); exit; }
$image = handleFileUpload('image', 'images');
if (!$image['success']) { echo json_encode($image); exit; }
$file = handleFileUpload('file', 'files');
if (!$file['success']) { echo json_encode($file); exit; }

$pricing_type = in_array($_POST['pricing_type'] ?? '', ['free', 'one_time', 'subscription']) ? $_POST['pricing_type'] : 'one_time';

$data = [
    'name' => $name,
    'slug' => slugify($_POST['slug'] ?? $name),
    'description' => trim($_POST['description'] ?? ''),
    'short_description' => trim($_POST['short_description'] ?? '') ?: null,
    'category_id' => (int)($_POST['category_id'] ?? 1),
    'pricing_type' => $pricing_type,
    'price' => (float)($_POST['price'] ?? 0),
    'subscription_period' => ($_POST['subscription_period'] ?? '') === 'yearly' ? 'yearly' : ($pricing_type === 'subscription' ? 'monthly' : null),
    'trial_days' => (int)($_POST['trial_days'] ?? 0),
    'version' => trim($_POST['version'] ?? '') ?: '1.0.0',
    'website_url' => trim($_POST['website_url'] ?? '') ?: null,
    'documentation_url' => trim($_POST['documentation_url'] ?? '') ?: null,
    'play_store_url' => trim($_POST['play_store_url'] ?? '') ?: null,
    'app_store_url' => trim($_POST['app_store_url'] ?? '') ?: null,
    'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null,
    'link_type' => in_array($_POST['link_type'] ?? '', ['control_software', 'accessory']) ? $_POST['link_type'] : null,
    'is_featured' => !empty($_POST['is_featured']),
    'is_active' => isset($_POST['is_active']),
    'release_date' => trim($_POST['release_date'] ?? '') ?: null,
    'icon_path' => $icon['path'],
    'image_path' => $image['path'],
    'file_path' => $file['path'],
    'file_size' => $file['size'],
];

$result = Product::create($data);

if ($result) {
    $productId = (int)Database::getConnection()->lastInsertId();
    $features = $_POST['features'] ?? [];
    if (is_array($features)) {
        Product::setFeatures($productId, $features);
    }
    AdminLog::create((int)$_SESSION['user_id'], 'product_created', "Création du produit : $name");
    echo json_encode(['success' => true, 'message' => 'Produit créé avec succès', 'id' => $productId]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la création du produit']);
}
