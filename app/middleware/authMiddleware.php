<?php
// nest/app/middleware/AuthMiddleware.php

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
                header('Location: /nest/?page=login');
            }
            exit;
        }
        
        // Vérifier l'expiration de session (24 heures)
        if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > 86400) {
            session_destroy();
            http_response_code(401);
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['success' => false, 'message' => 'Session expirée']);
            } else {
                header('Location: /nest/?page=login');
            }
            exit;
        }
        
        return true;
    }
    
    public static function requireAdmin() {
        self::requireAuth();
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['success' => false, 'message' => 'Accès administrateur requis']);
            } else {
                header('Location: /nest/?page=home');
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
?>