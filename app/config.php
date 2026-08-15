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
define('APP_VERSION', '3.0.0');
define('APP_ENV', 'development'); // development, production
define('APP_TAGLINE', 'Ingénierie logicielle & électronique');

// ==================== COORDONNÉES & DEVISES ====================
define('APP_EMAIL', 'contact.nestcorp@gmail.com');
define('APP_PHONE', '+243 84 01 49 027');
define('APP_LOCATION', 'Afrique (RDC)');
define('APP_CURRENCY', 'USD');
define('APP_CURRENCY_SYMBOL', '$');

// ==================== MODES DE PAIEMENT ====================
define('PRICING_FREE', 'free');
define('PRICING_ONE_TIME', 'one_time');
define('PRICING_SUBSCRIPTION', 'subscription');

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

// ==================== GESTION DES ERREURS ====================
if (!is_dir(LOG_PATH)) {
    @mkdir(LOG_PATH, 0775, true);
}
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
define('CSRF_TOKEN_NAME', 'nest_csrf_token');
define('SESSION_TIMEOUT', 86400); // 24 heures en secondes
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 minutes en secondes

// Configuration de sécurité
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
if (APP_ENV === 'production') {
    ini_set('session.cookie_secure', 1);
}

// ==================== DÉMARRAGE DE SESSION ====================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==================== HELPER CSRF ====================
function csrfToken() {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}
function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}
function csrfVerify() {
    $token = $_POST['csrf_token'] ?? '';
    return !empty($token) && hash_equals($_SESSION[CSRF_TOKEN_NAME] ?? '', $token);
}
?>