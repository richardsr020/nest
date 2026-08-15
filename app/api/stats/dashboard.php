<?php
// nest/app/api/stats/dashboard.php
require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../../middleware/authMiddleware.php';
AuthMiddleware::requireAdmin();

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Project.php';
require_once __DIR__ . '/../../models/Order.php';

$stats = [
    'total_users' => User::countAll(),
    'new_users_today' => User::countNewToday(),
    'total_products' => Product::countAll(),
    'total_downloads' => Product::countDownloads(),
    'total_projects' => Project::countAll(),
    'total_orders' => Order::countAll(),
    'orders_today' => Order::countToday(),
    'total_revenue' => Order::totalRevenue(),
    'orders_7_days' => Order::last7Days(),
    'categories' => Product::countByCategory(),
];

echo json_encode(['success' => true, 'stats' => $stats]);
