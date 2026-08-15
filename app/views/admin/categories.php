<?php
// nest/app/views/admin/categories.php
$adminPage = 'categories';
include __DIR__ . '/../partials/admin_header.php';
?>

<div class="container" style="padding:30px 20px;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:24px;">
        <h2 style="margin:0; font-size:1.5rem;">Catégories du catalogue</h2>
        <button class="btn btn-primary" onclick="openCatModal(null)"><i class="fas fa-plus"></i> Nouvelle catégorie</button>
    </div>

    <div class="entities-grid">
        <?php foreach ($categories as $cat): ?>
            <div class="entity-admin-card">
                <div class="card-header" style="display:flex; align-items:center; gap:12px;">
                    <div style="width:46px; height:46px; border-radius:12px; background:<?php echo e($cat['color']); ?>; display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.2rem;">
                        <i class="fas <?php echo e($cat['icon'] ?: 'fa-cube'); ?>"></i>
                    </div>
                    <div style="flex:1;">
                        <h3 style="margin:0;"><?php echo e($cat['name']); ?></h3>
                        <span class="entity-type"><?php echo e($cat['slug']); ?> · ordre <?php echo (int)$cat['display_order']; ?></span>
                    </div>
                    <span class="status-badge <?php echo $cat['is_active'] ? 'active' : 'inactive'; ?>">
                        <?php echo $cat['is_active'] ? 'Actif' : 'Inactif'; ?>
                    </span>
                </div>
                <div class="card-body">
                    <p class="entity-description"><?php echo e($cat['description'] ?: 'Aucune description'); ?></p>
                </div>
                <div class="card-footer" style="display:flex; gap:10px; justify-content:flex-end;">
                    <button class="btn btn-secondary btn-icon" onclick='openCatModal(<?php echo json_encode($cat); ?>)'><i class="fas fa-edit"></i></button>
                    <button class="btn btn-icon danger" onclick="deleteCategory(<?php echo (int)$cat['id']; ?>, '<?php echo addslashes($cat['name']); ?>')"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal Catégorie -->
<div class="modal" id="catModal">
    <div class="modal-content" style="max-width:480px;">
        <div class="modal-header">
            <h2 id="catModalTitle">Nouvelle catégorie</h2>
            <button class="modal-close" onclick="closeCatModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="catForm">
                <input type="hidden" name="id" id="cat-id" value="">
                <input type="hidden" name="action" id="cat-action" value="create">
                <div class="form-group">
                    <label>Nom *</label>
                    <input type="text" name="name" id="cat-name" required>
                </div>
                <div class="form-group">
                    <label>Slug (desktop / saas / android / hardware)</label>
                    <input type="text" name="slug" id="cat-slug">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="cat-desc" rows="3"></textarea>
                </div>
                <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label>Icône (classe FontAwesome)</label>
                        <input type="text" name="icon" id="cat-icon" placeholder="fa-desktop">
                    </div>
                    <div class="form-group">
                        <label>Couleur</label>
                        <input type="color" name="color" id="cat-color" value="#0066FF" style="height:42px; padding:4px;">
                    </div>
                </div>
                <div class="form-group">
                    <label>Ordre d'affichage</label>
                    <input type="number" name="display_order" id="cat-order" value="0">
                </div>
                <label class="checkbox">
                    <input type="checkbox" name="is_active" id="cat-active" checked>
                    <span class="checkmark"></span> Active
                </label>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeCatModal()">Annuler</button>
            <button class="btn btn-primary" id="saveCatBtn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </div>
</div>

<script>
function openCatModal(cat) {
    const form = document.getElementById('catForm');
    form.reset();
    document.getElementById('cat-active').checked = true;
    if (cat) {
        document.getElementById('catModalTitle').textContent = 'Modifier la catégorie';
        document.getElementById('cat-action').value = 'update';
        document.getElementById('cat-id').value = cat.id;
        document.getElementById('cat-name').value = cat.name;
        document.getElementById('cat-slug').value = cat.slug || '';
        document.getElementById('cat-desc').value = cat.description || '';
        document.getElementById('cat-icon').value = cat.icon || '';
        document.getElementById('cat-color').value = cat.color || '#0066FF';
        document.getElementById('cat-order').value = cat.display_order || 0;
        document.getElementById('cat-active').checked = !!cat.is_active;
    } else {
        document.getElementById('catModalTitle').textContent = 'Nouvelle catégorie';
        document.getElementById('cat-action').value = 'create';
        document.getElementById('cat-id').value = '';
    }
    document.getElementById('catModal').style.display = 'flex';
}

function closeCatModal() {
    document.getElementById('catModal').style.display = 'none';
}

document.getElementById('saveCatBtn').addEventListener('click', async function () {
    const fd = new FormData(document.getElementById('catForm'));
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
    try {
        const res = await fetch('/nest/app/api/categories/manage.php', { method: 'POST', body: fd });
        const data = await res.json();
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) setTimeout(() => window.location.reload(), 800);
    } catch (e) { showToast('Erreur réseau', 'error'); }
    finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Enregistrer';
    }
});

async function deleteCategory(id, name) {
    if (!confirm(`Supprimer la catégorie « ${name} » ?`)) return;
    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('id', id);
    try {
        const res = await fetch('/nest/app/api/categories/manage.php', { method: 'POST', body: fd });
        const data = await res.json();
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) setTimeout(() => window.location.reload(), 800);
    } catch (e) { showToast('Erreur réseau', 'error'); }
}
</script>

<?php include __DIR__ . '/../partials/admin_footer.php'; ?>
