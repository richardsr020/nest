<?php
// nest/app/views/admin/users.php
$adminPage = 'users';
$roleLabels = ['user' => 'Utilisateur', 'admin' => 'Admin', 'super_admin' => 'Super admin'];
include __DIR__ . '/../partials/admin_header.php';
?>

<div class="container" style="padding:30px 20px;">
    <h2 style="margin:0 0 24px; font-size:1.5rem;">Gestion des utilisateurs</h2>

    <div class="content-card">
        <div class="card-header"><h3>Utilisateurs (<?php echo count($users); ?>)</h3></div>
        <div class="card-body" style="overflow-x:auto;">
            <?php if (empty($users)): ?>
                <p style="color:var(--gray-medium); text-align:center; padding:20px;">Aucun utilisateur.</p>
            <?php else: ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Inscrit</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo (int)$user['id']; ?></td>
                                <td><?php echo e($user['name']); ?></td>
                                <td><?php echo e($user['email']); ?></td>
                                <td>
                                    <select class="form-control" style="width:auto; padding:4px 8px;"
                                            <?php echo ((int)$user['id'] === (int)$_SESSION['user_id']) ? 'disabled' : ''; ?>
                                            onchange="setRole(<?php echo (int)$user['id']; ?>, this.value)">
                                        <?php foreach ($roleLabels as $role => $label): ?>
                                            <option value="<?php echo $role; ?>" <?php echo $user['role'] === $role ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $user['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $user['is_active'] ? 'Actif' : 'Désactivé'; ?>
                                    </span>
                                </td>
                                <td style="white-space:nowrap;">
                                    <?php if ((int)$user['id'] !== (int)$_SESSION['user_id']): ?>
                                        <?php if ($user['is_active']): ?>
                                            <button class="btn btn-icon danger" title="Désactiver" onclick="toggleUser(<?php echo (int)$user['id']; ?>, 'deactivate')"><i class="fas fa-ban"></i></button>
                                        <?php else: ?>
                                            <button class="btn btn-icon" title="Activer" onclick="toggleUser(<?php echo (int)$user['id']; ?>, 'activate')"><i class="fas fa-check-circle"></i></button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <small style="color:var(--gray-medium);">(vous)</small>
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
async function postUserAction(id, action, extra = {}) {
    const fd = new FormData();
    fd.append('id', id);
    fd.append('action', action);
    for (const key in extra) fd.append(key, extra[key]);
    try {
        const res = await fetch('/nest/app/api/users/manage.php', { method: 'POST', body: fd });
        const data = await res.json();
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) setTimeout(() => window.location.reload(), 800);
    } catch (e) { showToast('Erreur réseau', 'error'); }
}

async function toggleUser(id, action) {
    if (!confirm(`Confirmer la ${action === 'activate' ? 'réactivation' : 'désactivation'} ?`)) return;
    postUserAction(id, action);
}

async function setRole(id, role) {
    if (!confirm(`Changer le rôle en « ${role} » ?`)) return;
    postUserAction(id, 'set_role', { role });
}
</script>

<?php include __DIR__ . '/../partials/admin_footer.php'; ?>
