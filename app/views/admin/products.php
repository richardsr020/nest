<?php
// nest/app/views/admin/products.php
$adminPage = 'products';
$pricingLabels = [
    'free' => 'Gratuit',
    'one_time' => 'Paiement unique',
    'subscription' => 'Abonnement',
];
include __DIR__ . '/../partials/admin_header.php';
?>

<div class="container" style="padding:30px 20px;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:24px;">
        <div class="search-box" style="flex:1; max-width:400px;">
            <i class="fas fa-search"></i>
            <input type="text" id="productSearch" placeholder="Rechercher un produit...">
        </div>
        <button class="btn btn-primary" onclick="openProductModal(null)">
            <i class="fas fa-plus"></i> Nouveau produit
        </button>
    </div>

    <div class="entities-grid" id="productsGrid">
        <?php foreach ($allProducts as $product): ?>
            <div class="entity-admin-card" data-product-id="<?php echo (int)$product['id']; ?>">
                <div class="card-header" style="display:flex; align-items:center; gap:12px;">
                    <div style="width:46px; height:46px; border-radius:12px; background:<?php echo e($product['category_color']); ?>; display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.2rem;">
                        <i class="fas fa-cube"></i>
                    </div>
                    <div style="flex:1;">
                        <h3 style="margin:0;"><?php echo e($product['name']); ?></h3>
                        <span class="entity-type"><?php echo e($product['category_name']); ?></span>
                    </div>
                    <span class="status-badge <?php echo $product['is_active'] ? 'active' : 'inactive'; ?>">
                        <?php echo $product['is_active'] ? 'Actif' : 'Inactif'; ?>
                    </span>
                </div>
                <div class="card-body">
                    <p class="entity-description"><?php echo e(mb_substr($product['description'], 0, 90)); ?><?php echo mb_strlen($product['description']) > 90 ? '…' : ''; ?></p>
                    <div class="entity-meta">
                        <span><i class="fas fa-tag"></i> <?php echo e($pricingLabels[$product['pricing_type']] ?? $product['pricing_type']); ?></span>
                        <span><i class="fas fa-dollar-sign"></i> <?php echo e(formatPrice($product['price'], 'USD')); ?></span>
                        <span><i class="fas fa-eye"></i> <?php echo (int)$product['view_count']; ?></span>
                    </div>
                    <?php if ($product['is_featured']): ?>
                        <span class="status-badge active"><i class="fas fa-star"></i> À la une</span>
                    <?php endif; ?>
                    <?php if ($product['parent_id']): ?>
                        <span class="status-badge inactive"><i class="fas fa-layer-group"></i> Bundle</span>
                    <?php endif; ?>
                </div>
                <div class="card-footer" style="display:flex; gap:10px; justify-content:flex-end;">
                    <button class="btn btn-secondary btn-icon" onclick="openProductModal(<?php echo (int)$product['id']; ?>)"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-icon danger" onclick="deleteProduct(<?php echo (int)$product['id']; ?>, '<?php echo addslashes($product['name']); ?>')"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal Produit -->
