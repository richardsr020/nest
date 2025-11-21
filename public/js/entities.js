// Gestion des entités
function displayEntities(entities) {
    const grid = document.getElementById('entities-grid');
    if (!grid) return;
    
    grid.innerHTML = '';
    
    entities.forEach(entity => {
        const card = createEntityCard(entity);
        grid.appendChild(card);
    });
}

function createEntityCard(entity) {
    const card = document.createElement('div');
    card.className = 'entity-card';
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    
    const iconClass = getIconForEntity(entity.name);
    
    card.innerHTML = `
        <div class="entity-icon">
            <i class="fas ${iconClass}"></i>
        </div>
        <h3>${entity.name}</h3>
        <p>${entity.description}</p>
        <a href="${entity.url}" target="_blank" class="entity-link">Accéder à ${entity.name}</a>
    `;
    
    // Animation d'apparition
    setTimeout(() => {
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
        card.style.transition = 'all 0.5s ease';
    }, 100);
    
    return card;
}

function getIconForEntity(entityName) {
    const icons = {
        'Skill': 'fa-briefcase',
        'i-Shopping': 'fa-shopping-cart', 
        'Pay & Wise': 'fa-credit-card',
        'Mailer': 'fa-envelope'
    };
    
    return icons[entityName] || 'fa-cube';
}

async function loadEntities() {
    const grid = document.getElementById('entities-grid');
    if (!grid) return;
    
    try {
        grid.innerHTML = '<div class="loading">Chargement des entités...</div>';
        
        const result = await apiService.getEntities();
        displayEntities(result.data);
        
    } catch (error) {
        console.error('Erreur lors du chargement des entités:', error);
        grid.innerHTML = `
            <div class="error">
                Erreur lors du chargement des entités: ${error.message}
                <br><br>
                <button onclick="loadEntities()" class="entity-link">Réessayer</button>
            </div>
        `;
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    loadEntities();
    console.log('Entities module initialisé');
});


