// Service API pour communiquer avec le backend PHP
class ApiService {
    constructor() {
        this.baseUrl = '/api';
    }

    async request(endpoint, options = {}) {
        const url = `${this.baseUrl}/${endpoint}`;
        
        const config = {
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            ...options
        };

        if (config.body && typeof config.body === 'object') {
            config.body = JSON.stringify(config.body);
        }

        try {
            const response = await fetch(url, config);
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'Erreur API');
            }
            
            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    // Méthodes pour l'authentification
    async login(email, password) {
        return this.request('auth/login.php', {
            method: 'POST',
            body: { email, password }
        });
    }

    async register(userData) {
        return this.request('auth/register.php', {
            method: 'POST',
            body: userData
        });
    }

    // Méthodes pour les entités
    async getEntities() {
        return this.request('entities/list.php');
    }

    async getEntity(id) {
        return this.request(`entities/get.php?id=${id}`);
    }
}

// Instance globale du service API
const apiService = new ApiService();