<?php
// nest/app/api/auth/test-debug.php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../models/User.php';

echo json_encode([
    'status' => 'API accessible',
    'session_status' => session_status(),
    'config_loaded' => defined('APP_NAME'),
    'user_model' => class_exists('User') ? 'OK' : 'NOT FOUND'
]);
?>