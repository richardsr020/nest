class AdminDashboard {
    constructor() {
        this.stats = {};
        this.init();
    }

    async init() {
        try {
            await this.loadUserInfo();
            await this.loadDashboardData();
            this.initCharts();
            this.setupEventListeners();
        } catch (error) {
            console.error('Erreur initialisation dashboard:', error);
            this.showError('Erreur de chargement du dashboard');
        }
    }

    async loadUserInfo() {
        try {
            const response = await fetch('/nest/app/api/auth/current-user.php');
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('currentUserName').textContent = data.user.name;
            } else {
                // Rediriger vers la page de login si non authentifié
                window.location.href = '/nest/?page=login';
            }
        } catch (error) {
            console.error('Erreur chargement utilisateur:', error);
        }
    }

    async loadDashboardData() {
        try {
            const response = await fetch('/nest/app/api/admin/dashboard.php');
            const data = await response.json();
            
            if (data.success) {
                this.stats = data.stats;
                this.updateStatsCards();
                this.updateRecentEntities(data.recentEntities);
                this.updateRecentLogs(data.recentLogs);
                this.updateCharts(data);
            } else {
                this.showError(data.message || 'Erreur de chargement des données');
            }
        } catch (error) {
            console.error('Erreur chargement données:', error);
            this.showError('Erreur de connexion au serveur');
        }
    }

    updateStatsCards() {
        document.getElementById('totalUsers').textContent = this.stats.total_users || 0;
        document.getElementById('totalEntities').textContent = this.stats.total_entities || 0;
        document.getElementById('totalDownloads').textContent = this.stats.total_downloads || 0;
        document.getElementById('totalActive').textContent = this.stats.total_active || 0;
    }

    updateRecentEntities(entities) {
        const container = document.getElementById('recentEntities');
        
        if (!entities || entities.length === 0) {
            container.innerHTML = '<div class="no-data">Aucune entité récente</div>';
            return;
        }

        container.innerHTML = entities.map(entity => `
            <div class="recent-item">
                <div class="item-icon">
                    <i class="fas fa-${this.getEntityIcon(entity.type)}"></i>
                </div>
                <div class="item-content">
                    <h4>${this.escapeHtml(entity.name)}</h4>
                    <span class="item-meta">${this.escapeHtml(entity.category_name)}</span>
                </div>
                <div class="item-badge ${entity.type}">
                    ${entity.type.toUpperCase()}
                </div>
            </div>
        `).join('');
    }

    updateRecentLogs(logs) {
        const container = document.getElementById('recentLogs');
        
        if (!logs || logs.length === 0) {
            container.innerHTML = '<div class="no-data">Aucun log récent</div>';
            return;
        }

        container.innerHTML = logs.map(log => `
            <div class="log-item">
                <div class="log-icon">
                    <i class="fas fa-${this.getLogIcon(log.action)}"></i>
                </div>
                <div class="log-content">
                    <p>${this.escapeHtml(log.description)}</p>
                    <span class="log-meta">
                        Par ${this.escapeHtml(log.user_name)} • 
                        ${this.timeAgo(log.created_at)}
                    </span>
                </div>
            </div>
        `).join('');
    }

    initCharts() {
        // Initialiser les canvas pour les graphiques
        this.activityChart = new Chart(
            document.getElementById('activityChart'),
            { type: 'line', data: { labels: [], datasets: [] } }
        );
        
        this.categoryChart = new Chart(
            document.getElementById('categoryChart'),
            { type: 'doughnut', data: { labels: [], datasets: [] } }
        );
    }

    updateCharts(data) {
        // Graphique d'activité
        if (data.activityData) {
            this.activityChart.data = {
                labels: data.activityData.labels,
                datasets: [{
                    label: 'Activité',
                    data: data.activityData.values,
                    borderColor: '#0072ff',
                    backgroundColor: 'rgba(0, 114, 255, 0.1)',
                    tension: 0.4
                }]
            };
            this.activityChart.update();
        }

        // Graphique de répartition
        if (data.categoryDistribution) {
            this.categoryChart.data = {
                labels: data.categoryDistribution.labels,
                datasets: [{
                    data: data.categoryDistribution.values,
                    backgroundColor: ['#0072ff', '#00c6ff', '#667eea', '#764ba2']
                }]
            };
            this.categoryChart.update();
        }
    }

    getEntityIcon(type) {
        const icons = {
            'saas': 'cloud',
            'desktop': 'desktop',
            'mobile': 'mobile-alt'
        };
        return icons[type] || 'cube';
    }

    getLogIcon(action) {
        const icons = {
            'create': 'plus-circle',
            'update': 'edit',
            'delete': 'trash',
            'login': 'sign-in-alt'
        };
        return icons[action] || 'info-circle';
    }

    timeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffMins < 1) return 'À l\'instant';
        if (diffMins < 60) return `Il y a ${diffMins} min`;
        if (diffHours < 24) return `Il y a ${diffHours} h`;
        if (diffDays < 7) return `Il y a ${diffDays} j`;
        return date.toLocaleDateString('fr-FR');
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    showError(message) {
        // Créer une notification d'erreur
        const errorDiv = document.createElement('div');
        errorDiv.className = 'alert alert-danger';
        errorDiv.style.cssText = 'position:fixed; top:20px; right:20px; z-index:10000;';
        errorDiv.textContent = message;
        document.body.appendChild(errorDiv);

        setTimeout(() => errorDiv.remove(), 5000);
    }

    setupEventListeners() {
        // Auto-refresh toutes les 30 secondes
        setInterval(() => this.loadDashboardData(), 30000);
    }
}

// Démarrer le dashboard quand la page est chargée
document.addEventListener('DOMContentLoaded', () => {
    new AdminDashboard();
});