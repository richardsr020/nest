<?php
// nest/app/views/partials/admin_header.php
$adminPage = $adminPage ?? 'dashboard';
$adminNavItems = [
    'dashboard' => ['url' => url('admin'), 'icon' => 'fa-chart-bar', 'label' => 'Dashboard'],
    'products' => ['url' => url('admin/products'), 'icon' => 'fa-cube', 'label' => 'Produits'],
    'categories' => ['url' => url('admin/categories'), 'icon' => 'fa-tags', 'label' => 'Catégories'],
    'projects' => ['url' => url('admin/projects'), 'icon' => 'fa-project-diagram', 'label' => 'Projets'],
    'orders' => ['url' => url('admin/orders'), 'icon' => 'fa-shopping-cart', 'label' => 'Commandes'],
    'users' => ['url' => url('admin/users'), 'icon' => 'fa-users', 'label' => 'Utilisateurs'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? e($page_title) : 'Admin - ' . APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo css('fontawesome.css'); ?>">
    <link rel="stylesheet" href="<?php echo css('bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo css('admin.css'); ?>">
</head>
<body>
<div class="admin-dashboard">
    <div class="admin-header">
        <div class="container">
            <div class="admin-nav">
                <a href="<?php echo url('home'); ?>" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour au site</span>
                </a>
                <h1>Dashboard <?php echo APP_NAME; ?></h1>
                <div class="admin-user">
                    <i class="fas fa-user-shield"></i>
                    <span><?php echo e($_SESSION['user_name'] ?? 'Admin'); ?></span>
                    <button id="logout-btn" class="logout-button" title="Déconnexion">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <nav class="admin-navbar">
        <div class="container">
            <div class="nav-links">
                <?php foreach ($adminNavItems as $key => $item): ?>
                    <a href="<?php echo $item['url']; ?>" class="nav-link <?php echo $adminPage === $key ? 'active' : ''; ?>">
                        <i class="fas <?php echo $item['icon']; ?>"></i>
                        <span><?php echo $item['label']; ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </nav>
