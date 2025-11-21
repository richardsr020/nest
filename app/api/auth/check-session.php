<?php
// nest/app/api/auth/check-session.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . APP_URL);

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../models/User.php';

session_start();

$response = [
    'authenticated' => false,
    'user' => null
];

if (isset($_SESSION['user_id']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Check if session is not expired (24 hours)
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) < 86400) {
        $user = User::findById($_SESSION['user_id']);
        if ($user) {
            $response['authenticated'] = true;
            $response['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $_SESSION['user_role'] ?? 'user'
            ];
        }
    } else {
        // Session expired
        session_destroy();
    }
}

echo json_encode($response);
?>