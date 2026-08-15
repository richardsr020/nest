<?php
// nest/app/views/admin/dashboard.php
$adminPage = 'dashboard';
include __DIR__ . '/../partials/admin_header.php';

$orderStatusLabels = ['pending' => 'En attente', 'confirmed' => 'Confirmée'];
?>

<div class="container" style="padding:30px 20px;">
    <!-- Statistiques principales -->
    <div class="stats-grid">
        <div class="stat-card primary">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-content">
                <h3><?php echo (int)$adminStats['total_users']; ?></h3>
                <p>Utilisateurs</p>
                <small>+<?php echo (int)$adminStats['new_users_today']; ?> aujourd'hui</small>
            </div>
        </div>
        <div class="stat-card success">
            <div class="stat-icon"><i class="fas fa-cube"></i></div>
            <div class="stat-content">
                <h3><?php echo (int)$adminStats['total_products']; ?></h3>
                <p>Produits</p>
                <small><?php echo (int)$adminStats['total_downloads']; ?> téléchargements</small>
            </div>
        </div>
        <div class="stat-card warning">
            <div class="stat-icon"><i class="fas fa-project-diagram"></i></div>
            <div class="stat-content">
                <h3><?php echo (int)$adminStats['total_projects']; ?></h3>
                <p>Projets</p>
                <small>réalisations</small>
            </div>
        </div>
        <div class="stat-card info">
            <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
            <div class="stat-content">
                <h3><?php echo (int)$adminStats['total_orders']; ?></h3>
                <p>Commandes</p>
                <small>+<?php echo (int)$adminStats['orders_today']; ?> aujourd'hui · CA : <?php echo formatPrice($adminStats['total_revenue']); ?></small>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="content-card" style="margin-top:24px;">
        <div class="card-header"><h3>Actions rapides</h3></div>
        <div class="card-body">
            <div class="quick-actions" style="display:flex; gap:12px; flex-wrap:wrap; flex-direction:row;">
                <button class="action-btn" onclick="window.location.href='<?php echo url('admin/products'); ?>'"><i class="fas fa-plus"></i> Ajouter un produit</button>
                <button class="action-btn" onclick="window.location.href='<?php echo url('admin/categories'); ?>'"><i class="fas fa-tags"></i> Gérer les catégories</button>
                <button class="action-btn" onclick="window.location.href='<?php echo url('admin/projects'); ?>'"><i class="fas fa-plus-circle"></i> Ajouter un projet</button>
                <button class="action-btn" onclick="window.location.href='<?php echo url('admin/orders'); ?>'"><i class="fas fa-shopping-cart"></i> Commandes à traiter</button>
            </div>
        </div>
    </div>

    <!-- Dernières commandes -->
    <div class="content-card" style="margin-top:24px;">
        <div class="card-header"><h3>Dernières commandes</h3></div>
        <div class="card-body">
            <?php if (empty($recentOrders)): ?>
                <p style="color:var(--gray-medium); text-align:center; padding:20px;">Aucune commande pour l'instant.</p>
            <?php else: ?>
                <div class="recent-list">
                    <?php foreach ($recentOrders as $order): ?>
                        <div class="recent-item">
                            <div class="item-icon"><i class="fas fa-shopping-bag"></i></div>
                            <div class="item-content">
                                <h4>#<?php echo (int)$order['id']; ?> — <?php echo e($order['product_name'] ?? 'Produit'); ?></h4>
                                <span class="item-meta"><?php echo e($order['user_name'] ?? $order['customer_name'] ?? 'Client'); ?> · <?php echo e(formatPrice($order['price'] ?? 0, 'USD')); ?></span>
                            </div>
                            <div class="item-badge">
                                <span class="status status-<?php echo e($order['status']); ?>"><?php echo e($orderStatusLabels[$order['status']] ?? $order['status']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Informations système -->
    <div class="content-card" style="margin-top:24px;">
        <div class="card-header"><h3>Informations système</h3></div>
        <div class="card-body">
            <div class="system-info">
                <div class="info-item"><span class="info-label">Utilisateur:</span><span class="info-value"><?php echo e($_SESSION['user_name']); ?></span></div>
                <div class="info-item"><span class="info-label">Email:</span><span class="info-value"><?php echo e($_SESSION['user_email']); ?></span></div>
                <div class="info-item"><span class="info-label">Rôle:</span><span class="info-value"><?php echo e($_SESSION['user_role'] ?? 'user'); ?></span></div>
                <div class="info-item"><span class="info-label">PHP:</span><span class="info-value"><?php echo PHP_VERSION; ?></span></div>
                <div class="info-item"><span class="info-label">Version:</span><span class="info-value">3.0 (vitrine ingénierie)</span></div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../partials/admin_footer.php'; ?>
