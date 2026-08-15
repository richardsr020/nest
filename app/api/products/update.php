<?php
// nest/app/api/products/update.php
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

$id = (int)($_POST['id'] ?? 0);
$existing = Product::find($id);
if (!$existing) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Produit introuvable']);
    exit;
}

// Uploads (seulement si de nouveaux fichiers fournis)
$icon = handleFileUpload('icon', 'icons');
if (!$icon['success']) { echo json_encode($icon); exit; }
$image = handleFileUpload('image', 'images');
if (!$image['success']) { echo json_encode($image); exit; }
$file = handleFileUpload('file', 'files');
if (!$file['success']) { echo json_encode($file); exit; }

$pricing_type = in_array($_POST['pricing_type'] ?? $existing['pricing_type'], ['free', 'one_time', 'subscription']) ? $_POST['pricing_type'] : $existing['pricing_type'];

$data = [
    'name' => trim($_POST['name'] ?? $existing['name']),
    'slug' => slugify($_POST['slug'] ?? $existing['name']),
    'description' => trim($_POST['description'] ?? $existing['description']),
    'short_description' => trim($_POST['short_description'] ?? '') ?: null,
    'category_id' => (int)($_POST['category_id'] ?? $existing['category_id']),
    'pricing_type' => $pricing_type,
    'price' => (float)($_POST['price'] ?? $existing['price']),
    'subscription_period' => ($_POST['subscription_period'] ?? '') === 'yearly' ? 'yearly' : ($pricing_type === 'subscription' ? 'monthly' : null),
    'trial_days' => (int)($_POST['trial_days'] ?? $existing['trial_days']),
    'version' => trim($_POST['version'] ?? '') ?: $existing['version'],
    'website_url' => trim($_POST['website_url'] ?? '') ?: null,
    'documentation_url' => trim($_POST['documentation_url'] ?? '') ?: null,
    'play_store_url' => trim($_POST['play_store_url'] ?? '') ?: null,
    'app_store_url' => trim($_POST['app_store_url'] ?? '') ?: null,
    'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null,
    'link_type' => in_array($_POST['link_type'] ?? '', ['control_software', 'accessory']) ? $_POST['link_type'] : null,
    'is_featured' => !empty($_POST['is_featured']),
    'is_active' => isset($_POST['is_active']),
    'release_date' => trim($_POST['release_date'] ?? '') ?: null,
];

if ($icon['path']) $data['icon_path'] = $icon['path'];
if ($image['path']) $data['image_path'] = $image['path'];
if ($file['path']) { $data['file_path'] = $file['path']; $data['file_size'] = $file['size']; }

$result = Product::update($id, $data);

$features = $_POST['features'] ?? null;
if (is_array($features)) {
    Product::setFeatures($id, $features);
}

AdminLog::create((int)$_SESSION['user_id'], 'product_updated', "Mise à jour du produit : {$existing['name']}");
echo json_encode(['success' => true, 'message' => 'Produit mis à jour avec succès']);
