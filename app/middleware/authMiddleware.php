<?php
// nest/app/middleware/authMiddleware.php
require_once __DIR__ . '/../config.php';

class AuthMiddleware {

    public static function requireAuth() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            http_response_code(401);
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['success' => false, 'message' => 'Authentification requise']);
            } else {
                header('Location: ' . url('auth'));
            }
            exit;
        }

        // Vérifier l'expiration de session (24 heures)
        if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > SESSION_TIMEOUT) {
            session_destroy();
            http_response_code(401);
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['success' => false, 'message' => 'Session expirée']);
            } else {
                header('Location: ' . url('auth'));
            }
            exit;
        }

        return true;
    }

    public static function requireAdmin() {
        self::requireAuth();

        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin', 'super_admin'])) {
            http_response_code(403);
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['success' => false, 'message' => 'Accès administrateur requis']);
            } else {
                header('Location: ' . url('home'));
            }
            exit;
        }

        return true;
    }

    public static function getCurrentUser() {
        if (self::requireAuth()) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'role' => $_SESSION['user_role'] ?? 'user'
            ];
        }
        return null;
    }
}
