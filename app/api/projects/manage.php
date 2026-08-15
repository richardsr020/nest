<?php
// nest/app/api/projects/manage.php (create/update/delete - admin)
require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../../middleware/authMiddleware.php';
AuthMiddleware::requireAdmin();

require_once __DIR__ . '/../../core/helpers.php';
require_once __DIR__ . '/../../core/upload.php';
require_once __DIR__ . '/../../models/Project.php';
require_once __DIR__ . '/../../models/AdminLog.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$action = $_POST['action'] ?? '';
$id = (int)($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');

$image = handleFileUpload('image', 'projects');

if ($action === 'delete') {
    if (!$id) { echo json_encode(['success' => false, 'message' => 'ID requis']); exit; }
    $result = Project::delete($id);
    AdminLog::create((int)$_SESSION['user_id'], 'project_deleted', "Suppression du projet id=$id");
    echo json_encode(['success' => (bool)$result, 'message' => $result ? 'Projet supprimé' : 'Erreur de suppression']);
    exit;
}

if ($title === '') {
    echo json_encode(['success' => false, 'message' => 'Le titre est requis']);
    exit;
}

$category = in_array($_POST['category'] ?? '', ['software', 'electronics', 'iot', 'manufacturing']) ? $_POST['category'] : 'software';

$data = [
    'title' => $title,
    'slug' => slugify($_POST['slug'] ?? $title),
    'description' => trim($_POST['description'] ?? ''),
    'category' => $category,
    'client' => trim($_POST['client'] ?? '') ?: null,
    'year' => trim($_POST['year'] ?? '') ?: null,
    'link' => trim($_POST['link'] ?? '') ?: null,
    'tags' => trim($_POST['tags'] ?? '') ?: null,
    'is_featured' => !empty($_POST['is_featured']),
    'is_active' => isset($_POST['is_active']),
];

if ($action === 'create') {
    $data['image_path'] = $image['path'];
    $result = Project::create($data);
    AdminLog::create((int)$_SESSION['user_id'], 'project_created', "Création du projet : $title");
    echo json_encode(['success' => (bool)$result, 'message' => $result ? 'Projet créé' : 'Erreur de création']);
} elseif ($action === 'update') {
    if (!$id) { echo json_encode(['success' => false, 'message' => 'ID requis']); exit; }
    if ($image['path']) $data['image_path'] = $image['path'];
    $result = Project::update($id, $data);
    AdminLog::create((int)$_SESSION['user_id'], 'project_updated', "Mise à jour du projet : $title");
    echo json_encode(['success' => (bool)$result, 'message' => $result ? 'Projet mis à jour' : 'Erreur de mise à jour']);
} else {
    echo json_encode(['success' => false, 'message' => 'Action inconnue']);
}
