<?php
// nest/app/api/users/manage.php (activate/deactivate/role - admin)
require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../../middleware/authMiddleware.php';
AuthMiddleware::requireAdmin();

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/AdminLog.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$action = $_POST['action'] ?? '';

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID requis']);
    exit;
}

// Empêcher de modifier son propre compte admin
if ($id === (int)$_SESSION['user_id'] && $action !== 'view') {
    echo json_encode(['success' => false, 'message' => 'Impossible de modifier votre propre compte']);
    exit;
}

switch ($action) {
    case 'activate':
        $result = User::setActive($id, true);
        break;
    case 'deactivate':
        $result = User::setActive($id, false);
        break;
    case 'set_role':
        $role = $_POST['role'] ?? '';
        if (!in_array($role, ['user', 'admin', 'super_admin'])) {
            echo json_encode(['success' => false, 'message' => 'Rôle invalide']);
            exit;
        }
        $result = User::setRole($id, $role);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Action inconnue']);
        exit;
}

AdminLog::create((int)$_SESSION['user_id'], 'user_updated', "Action $action sur l'utilisateur #$id");
echo json_encode(['success' => (bool)$result, 'message' => $result ? 'Utilisateur mis à jour' : 'Erreur de mise à jour']);