<div class="modal" id="productModal">
    <div class="modal-content" style="max-width:760px;">
        <div class="modal-header">
            <h2 id="modalTitle">Nouveau produit</h2>
            <button class="modal-close" onclick="closeProductModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="productForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="p-id" value="">

                <div class="form-group">
                    <label>Nom *</label>
                    <input type="text" name="name" id="p-name" required>
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" id="p-slug" placeholder="Laisser vide pour auto-génération">
                </div>
                <div class="form-group">
                    <label>Catégorie</label>
                    <select name="category_id" id="p-category">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo (int)$cat['id']; ?>"><?php echo e($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label>Type de prix</label>
                        <select name="pricing_type" id="p-pricing" onchange="togglePricingFields()">
                            <option value="free">Gratuit</option>
                            <option value="one_time">Paiement unique</option>
                            <option value="subscription">Abonnement</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Prix (USD)</label>
                        <input type="number" step="0.01" min="0" name="price" id="p-price" value="0">
                    </div>
                </div>

                <div class="form-row" id="pricingExtras" style="display:grid; grid-template-columns:1fr 1fr; gap:16px; display:none;">
                    <div class="form-group">
                        <label>Période d'abonnement</label>
                        <select name="subscription_period" id="p-period">
                            <option value="monthly">Mensuel</option>
                            <option value="yearly">Annuel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jours d'essai</label>
                        <input type="number" min="0" name="trial_days" id="p-trial" value="0">
                    </div>
                </div>

                <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label>Version</label>
                        <input type="text" name="version" id="p-version" value="1.0.0">
                    </div>
                    <div class="form-group">
                        <label>Date de sortie</label>
                        <input type="date" name="release_date" id="p-release">
                    </div>
                </div>

                <div class="form-group">
                    <label>Description courte</label>
                    <input type="text" name="short_description" id="p-short" placeholder="Un résumé en une phrase">
                </div>
                <div class="form-group">
                    <label>Description complète</label>
                    <textarea name="description" id="p-description" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label>Caractéristiques (une par ligne)</label>
                    <textarea name="features" id="p-features" rows="4" placeholder="Fonctionnalité 1&#10;Fonctionnalité 2&#10;..."></textarea>
                </div>

                <div class="form-group">
                    <label>Lien parent (bundle)</label>
                    <select name="parent_id" id="p-parent">
                        <option value="">Aucun (produit principal)</option>
                        <?php foreach ($allProducts as $product): ?>
                            <option value="<?php echo (int)$product['id']; ?>">#<?php echo (int)$product['id']; ?> — <?php echo e($product['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Type de lien</label>
                    <select name="link_type" id="p-linktype">
                        <option value="">—</option>
                        <option value="control_software">Logiciel de pilotage</option>
                        <option value="accessory">Accessoire</option>
                    </select>
                </div>

                <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group"><label>Site web</label><input type="text" name="website_url" id="p-website"></div>
                    <div class="form-group"><label>Documentation</label><input type="text" name="documentation_url" id="p-docs"></div>
                    <div class="form-group"><label>Google Play</label><input type="text" name="play_store_url" id="p-play"></div>
                    <div class="form-group"><label>App Store</label><input type="text" name="app_store_url" id="p-app"></div>
                </div>

                <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group"><label>Icône (fichier)</label><input type="file" name="icon" accept="image/*"></div>
                    <div class="form-group"><label>Image (fichier)</label><input type="file" name="image" accept="image/*"></div>
                </div>
                <div class="form-group">
                    <label>Fichier du produit</label>
                    <input type="file" name="file" id="p-file">
                </div>

                <div class="form-row" style="display:flex; gap:24px; flex-wrap:wrap; margin-top:10px;">
                    <label class="checkbox"><input type="checkbox" name="is_featured" id="p-featured"><span class="checkmark"></span> À la une</label>
                    <label class="checkbox"><input type="checkbox" name="is_active" id="p-active" checked><span class="checkmark"></span> Actif</label>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeProductModal()">Annuler</button>
            <button class="btn btn-primary" id="saveProductBtn"><i class="fas fa-save"></i> Enregistrer</button>
        </div>
    </div>
</div>

<script>
let productsData = <?php echo json_encode($allProducts); ?>;
let editingProductId = null;

function openProductModal(id) {
    editingProductId = id;
    const form = document.getElementById('productForm');
    form.reset();
    document.getElementById('p-active').checked = true;
    document.getElementById('p-pricing').value = 'one_time';
    togglePricingFields();

    if (id) {
        const p = productsData.find(x => String(x.id) === String(id));
        if (!p) return;
        document.getElementById('modalTitle').textContent = 'Modifier le produit';
        document.getElementById('p-id').value = p.id;
        document.getElementById('p-name').value = p.name;
        document.getElementById('p-slug').value = p.slug || '';
        document.getElementById('p-category').value = p.category_id;
        document.getElementById('p-pricing').value = p.pricing_type;
        document.getElementById('p-price').value = p.price;
        document.getElementById('p-period').value = p.subscription_period || 'monthly';
        document.getElementById('p-trial').value = p.trial_days || 0;
        document.getElementById('p-version').value = p.version || '1.0.0';
        document.getElementById('p-release').value = p.release_date || '';
        document.getElementById('p-short').value = p.short_description || '';
        document.getElementById('p-description').value = p.description || '';
        document.getElementById('p-parent').value = p.parent_id || '';
        document.getElementById('p-linktype').value = p.link_type || '';
        document.getElementById('p-website').value = p.website_url || '';
        document.getElementById('p-docs').value = p.documentation_url || '';
        document.getElementById('p-play').value = p.play_store_url || '';
        document.getElementById('p-app').value = p.app_store_url || '';
        document.getElementById('p-featured').checked = !!p.is_featured;
        document.getElementById('p-active').checked = !!p.is_active;
        togglePricingFields();
        loadFeatures(id);
    } else {
        document.getElementById('modalTitle').textContent = 'Nouveau produit';
        document.getElementById('p-id').value = '';
        document.getElementById('p-features').value = '';
    }
    document.getElementById('productModal').style.display = 'flex';
}

async function loadFeatures(id) {
    try {
        const res = await fetch('/nest/app/api/products/get.php?id=' + id);
        const data = await res.json();
        if (data.success && data.product.features) {
            document.getElementById('p-features').value = data.product.features.join('\n');
        }
    } catch (e) { /* ignore */ }
}

function closeProductModal() {
    document.getElementById('productModal').style.display = 'none';
}

function togglePricingFields() {
    const v = document.getElementById('p-pricing').value;
    document.getElementById('pricingExtras').style.display = v === 'subscription' ? 'grid' : 'none';
}

document.getElementById('saveProductBtn').addEventListener('click', async function () {
    const form = document.getElementById('productForm');
    const features = form.features.value.split('\n').map(s => s.trim()).filter(Boolean);
    const fd = new FormData(form);
    fd.delete('features');
    features.forEach(f => fd.append('features[]', f));

    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';

    const url = editingProductId ? '/nest/app/api/products/update.php' : '/nest/app/api/products/create.php';
    try {
        const res = await fetch(url, { method: 'POST', body: fd });
        const data = await res.json();
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) setTimeout(() => window.location.reload(), 800);
    } catch (e) {
        showToast('Erreur réseau', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Enregistrer';
    }
});

async function deleteProduct(id, name) {
    if (!confirm(`Supprimer le produit « ${name} » ?`)) return;
    const fd = new FormData();
    fd.append('id', id);
    try {
        const res = await fetch('/nest/app/api/products/delete.php', { method: 'POST', body: fd });
        const data = await res.json();
        showToast(data.message, data.success ? 'success' : 'error');
        if (data.success) setTimeout(() => window.location.reload(), 800);
    } catch (e) {
        showToast('Erreur réseau', 'error');
    }
}

document.getElementById('productSearch').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.entity-admin-card').forEach(card => {
        card.style.display = card.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>

<?php include __DIR__ . '/../partials/admin_footer.php'; ?>
