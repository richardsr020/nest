<?php
// nest/app/views/admin/orders.php
$adminPage = 'orders';
$statusLabels = ['pending' => 'En attente', 'confirmed' => 'Confirmée'];
include __DIR__ . '/../partials/admin_header.php';
?>

<div class="container" style="padding:30px 20px;">
    <h2 style="margin:0 0 24px; font-size:1.5rem;">Commandes &amp; téléchargements</h2>

    <div class="content-card">
        <div class="card-header"><h3>Toutes les commandes</h3></div>
        <div class="card-body" style="overflow-x:auto;">
            <?php if (empty($orders)): ?>
                <p style="color:var(--gray-medium); text-align:center; padding:20px;">Aucune commande pour l'instant.</p>
            <?php else: ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Produit</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo (int)$order['id']; ?></td>
                                <td>
                                    <?php echo e($order['user_name'] ?? $order['customer_name'] ?? 'Invité'); ?>
                                    <?php if (!empty($order['user_email'])): ?>
                                        <br><small style="color:var(--gray-medium);"><?php echo e($order['user_email']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($order['product_name'] ?? '—'); ?></td>
                                <td><?php echo e(formatPrice($order['price'] ?? 0, 'USD')); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $order['status'] === 'confirmed' ? 'active' : 'inactive'; ?>">
                                        <?php echo e($statusLabels[$order['status']] ?? $order['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($order['status'] === 'pending'): ?>
                                        <button class="btn btn-primary" style="padding:6px 14px; font-size:0.8rem;"
                                                onclick="setOrderStatus(<?php echo (int)$order['id']; ?>, 'confirmed')">
                                            Confirmer
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" style="padding:6px 14px; font-size:0.8rem;"
                                                onclick="setOrderStatus(<?php echo (int)$order['id']; ?>, 'pending')">
                                            Revenir en attente
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
async function setOrderStatus(id, status) {
    const fd = new FormData();
    fd.append('id', id);
    fd.append('status', status);
    try {
        const res = await fetch('/nest/app/api/orders/status.php', { method: 'POST', body: fd });
        const data = await res.json();
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) setTimeout(() => window.location.reload(), 800);
    } catch (e) { showToast('Erreur réseau', 'error'); }
}
</script>

<?php include __DIR__ . '/../partials/admin_footer.php'; ?>
