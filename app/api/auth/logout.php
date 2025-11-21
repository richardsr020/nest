<?php
// nest/app/api/auth/logout.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . APP_URL);

require_once __DIR__ . '/../../config.php';

// Destroy session
session_destroy();

// Clear session cookie
setcookie(session_name(), '', time() - 3600, '/');

echo json_encode([
    'success' => true, 
    'message' => 'Déconnexion réussie'
]);
?>