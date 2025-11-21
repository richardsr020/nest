<section class="auth-hero">
    <div class="container">
        <div class="auth-hero-content">
            <h1 class="auth-title">Rejoignez Notre Écosystème</h1>
            <p class="auth-description">
                Accédez à toutes nos solutions innovantes avec un seul compte Nest
            </p>
        </div>
    </div>
</section>

<section class="auth-section">
    <div class="auth-container">
        <!-- Navigation Auth -->
        <div class="auth-nav">
            <a href="<?php echo url(''); ?>" class="back-button">
                <i class="fas fa-arrow-left"></i>
                <span>Retour à l'accueil</span>
            </a>
        </div>

        <!-- Container Principal -->
        <div class="auth-main">
            <!-- Illustration -->
            <div class="auth-illustration">
                <div class="illustration-content">
                    <div class="illustration-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Sécurité Garantie</h3>
                    <p>Vos données sont protégées avec un chiffrement de niveau bancaire</p>
                    
                    <div class="features-list">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Chiffrement AES-256</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Authentification 2FA</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Conformité RGPD</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire -->
            <div class="auth-forms">
                <!-- Tabs -->
                <div class="auth-tabs">
                    <button class="auth-tab active" data-tab="login">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Connexion</span>
                    </button>
                    <button class="auth-tab" data-tab="register">
                        <i class="fas fa-user-plus"></i>
                        <span>Inscription</span>
                    </button>
                </div>

                <!-- Formulaires -->
                <div class="forms-container">
                    <!-- Login Form -->
                    <form class="auth-form active" id="login-form" data-form="login">
                        <div class="form-header">
                            <h2>Content de vous revoir</h2>
                            <p>Connectez-vous à votre compte Nest</p>
                        </div>

                        <div class="form-group">
                            <label for="login-email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                <span>Adresse Email</span>
                            </label>
                            <input 
                                type="email" 
                                id="login-email" 
                                class="form-input"
                                placeholder="votre@email.com" 
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="login-password" class="form-label">
                                <i class="fas fa-lock"></i>
                                <span>Mot de passe</span>
                            </label>
                            <input 
                                type="password" 
                                id="login-password" 
                                class="form-input"
                                placeholder="Votre mot de passe" 
                                required
                            >
                            <button type="button" class="password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <div class="form-options">
                            <label class="checkbox-container">
                                <input type="checkbox" id="remember-me">
                                <span class="checkmark"></span>
                                Se souvenir de moi
                            </label>
                            <a href="#forgot-password" class="forgot-link">Mot de passe oublié ?</a>
                        </div>

                        <button type="submit" class="auth-button primary">
                            <span>Se connecter</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>

                        <div class="demo-account">
                            <div class="demo-header">
                                <i class="fas fa-flask"></i>
                                <span>Compte de démonstration</span>
                            </div>
                            <div class="demo-credentials">
                                <div class="credential">
                                    <strong>Email:</strong> admin@nest.com
                                </div>
                                <div class="credential">
                                    <strong>Mot de passe:</strong> password
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Register Form -->
                    <form class="auth-form" id="register-form" data-form="register">
                        <div class="form-header">
                            <h2>Créer un compte</h2>
                            <p>Rejoignez notre écosystème en quelques secondes</p>
                        </div>

                        <div class="form-group">
                            <label for="register-name" class="form-label">
                                <i class="fas fa-user"></i>
                                <span>Nom complet</span>
                            </label>
                            <input 
                                type="text" 
                                id="register-name" 
                                class="form-input"
                                placeholder="Votre nom complet" 
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="register-email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                <span>Adresse Email</span>
                            </label>
                            <input 
                                type="email" 
                                id="register-email" 
                                class="form-input"
                                placeholder="votre@email.com" 
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="register-password" class="form-label">
                                <i class="fas fa-lock"></i>
                                <span>Mot de passe</span>
                            </label>
                            <input 
                                type="password" 
                                id="register-password" 
                                class="form-input"
                                placeholder="Créez un mot de passe sécurisé" 
                                required
                            >
                            <button type="button" class="password-toggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>

                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" data-strength="0"></div>
                            </div>
                            <div class="strength-text">Force du mot de passe: <span>Faible</span></div>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-container">
                                <input type="checkbox" id="accept-terms" required>
                                <span class="checkmark"></span>
                                J'accepte les <a href="#" class="link">conditions d'utilisation</a> et la <a href="#" class="link">politique de confidentialité</a>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-container">
                                <input type="checkbox" id="newsletter">
                                <span class="checkmark"></span>
                                Je souhaite recevoir les actualités et offres spéciales
                            </label>
                        </div>

                        <button type="submit" class="auth-button primary">
                            <span>Créer mon compte</span>
                            <i class="fas fa-rocket"></i>
                        </button>

                        <div class="login-redirect">
                            <p>Déjà un compte ? <a href="#" class="link switch-tab" data-tab="login">Se connecter</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Inclusion des fichiers CSS et JS -->
<link rel="stylesheet" href="<?php echo asset('css/auth.css'); ?>">
<script src="<?php echo asset('js/auth.js'); ?>"></script>