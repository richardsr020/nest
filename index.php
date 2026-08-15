<?php
// nest/index.php - Routeur principal
require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/core/helpers.php';

$page = $_GET['page'] ?? 'home';

// Routes publiques
switch ($page) {
    case 'home':
        require_once __DIR__ . '/app/models/Product.php';
        require_once __DIR__ . '/app/models/Project.php';
        $featuredProducts = Product::all(['is_active' => true, 'featured' => true], 4);
        $featuredProjects = Project::all(['is_active' => true, 'featured' => true], 3);
        $categoryCounts = Product::countByCategory();
        $page_title = "Accueil - " . APP_NAME;
        include 'app/views/pages/home.php';
        break;

    case 'services':
        $page_title = "Services - " . APP_NAME;
        include 'app/views/pages/services.php';
        break;

    case 'catalog':
        require_once __DIR__ . '/app/models/Product.php';
        require_once __DIR__ . '/app/models/Category.php';
        $categories = Category::all();
        $filters = ['is_active' => true];
        if (!empty($_GET['type']) && in_array($_GET['type'], ['desktop', 'saas', 'android', 'hardware'])) {
            $filters['category'] = $_GET['type'];
        }
        if (!empty($_GET['pricing']) && in_array($_GET['pricing'], ['free', 'one_time', 'subscription'])) {
            $filters['pricing'] = $_GET['pricing'];
        }
        if (!empty($_GET['q'])) {
            $filters['search'] = trim($_GET['q']);
        }
        $products = Product::all($filters);
        $activeType = $_GET['type'] ?? '';
        $activePricing = $_GET['pricing'] ?? '';
        $searchQuery = $_GET['q'] ?? '';
        $page_title = "Catalogue - " . APP_NAME;
        include 'app/views/pages/catalog.php';
        break;

    case 'product':
        require_once __DIR__ . '/app/models/Product.php';
        $product = null;
        if (!empty($_GET['id'])) {
            $product = Product::find((int)$_GET['id']);
        } elseif (!empty($_GET['slug'])) {
            $product = Product::findBySlug($_GET['slug']);
        }
        if (!$product) {
            http_response_code(404);
            include 'app/views/pages/404.php';
            break;
        }
        Product::incrementView($product['id']);
        $product['features'] = Product::features($product['id']);
        $linked = Product::linkedProducts($product['id']);
        $parent = Product::parent($product['id']);
        $page_title = $product['name'] . " - " . APP_NAME;
        include 'app/views/pages/product.php';
        break;

    case 'projects':
        require_once __DIR__ . '/app/models/Project.php';
        $projects = Project::all(['is_active' => true]);
        $activeCategory = $_GET['category'] ?? '';
        if ($activeCategory && in_array($activeCategory, ['software', 'electronics', 'iot', 'manufacturing'])) {
            $projects = Project::all(['is_active' => true, 'category' => $activeCategory]);
        }
        $page_title = "Projets & Réalisations - " . APP_NAME;
        include 'app/views/pages/projects.php';
        break;

    case 'about':
        $page_title = "À propos - " . APP_NAME;
        include 'app/views/pages/about.php';
        break;

    case 'contact':
        $page_title = "Contact - " . APP_NAME;
        include 'app/views/pages/contact.php';
        break;

    case 'auth':
        if (isAuthenticated()) {
            redirect('admin');
        }
        $page_title = "Connexion & Inscription - " . APP_NAME;
        include 'app/views/auth.php';
        break;

    // ==================== ROUTES ADMIN ====================
    case 'admin':
        require_once __DIR__ . '/app/middleware/authMiddleware.php';
        AuthMiddleware::requireAdmin();
        require_once __DIR__ . '/app/models/Order.php';
        require_once __DIR__ . '/app/models/Product.php';
        require_once __DIR__ . '/app/models/User.php';
        require_once __DIR__ . '/app/models/Project.php';
        $adminStats = [
            'total_users' => User::countAll(),
            'new_users_today' => User::countNewToday(),
            'total_products' => Product::countAll(),
            'total_downloads' => Product::countDownloads(),
            'total_projects' => Project::countAll(),
            'total_orders' => Order::countAll(),
            'orders_today' => Order::countToday(),
            'total_revenue' => Order::totalRevenue(),
        ];
        $recentOrders = Order::all(8);
        $page_title = "Dashboard - " . APP_NAME;
        include 'app/views/admin/dashboard.php';
        break;

    case 'admin/products':
        require_once __DIR__ . '/app/middleware/authMiddleware.php';
        AuthMiddleware::requireAdmin();
        require_once __DIR__ . '/app/models/Product.php';
        require_once __DIR__ . '/app/models/Category.php';
        $categories = Category::all();
        $allProducts = Product::all([]);
        $page_title = "Gestion des produits - " . APP_NAME;
        include 'app/views/admin/products.php';
        break;

    case 'admin/categories':
        require_once __DIR__ . '/app/middleware/authMiddleware.php';
        AuthMiddleware::requireAdmin();
        require_once __DIR__ . '/app/models/Category.php';
        $categories = Category::all(false);
        $page_title = "Gestion des catégories - " . APP_NAME;
        include 'app/views/admin/categories.php';
        break;

    case 'admin/projects':
        require_once __DIR__ . '/app/middleware/authMiddleware.php';
        AuthMiddleware::requireAdmin();
        require_once __DIR__ . '/app/models/Project.php';
        $projects = Project::all([]);
        $page_title = "Gestion des projets - " . APP_NAME;
        include 'app/views/admin/projects.php';
        break;

    case 'admin/orders':
        require_once __DIR__ . '/app/middleware/authMiddleware.php';
        AuthMiddleware::requireAdmin();
        require_once __DIR__ . '/app/models/Order.php';
        $orders = Order::all(200);
        $page_title = "Commandes - " . APP_NAME;
        include 'app/views/admin/orders.php';
        break;

    case 'admin/users':
        require_once __DIR__ . '/app/middleware/authMiddleware.php';
        AuthMiddleware::requireAdmin();
        require_once __DIR__ . '/app/models/User.php';
        $users = User::all(200);
        $page_title = "Utilisateurs - " . APP_NAME;
        include 'app/views/admin/users.php';
        break;

    default:
        http_response_code(404);
        include 'app/views/pages/404.php';
        break;
}
