<?php
// nest/app/api/auth/logout.php
require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . APP_URL);

// Détruire la session
$_SESSION = [];
session_destroy();

// Supprimer le cookie de session
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

echo json_encode([
    'success' => true,
    'message' => 'Déconnexion réussie'
]);
