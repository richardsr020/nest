// Gestion des entités - Administration
document.addEventListener('DOMContentLoaded', function() {
    initializeEntitiesFilters();
    initializeEntityForm();
});

// Filtres et recherche
function initializeEntitiesFilters() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const typeFilter = document.getElementById('typeFilter');
    
    if (searchInput) {
        searchInput.addEventListener('input', filterEntities);
    }
    
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterEntities);
    }
    
    if (typeFilter) {
        typeFilter.addEventListener('change', filterEntities);
    }
}

function filterEntities() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    
    const entities = document.querySelectorAll('.entity-admin-card');
    
    entities.forEach(entity => {
        const name = entity.querySelector('h3').textContent.toLowerCase();
        const category = entity.getAttribute('data-category');
        const type = entity.getAttribute('data-type');
        
        const matchesSearch = name.includes(searchTerm);
        const matchesCategory = !categoryFilter || category === categoryFilter;
        const matchesType = !typeFilter || type === typeFilter;
        
        if (matchesSearch && matchesCategory && matchesType) {
            entity.style.display = 'block';
        } else {
            entity.style.display = 'none';
        }
    });
}

// Modal et formulaire
let currentEditingId = null;

function openEntityModal(entityId = null) {
    currentEditingId = entityId;
    const modal = document.getElementById('entityModal');
    const title = document.getElementById('modalTitle');
    const submitText = document.getElementById('submitButtonText');
    
    if (entityId) {
        title.textContent = 'Modifier l\'Entité';
        submitText.textContent = 'Mettre à jour';
        loadEntityData(entityId);
    } else {
        title.textContent = 'Nouvelle Entité';
        submitText.textContent = 'Créer l\'entité';
        resetEntityForm();
    }
    
    modal.style.display = 'block';
}

function closeEntityModal() {
    const modal = document.getElementById('entityModal');
    modal.style.display = 'none';
    currentEditingId = null;
    resetEntityForm();
}

function resetEntityForm() {
    document.getElementById('entityForm').reset();
    document.getElementById('featuresContainer').innerHTML = `
        <div class="feature-input">
            <input type="text" name="features[]" placeholder="Ajouter une fonctionnalité">
            <button type="button" class="btn-icon" onclick="addFeatureField()">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    `;
    hideAllTypeFields();
}

function hideAllTypeFields() {
    document.querySelectorAll('.type-fields').forEach(field => {
        field.style.display = 'none';
    });
}

function toggleTypeFields() {
    const type = document.getElementById('entityType').value;
    hideAllTypeFields();
    
    if (type) {
        document.getElementById(`${type}Fields`).style.display = 'block';
    }
}

function addFeatureField() {
    const container = document.getElementById('featuresContainer');
    const newField = document.createElement('div');
    newField.className = 'feature-input';
    newField.innerHTML = `
        <input type="text" name="features[]" placeholder="Ajouter une fonctionnalité">
        <button type="button" class="btn-icon danger" onclick="removeFeatureField(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(newField);
}

function removeFeatureField(button) {
    button.parentElement.remove();
}

// Initialisation du formulaire
function initializeEntityForm() {
    const form = document.getElementById('entityForm');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        await submitEntityForm();
    });
}

// Soumission du formulaire
async function submitEntityForm() {
    const form = document.getElementById('entityForm');
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Afficher le loading
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>En cours...</span>';
    submitButton.disabled = true;
    
    try {
        const url = currentEditingId ? 
            `/nest-software/api/admin/entities/${currentEditingId}` : 
            '/nest-software/api/admin/entities';
            
        const method = currentEditingId ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(
                currentEditingId ? 'Entité mise à jour avec succès' : 'Entité créée avec succès', 
                'success'
            );
            closeEntityModal();
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message);
        }
        
    } catch (error) {
        showNotification(error.message, 'error');
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    }
}

// Chargement des données d'une entité
async function loadEntityData(entityId) {
    try {
        const response = await fetch(`/nest-software/api/admin/entities/${entityId}`);
        const data = await response.json();
        
        if (data.success) {
            populateEntityForm(data.data);
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        showNotification('Erreur lors du chargement des données', 'error');
    }
}

function populateEntityForm(entity) {
    document.getElementById('entityName').value = entity.name;
    document.getElementById('entityCategory').value = entity.category_id;
    document.getElementById('entityType').value = entity.type;
    document.getElementById('entityVersion').value = entity.version || '';
    document.getElementById('entityDescription').value = entity.description;
    document.getElementById('entityShortDescription').value = entity.short_description || '';
    document.getElementById('websiteUrl').value = entity.website_url || '';
    document.getElementById('playStoreUrl').value = entity.play_store_url || '';
    document.getElementById('appStoreUrl').value = entity.app_store_url || '';
    document.getElementById('isFeatured').checked = entity.is_featured;
    document.getElementById('isActive').checked = entity.is_active;
    
    // Charger les fonctionnalités
    const featuresContainer = document.getElementById('featuresContainer');
    featuresContainer.innerHTML = '';
    
    if (entity.features && entity.features.length > 0) {
        entity.features.forEach((feature, index) => {
            const featureField = document.createElement('div');
            featureField.className = 'feature-input';
            featureField.innerHTML = `
                <input type="text" name="features[]" value="${feature.feature_text}" placeholder="Ajouter une fonctionnalité">
                <button type="button" class="btn-icon ${index === 0 ? '' : 'danger'}" 
                        onclick="${index === 0 ? 'addFeatureField()' : 'removeFeatureField(this)'}">
                    <i class="fas fa-${index === 0 ? 'plus' : 'times'}"></i>
                </button>
            `;
            featuresContainer.appendChild(featureField);
        });
    } else {
        addFeatureField();
    }
    
    toggleTypeFields();
}

// Suppression d'une entité
async function deleteEntity(entityId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette entité ? Cette action est irréversible.')) {
        return;
    }
    
    try {
        const response = await fetch(`/nest-software/api/admin/entities/${entityId}`, {
            method: 'DELETE'
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Entité supprimée avec succès', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        showNotification(error.message, 'error');
    }
}

// Édition d'une entité
function editEntity(entityId) {
    openEntityModal(entityId);
}

// Fermer le modal en cliquant à l'extérieur
window.addEventListener('click', function(e) {
    const modal = document.getElementById('entityModal');
    if (e.target === modal) {
        closeEntityModal();
    }
});

console.log('Admin Entities loaded successfully');