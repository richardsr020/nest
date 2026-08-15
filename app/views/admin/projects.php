<?php
// nest/app/views/admin/projects.php
$adminPage = 'projects';
$projectCatLabels = ['software' => 'Logiciel', 'electronics' => 'Électronique', 'iot' => 'Objets connectés', 'manufacturing' => 'Fabrication'];
include __DIR__ . '/../partials/admin_header.php';
?>

<div class="container" style="padding:30px 20px;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:24px;">
        <h2 style="margin:0; font-size:1.5rem;">Projets &amp; réalisations</h2>
        <button class="btn btn-primary" onclick="openProjectModal(null)"><i class="fas fa-plus"></i> Nouveau projet</button>
    </div>

    <div class="entities-grid">
        <?php foreach ($projects as $project): ?>
            <div class="entity-admin-card">
                <div class="card-header" style="display:flex; align-items:center; gap:12px;">
                    <div style="width:46px; height:46px; border-radius:12px; background:linear-gradient(135deg,#0066FF,#00D4FF); display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.2rem;">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div style="flex:1;">
                        <h3 style="margin:0;"><?php echo e($project['title']); ?></h3>
                        <span class="entity-type"><?php echo e($projectCatLabels[$project['category']] ?? $project['category']); ?><?php echo !empty($project['year']) ? ' · ' . e($project['year']) : ''; ?></span>
                    </div>
                    <span class="status-badge <?php echo $project['is_active'] ? 'active' : 'inactive'; ?>">
                        <?php echo $project['is_active'] ? 'Actif' : 'Inactif'; ?>
                    </span>
                </div>
                <div class="card-body">
                    <p class="entity-description"><?php echo e(mb_substr($project['description'], 0, 90)); ?><?php echo mb_strlen($project['description']) > 90 ? '…' : ''; ?></p>
                    <?php if (!empty($project['tags'])): ?>
                        <div class="entity-meta"><span><i class="fas fa-tags"></i> <?php echo e($project['tags']); ?></span></div>
                    <?php endif; ?>
                    <?php if ($project['is_featured']): ?>
                        <span class="status-badge active"><i class="fas fa-star"></i> À la une</span>
                    <?php endif; ?>
                </div>
                <div class="card-footer" style="display:flex; gap:10px; justify-content:flex-end;">
                    <button class="btn btn-secondary btn-icon" onclick='openProjectModal(<?php echo json_encode($project); ?>)'><i class="fas fa-edit"></i></button>
                    <button class="btn btn-icon danger" onclick="deleteProject(<?php echo (int)$project['id']; ?>, '<?php echo addslashes($project['title']); ?>')"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal Projet -->
<div class="modal" id="projectModal">
    <div class="modal-content" style="max-width:640px;">
        <div class="modal-header">
            <h2 id="projectModalTitle">Nouveau projet</h2>
            <button class="modal-close" onclick="closeProjectModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="projectForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="pr-id" value="">
                <input type="hidden" name="action" id="pr-action" value="create">
                <div class="form-group">
                    <label>Titre *</label>
                    <input type="text" name="title" id="pr-title" required>
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" id="pr-slug">
                </div>
                <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label>Catégorie</label>
                        <select name="category" id="pr-category">
                            <option value="software">Logiciel</option>
                            <option value="electronics">Électronique</option>
                            <option value="iot">Objets connectés</option>
                            <option value="manufacturing">Fabrication</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Année</label>
                        <input type="text" name="year" id="pr-year" placeholder="2026">
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="pr-description" rows="4"></textarea>
                </div>
                <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label>Client</label>
                        <input type="text" name="client" id="pr-client">
                    </div>
                    <div class="form-group">
                        <label>Lien (démo / code)</label>
                        <input type="text" name="link" id="pr-link">
                    </div>
                </div>
                <div class="form-group">
                    <label>Tags (séparés par des virgules)</label>
                    <input type="text" name="tags" id="pr-tags" placeholder="PHP, MySQL, IoT">
                </div>
                <div class="form-group">
                    <label>Image (fichier)</label>
                    <input type="file" name="image" accept="image/*">
                </div>
                <div class="form-row" style="display:flex; gap:24px; flex-wrap:wrap; margin-top:10px;">
                    <label class="checkbox"><input type="checkbox" name="is_featured" id="pr-featured"><span class="checkmark"></span> À la une</label>
                    <label class="checkbox"><input type="checkbox" name="is_active" id="pr-active" checked><span class="checkmark"></span> Actif</label>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeProjectModal()">Annuler</button>
            <button class="btn btn-primary" id="saveProjectBtn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </div>
</div>

<script>
function openProjectModal(project) {
    const form = document.getElementById('projectForm');
    form.reset();
    document.getElementById('pr-active').checked = true;
    if (project) {
        document.getElementById('projectModalTitle').textContent = 'Modifier le projet';
        document.getElementById('pr-action').value = 'update';
        document.getElementById('pr-id').value = project.id;
        document.getElementById('pr-title').value = project.title;
        document.getElementById('pr-slug').value = project.slug || '';
        document.getElementById('pr-category').value = project.category || 'software';
        document.getElementById('pr-year').value = project.year || '';
        document.getElementById('pr-description').value = project.description || '';
        document.getElementById('pr-client').value = project.client || '';
        document.getElementById('pr-link').value = project.link || '';
        document.getElementById('pr-tags').value = project.tags || '';
        document.getElementById('pr-featured').checked = !!project.is_featured;
        document.getElementById('pr-active').checked = !!project.is_active;
    } else {
        document.getElementById('projectModalTitle').textContent = 'Nouveau projet';
        document.getElementById('pr-action').value = 'create';
        document.getElementById('pr-id').value = '';
    }
    document.getElementById('projectModal').style.display = 'flex';
}

function closeProjectModal() {
    document.getElementById('projectModal').style.display = 'none';
}

document.getElementById('saveProjectBtn').addEventListener('click', async function () {
    const fd = new FormData(document.getElementById('projectForm'));
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
    try {
        const res = await fetch('/nest/app/api/projects/manage.php', { method: 'POST', body: fd });
        const data = await res.json();
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) setTimeout(() => window.location.reload(), 800);
    } catch (e) { showToast('Erreur réseau', 'error'); }
    finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Enregistrer';
    }
});

async function deleteProject(id, title) {
    if (!confirm(`Supprimer le projet « ${title} » ?`)) return;
    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('id', id);
    try {
        const res = await fetch('/nest/app/api/projects/manage.php', { method: 'POST', body: fd });
        const data = await res.json();
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) setTimeout(() => window.location.reload(), 800);
    } catch (e) { showToast('Erreur réseau', 'error'); }
}
</script>

<?php include __DIR__ . '/../partials/admin_footer.php'; ?>
