<?php
// nest/app/api/categories/manage.php (create/update/delete - admin)
require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../../middleware/authMiddleware.php';
AuthMiddleware::requireAdmin();

require_once __DIR__ . '/../../core/helpers.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../models/AdminLog.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$action = $_POST['action'] ?? '';
$name = trim($_POST['name'] ?? '');
$id = (int)($_POST['id'] ?? 0);

switch ($action) {
    case 'create':
        if ($name === '') {
            echo json_encode(['success' => false, 'message' => 'Le nom est requis']);
            exit;
        }
        $result = Category::create(
            $name,
            slugify($_POST['slug'] ?? $name),
            trim($_POST['description'] ?? '') ?: null,
            trim($_POST['icon'] ?? '') ?: null,
            trim($_POST['color'] ?? '') ?: '#0066FF',
            (int)($_POST['display_order'] ?? 0)
        );
        AdminLog::create((int)$_SESSION['user_id'], 'category_created', "Création de la catégorie : $name");
        echo json_encode(['success' => (bool)$result, 'message' => $result ? 'Catégorie créée' : 'Erreur de création']);
        break;

    case 'update':
        if (!$id) { echo json_encode(['success' => false, 'message' => 'ID requis']); exit; }
        $data = [
            'name' => $name,
            'slug' => slugify($_POST['slug'] ?? $name),
            'description' => trim($_POST['description'] ?? '') ?: null,
            'icon' => trim($_POST['icon'] ?? '') ?: null,
            'color' => trim($_POST['color'] ?? '') ?: '#0066FF',
            'display_order' => (int)($_POST['display_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
        ];
        $result = Category::update($id, $data);
        AdminLog::create((int)$_SESSION['user_id'], 'category_updated', "Mise à jour de la catégorie : $name");
        echo json_encode(['success' => (bool)$result, 'message' => $result ? 'Catégorie mise à jour' : 'Erreur de mise à jour']);
        break;

    case 'delete':
        if (!$id) { echo json_encode(['success' => false, 'message' => 'ID requis']); exit; }
        $result = Category::delete($id);
        AdminLog::create((int)$_SESSION['user_id'], 'category_deleted', "Suppression de la catégorie id=$id");
        echo json_encode(['success' => (bool)$result, 'message' => $result ? 'Catégorie supprimée' : 'Erreur de suppression']);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Action inconnue']);
}
