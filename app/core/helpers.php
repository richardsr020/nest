<?php
// nest/app/core/helpers.php
// Fonctions utilitaires globales (dépend de config.php)

function url($page = 'home', $params = []) {
    $queryString = http_build_query(array_merge(['page' => $page], $params));
    return "/nest/index.php?" . $queryString;
}

function currentUrl() {
    $page = $_GET['page'] ?? 'home';
    unset($_GET['page']);
    return url($page, $_GET);
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

function isAdmin() {
    return isAuthenticated() && isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'super_admin']);
}

function currentUser() {
    if (!isAuthenticated()) return null;
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'role' => $_SESSION['user_role'] ?? 'user',
    ];
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function slugify($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9-]+/', '-', $text);
    $text = trim($text, '-');
    return $text ?: 'produit';
}

function formatPrice($price, $pricingType = 'one_time') {
    $symbol = APP_CURRENCY_SYMBOL;
    if ($pricingType === PRICING_FREE) {
        return 'Gratuit';
    }
    if ($pricingType === PRICING_SUBSCRIPTION) {
        return $symbol . number_format((float)$price, 2) . '/mois';
    }
    return $symbol . number_format((float)$price, 2);
}

function formatPriceLabel($product) {
    $type = $product['pricing_type'] ?? 'one_time';
    if ($type === PRICING_FREE) {
        return 'Gratuit';
    }
    if ($type === PRICING_SUBSCRIPTION) {
        $period = ($product['subscription_period'] ?? 'monthly') === 'yearly' ? 'an' : 'mois';
        return APP_CURRENCY_SYMBOL . number_format((float)($product['price'] ?? 0), 2) . '/' . $period;
    }
    return APP_CURRENCY_SYMBOL . number_format((float)($product['price'] ?? 0), 2) . ' · unique';
}

function pricingBadgeClass($type) {
    switch ($type) {
        case PRICING_FREE: return 'pricing-free';
        case PRICING_SUBSCRIPTION: return 'pricing-subscription';
        default: return 'pricing-one-time';
    }
}

function categoryIcon($categorySlug) {
    switch ($categorySlug) {
        case 'saas': return 'fa-cloud';
        case 'android': return 'fa-android';
        case 'hardware': return 'fa-microchip';
        default: return 'fa-desktop';
    }
}

function getClientIp() {
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

function logAdmin($user_id, $action, $description) {
    require_once __DIR__ . '/../models/AdminLog.php';
    AdminLog::create($user_id, $action, $description);
}
