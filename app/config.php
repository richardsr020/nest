<?php
// nest/app/config.php

// ==================== CONFIGURATION DE LA BASE DE DONNÉES ====================
define('DB_HOST', 'localhost');
define('DB_NAME', 'nest_software');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ==================== CONFIGURATION DE L'APPLICATION ====================
define('APP_NAME', 'Nest');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // development, production

// URLs et Chemins ABSOLUS
define('APP_URL', 'http://localhost/nest');
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', BASE_PATH . '/public/');
define('UPLOAD_PATH', PUBLIC_PATH . 'uploads/');
define('CSS_PATH', PUBLIC_PATH . 'css/');
define('JS_PATH', PUBLIC_PATH . 'js/');
define('IMAGES_PATH', PUBLIC_PATH . 'images/');
define('FONTS_PATH', PUBLIC_PATH . 'fonts/');
define('VENDOR_PATH', PUBLIC_PATH . 'vendor/');
define('LOG_PATH', BASE_PATH . '/app/logs/');

// URLs RELATIVES pour le frontend
define('PUBLIC_URL', '/nest/public/');
define('CSS_URL', PUBLIC_URL . 'css/');
define('JS_URL', PUBLIC_URL . 'js/');
define('IMAGES_URL', PUBLIC_URL . 'images/');
define('FONTS_URL', PUBLIC_URL . 'fonts/');
define('VENDOR_URL', PUBLIC_URL . 'vendor/');
define('UPLOADS_URL', PUBLIC_URL . 'uploads/');

// ==================== CONFIGURATION DES PLATEFORMES ====================
define('PLATFORM_SKILL', 'Skill Platform');
define('PLATFORM_SHOPPING', 'i-Shopping');
define('PLATFORM_PAYMENT', 'Pay & Wise');
define('PLATFORM_MAILER', 'Mailer Pro');

// ==================== FONCTIONS UTILITAIRES ====================
function url($page = 'home', $params = []) {
    $queryString = http_build_query(array_merge(['page' => $page], $params));
    return "/nest/index.php?" . $queryString;
}

function asset($path) {
    return PUBLIC_URL . ltrim($path, '/');
}

function css($file) {
    return CSS_URL . ltrim($file, '/');
}

function js($file) {
    return JS_URL . ltrim($file, '/');
}

function image($file) {
    return IMAGES_URL . ltrim($file, '/');
}

function vendor($file) {
    return VENDOR_URL . ltrim($file, '/');
}

function redirect($page, $params = []) {
    header('Location: ' . url($page, $params));
    exit;
}

function isAuthenticated() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// ==================== GESTION DES ERREURS ====================
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', LOG_PATH . 'error.log');
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ==================== SECURITE ====================
// Dans config.php, ajoutez:
define('CSRF_TOKEN_NAME', 'nest_csrf_token');
define('SESSION_TIMEOUT', 86400); // 24 heures en secondes
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 minutes en secondes

// Configuration de sécurité
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // À activer en production avec HTTPS
ini_set('session.use_strict_mode', 1);

// ==================== DÉMARRAGE DE SESSION ====================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>