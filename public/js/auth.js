class AuthManager {
    
    constructor() {
        this.currentTab = 'login';
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupPasswordStrength();
        this.injectFeedbackStyles(); // Injecter les styles CSS via JavaScript
    }

    // Injecter les styles CSS directement dans le DOM
    injectFeedbackStyles() {
        const styles = `
            <style>
            .auth-message {
                padding: 16px 20px !important;
                margin: 20px 0 !important;
                border-radius: 12px !important;
                display: flex !important;
                align-items: center !important;
                gap: 12px !important;
                font-size: 14px !important;
                font-weight: 600 !important;
                border: 2px solid transparent !important;
                animation: slideInDown 0.5s ease !important;
                position: relative;
                z-index: 3;
            }

            .auth-message-success {
                background: linear-gradient(135deg, rgba(0, 214, 100, 0.1), rgba(0, 214, 100, 0.08)) !important;
                color: #006b3c !important;
                border-color: rgba(0, 214, 100, 0.3) !important;
                box-shadow: 0 4px 15px rgba(0, 214, 100, 0.1) !important;
            }

            .auth-message-error {
                background: linear-gradient(135deg, rgba(255, 71, 87, 0.1), rgba(255, 71, 87, 0.08)) !important;
                color: #a61e2a !important;
                border-color: rgba(255, 71, 87, 0.3) !important;
                box-shadow: 0 4px 15px rgba(255, 71, 87, 0.1) !important;
            }

            .auth-message i {
                font-size: 18px !important;
                flex-shrink: 0;
                width: 20px;
                text-align: center;
            }

            .auth-message-success i {
                color: #00d664 !important;
            }

            .auth-message-error i {
                color: #ff4757 !important;
            }

            .auth-forms {
                position: relative;
                transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1) !important;
            }

            .auth-forms::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: transparent;
                transform: scaleX(0);
                transition: transform 0.5s ease;
                z-index: 2;
            }

            .form-feedback-success {
                background: rgba(0, 214, 100, 0.05) !important;
                border: 2px solid rgba(0, 214, 100, 0.3) !important;
                box-shadow: 0 0 30px rgba(0, 214, 100, 0.15) !important;
                animation: gentlePulse 2s ease-in-out !important;
            }

            .form-feedback-success::before {
                background: linear-gradient(90deg, #00d664, #00ff88) !important;
                transform: scaleX(1) !important;
            }

            .form-feedback-error {
                background: rgba(255, 71, 87, 0.05) !important;
                border: 2px solid rgba(255, 71, 87, 0.3) !important;
                box-shadow: 0 0 30px rgba(255, 71, 87, 0.15) !important;
                animation: gentlePulse 2s ease-in-out !important;
            }

            .form-feedback-error::before {
                background: linear-gradient(90deg, #ff4757, #ff6b81) !important;
                transform: scaleX(1) !important;
            }

            @keyframes slideInDown {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes gentlePulse {
                0%, 100% {
                    transform: scale(1);
                }
                50% {
                    transform: scale(1.002);
                }
            }
            </style>
        `;
        
        document.head.insertAdjacentHTML('beforeend', styles);
    }

    setupEventListeners() {
        // Navigation entre tabs
        document.querySelectorAll('.auth-tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                this.switchTab(e.target.closest('.auth-tab').dataset.tab);
            });
        });

        // Switch tab depuis les liens
        document.querySelectorAll('.switch-tab').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                this.switchTab(e.target.dataset.tab);
            });
        });

        // Toggle password visibility
        document.querySelectorAll('.password-toggle').forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                this.togglePasswordVisibility(e.target.closest('.password-toggle'));
            });
        });

        // Form submission
        document.getElementById('login-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleLogin();
        });

        document.getElementById('register-form').addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleRegister();
        });

        // Password strength
        document.getElementById('register-password').addEventListener('input', (e) => {
            this.updatePasswordStrength(e.target.value);
        });

        // Demo account auto-fill
        this.setupDemoAccount();
    }

    switchTab(tabName) {
        // Update tabs
        document.querySelectorAll('.auth-tab').forEach(tab => {
            tab.classList.toggle('active', tab.dataset.tab === tabName);
        });

        // Update forms
        document.querySelectorAll('.auth-form').forEach(form => {
            form.classList.toggle('active', form.dataset.form === tabName);
        });

        this.currentTab = tabName;
    }

    togglePasswordVisibility(toggleButton) {
        const formGroup = toggleButton.closest('.form-group');
        const passwordInput = formGroup.querySelector('input[type="password"]');
        const icon = toggleButton.querySelector('i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    updatePasswordStrength(password) {
        const strengthBar = document.querySelector('.strength-fill');
        const strengthText = document.querySelector('.strength-text span');
        
        let strength = 0;
        let text = 'Faible';
        let color = '#ff4757';

        if (password.length >= 8) strength += 25;
        if (/[A-Z]/.test(password)) strength += 25;
        if (/[0-9]/.test(password)) strength += 25;
        if (/[^A-Za-z0-9]/.test(password)) strength += 25;

        if (strength >= 75) {
            text = 'Fort';
            color = '#00d664';
        } else if (strength >= 50) {
            text = 'Moyen';
            color = '#ff9500';
        }

        strengthBar.style.width = `${strength}%`;
        strengthBar.style.backgroundColor = color;
        strengthBar.dataset.strength = strength;
        strengthText.textContent = text;
        strengthText.style.color = color;
    }

    setupDemoAccount() {
        // Plus de compte de démonstration pré-créé :
        // le premier compte créé devient automatiquement administrateur.
    }

    async handleLogin() {
        const formData = {
            email: document.getElementById('login-email').value.trim(),
            password: document.getElementById('login-password').value,
            remember_me: document.getElementById('remember-me').checked
        };

        console.log('🔄 Tentative de connexion:', formData.email);

        // Validation
        if (!this.validateEmail(formData.email)) {
            this.showError('login', 'Veuillez entrer une adresse email valide');
            return;
        }

        if (!formData.password) {
            this.showError('login', 'Veuillez entrer votre mot de passe');
            return;
        }

        const submitBtn = document.querySelector('#login-form .auth-button');
        this.setLoadingState(submitBtn, true);

        try {
            console.log('📡 Envoi requête login...');
            
            const response = await fetch('/nest/app/api/auth/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            console.log('✅ Réponse reçue, status:', response.status);
            
            // Vérifier si la réponse est OK
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('📦 Données reçues:', data);

            if (data.success) {
                console.log('🎉 Connexion réussie');
                this.showSuccess('login', 'Connexion réussie ! Redirection...');
                const role = (data.user && data.user.role) || '';
                const target = (role === 'admin' || role === 'super_admin') ? '/nest/?page=admin' : '/nest/';
                setTimeout(() => {
                    window.location.href = target;
                }, 1500);
            } else {
                console.log('❌ Erreur connexion:', data.message);
                this.showError('login', data.message || 'Erreur de connexion');
            }
        } catch (error) {
            console.error('💥 Erreur fetch:', error);
            this.showError('login', 'Erreur de connexion au serveur: ' + error.message);
        } finally {
            this.setLoadingState(submitBtn, false);
        }
    }

    async handleRegister() {
        const formData = {
            name: document.getElementById('register-name').value.trim(),
            email: document.getElementById('register-email').value.trim(),
            password: document.getElementById('register-password').value,
            accepted_terms: document.getElementById('accept-terms').checked,
            newsletter_subscribed: document.getElementById('newsletter').checked
        };

        // Validation
        const errors = this.validateRegisterForm(formData);
        if (errors.length > 0) {
            this.showError('register', errors[0]);
            return;
        }

        // Vérification des conditions (déjà faite dans l'API mais on double au frontend)
        if (!formData.accepted_terms) {
            this.showError('register', 'Vous devez accepter les conditions d\'utilisation');
            return;
        }

        const submitBtn = document.querySelector('#register-form .auth-button');
        this.setLoadingState(submitBtn, true);

        try {
            const response = await fetch('/nest/app/api/auth/register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (data.success) {
                // Message serveur (ex. premier utilisateur devenu administrateur)
                let successMessage = data.message || 'Compte créé avec succès ! Redirection vers la connexion...';

                // Message personnalisé si newsletter activée
                if (formData.newsletter_subscribed && !data.message) {
                    successMessage += ' Vous êtes inscrit à notre newsletter.';
                }
                
                this.showSuccess('register', successMessage);
                
                // Reset form
                document.getElementById('register-form').reset();
                this.updatePasswordStrength('');
                
                // Redirection vers login après 2 secondes
                setTimeout(() => {
                    this.switchTab('login');
                    // Pré-remplir l'email dans le formulaire de login
                    document.getElementById('login-email').value = formData.email;
                }, 2000);
            } else {
                this.showError('register', data.message || 'Erreur lors de la création du compte');
            }
        } catch (error) {
            console.error('Register error:', error);
            this.showError('register', 'Erreur de connexion au serveur');
        } finally {
            this.setLoadingState(submitBtn, false);
        }
    }

    validateRegisterForm(formData) {
        const errors = [];

        if (!formData.name || formData.name.length < 2) {
            errors.push('Le nom doit contenir au moins 2 caractères');
        }

        if (!this.validateEmail(formData.email)) {
            errors.push('Veuillez entrer une adresse email valide');
        }

        if (!formData.password || formData.password.length < 8) {
            errors.push('Le mot de passe doit contenir au moins 8 caractères');
        }

        const passwordStrength = document.querySelector('.strength-fill').dataset.strength;
        if (parseInt(passwordStrength) < 50) {
            errors.push('Veuillez choisir un mot de passe plus fort');
        }

        return errors;
    }

    validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    setLoadingState(button, isLoading) {
        if (isLoading) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Chargement...</span>';
        } else {
            button.disabled = false;
            if (this.currentTab === 'login') {
                button.innerHTML = '<span>Se connecter</span><i class="fas fa-arrow-right"></i>';
            } else {
                button.innerHTML = '<span>Créer mon compte</span><i class="fas fa-rocket"></i>';
            }
        }
    }

    showSuccess(formType, message) {
        this.showFormFeedback(formType, message, 'success');
    }

    showError(formType, message) {
        this.showFormFeedback(formType, message, 'error');
    }

    showFormFeedback(formType, message, type) {
        console.log('🔄 Showing feedback:', formType, type, message);
        
        // Remove existing messages and feedback
        this.removeMessages();
        this.removeFormFeedback();

        const form = document.getElementById(`${formType}-form`);
        if (!form) {
            console.error('❌ Form not found:', `${formType}-form`);
            return;
        }

        const formContainer = form.closest('.auth-forms');
        if (!formContainer) {
            console.error('❌ Form container not found');
            return;
        }
        
        // Add visual feedback to the form container with forced styles
        formContainer.classList.add(`form-feedback-${type}`);
        console.log('✅ Added class to form container');
        
        // Create message with inline styles as backup
        const messageDiv = document.createElement('div');
        messageDiv.className = `auth-message auth-message-${type}`;
        
        // Styles inline en backup
        if (type === 'success') {
            messageDiv.style.cssText = `
                padding: 16px 20px !important;
                margin: 20px 0 !important;
                border-radius: 12px !important;
                display: flex !important;
                align-items: center !important;
                gap: 12px !important;
                font-size: 14px !important;
                font-weight: 600 !important;
                background: linear-gradient(135deg, rgba(0, 214, 100, 0.1), rgba(0, 214, 100, 0.08)) !important;
                color: #006b3c !important;
                border: 2px solid rgba(0, 214, 100, 0.3) !important;
                box-shadow: 0 4px 15px rgba(0, 214, 100, 0.1) !important;
                animation: slideInDown 0.5s ease !important;
            `;
        } else {
            messageDiv.style.cssText = `
                padding: 16px 20px !important;
                margin: 20px 0 !important;
                border-radius: 12px !important;
                display: flex !important;
                align-items: center !important;
                gap: 12px !important;
                font-size: 14px !important;
                font-weight: 600 !important;
                background: linear-gradient(135deg, rgba(255, 71, 87, 0.1), rgba(255, 71, 87, 0.08)) !important;
                color: #a61e2a !important;
                border: 2px solid rgba(255, 71, 87, 0.3) !important;
                box-shadow: 0 4px 15px rgba(255, 71, 87, 0.1) !important;
                animation: slideInDown 0.5s ease !important;
            `;
        }

        messageDiv.innerHTML = `
            <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'}" 
               style="font-size: 18px !important; color: ${type === 'success' ? '#00d664' : '#ff4757'} !important;">
            </i>
            <span>${message}</span>
        `;

        // Insert message after form header
        const formHeader = form.querySelector('.form-header');
        if (formHeader) {
            formHeader.parentNode.insertBefore(messageDiv, formHeader.nextSibling);
            console.log('✅ Message inserted after header');
        } else {
            form.insertBefore(messageDiv, form.firstChild);
            console.log('✅ Message inserted at form start');
        }

        // Remove feedback after 5 seconds
        setTimeout(() => {
            console.log('⏰ Removing feedback');
            this.removeFormFeedback();
            if (messageDiv.parentNode) {
                messageDiv.style.opacity = '0';
                messageDiv.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    if (messageDiv.parentNode) {
                        messageDiv.remove();
                        console.log('🗑️ Message removed');
                    }
                }, 500);
            }
        }, 5000);
    }

    removeFormFeedback() {
        document.querySelectorAll('.auth-forms').forEach(container => {
            container.classList.remove('form-feedback-success', 'form-feedback-error');
            // Reset les styles inline
            container.style.background = '';
            container.style.border = '';
            container.style.boxShadow = '';
        });
    }

    removeMessages() {
        document.querySelectorAll('.auth-message').forEach(msg => {
            msg.remove();
        });
    }

        // Dans la classe AuthManager, ajoutez:
    setupSessionCheck() {
        // Vérifier la session au chargement
        this.checkSession();
        
        // Vérifier périodiquement (toutes les 10 minutes)
        setInterval(() => this.checkSession(), 600000);
    }

    async checkSession() {
        try {
            const response = await fetch('/nest/app/api/auth/check-session.php');
            const data = await response.json();
            
            if (data.authenticated && window.location.pathname.includes('login')) {
                // Rediriger vers l'espace approprié si déjà connecté
                const role = (data.user && data.user.role) || '';
                const target = (role === 'admin' || role === 'super_admin') ? '/nest/?page=admin' : '/nest/';
                window.location.href = target;
            }
        } catch (error) {
            console.error('Session check error:', error);
        }
    }

    // Et modifiez l'init:
    init() {
        this.setupEventListeners();
        this.setupPasswordStrength();
        this.injectFeedbackStyles();
        this.setupSessionCheck(); // ← Ajouter cette ligne
    }
}

// Fonctions de test globales
window.testAuthSuccess = () => {
    const authManager = new AuthManager();
    authManager.showSuccess('register', 'TEST SUCCÈS - Compte créé avec succès !');
};

window.testAuthError = () => {
    const authManager = new AuthManager();
    authManager.showError('register', 'TEST ERREUR - Email déjà utilisé');
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new AuthManager();
    console.log('🚀 AuthManager initialized - Test with: testAuthSuccess() or testAuthError()');
});