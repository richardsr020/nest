<!-- Navigation secondaire sticky -->
<nav class="entities-nav" id="entitiesNav">
    <div class="container">
        <div class="entities-nav-content">
            <a href="<?php echo url(''); ?>" class="back-button">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
            
            <div class="nav-categories">
                <a href="#saas" class="nav-category active">
                    <i class="fas fa-cloud"></i>
                    <span>SaaS</span>
                </a>
                <a href="#bureau" class="nav-category">
                    <i class="fas fa-desktop"></i>
                    <span>Bureau</span>
                </a>
                <a href="#mobile" class="nav-category">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Mobile</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section Entités -->
<section class="entities-hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Notre Écosystème Technologique</h1>
            <p class="hero-description">
                Découvrez notre gamme complète de solutions innovantes conçues pour 
                transformer votre expérience numérique.
            </p>
        </div>
    </div>
</section>

<!-- Section Logiciels SaaS -->
<section id="saas" class="category-section">
    <div class="container">
        <div class="section-header">
            <h2>Logiciels SaaS</h2>
            <p>Solutions cloud accessibles depuis n'importe quel navigateur</p>
        </div>
        
        <div class="entities-grid">
            <!-- Skill -->
            <div class="entity-card saas-card">
                <div class="card-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h3>Skill Platform</h3>
                <p class="card-description">
                    Plateforme intelligente de recrutement et de matching entre talents 
                    et opportunités professionnelles.
                </p>
                <div class="card-features">
                    <span class="feature-tag">Matching algorithmique</span>
                    <span class="feature-tag">Profils vérifiés</span>
                </div>
                <div class="card-actions">
                    <a href="https://skill.nest-software.com" target="_blank" class="card-button">
                        <span>Découvrir</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- i-Shopping -->
            <div class="entity-card saas-card">
                <div class="card-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>i-Shopping</h3>
                <p class="card-description">
                    Marketplace e-commerce nouvelle génération avec expérience 
                    d'achat fluide et personnalisée.
                </p>
                <div class="card-features">
                    <span class="feature-tag">Interface intuitive</span>
                    <span class="feature-tag">Recommandations IA</span>
                </div>
                <div class="card-actions">
                    <a href="https://ishopping.nest-software.com" target="_blank" class="card-button">
                        <span>Découvrir</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Mailer Pro -->
            <div class="entity-card saas-card">
                <div class="card-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Mailer Pro</h3>
                <p class="card-description">
                    Suite de messagerie professionnelle avec outils collaboratifs 
                    modernes et sécurité renforcée.
                </p>
                <div class="card-features">
                    <span class="feature-tag">Organisation intelligente</span>
                    <span class="feature-tag">Collaboration temps réel</span>
                </div>
                <div class="card-actions">
                    <a href="https://mailer.nest-software.com" target="_blank" class="card-button">
                        <span>Découvrir</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Logiciels Bureau -->
<section id="bureau" class="category-section">
    <div class="container">
        <div class="section-header">
            <h2>Logiciels Bureau</h2>
            <p>Applications performantes pour Windows, macOS et Linux</p>
        </div>
        
        <div class="entities-grid">
            <!-- Nest Analytics -->
            <div class="entity-card desktop-card">
                <div class="card-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3>Nest Analytics</h3>
                <p class="card-description">
                    Suite d'analyse de données complète avec visualisations 
                    avancées et rapports automatisés.
                </p>
                <div class="card-features">
                    <span class="feature-tag">Visualisations 3D</span>
                    <span class="feature-tag">Rapports automatiques</span>
                </div>
                <div class="card-actions">
                    <button class="card-button secondary">
                        <span>Télécharger</span>
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>

            <!-- Design Studio -->
            <div class="entity-card desktop-card">
                <div class="card-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <h3>Design Studio</h3>
                <p class="card-description">
                    Logiciel de création graphique professionnel avec outils 
                    avancés et support multiplateforme.
                </p>
                <div class="card-features">
                    <span class="feature-tag">Édition vectorielle</span>
                    <span class="feature-tag">Support PSD/AI</span>
                </div>
                <div class="card-actions">
                    <button class="card-button secondary">
                        <span>Télécharger</span>
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Applications Mobile -->
<section id="mobile" class="category-section">
    <div class="container">
        <div class="section-header">
            <h2>Applications Mobile</h2>
            <p>Solutions optimisées pour iOS et Android</p>
        </div>
        
        <div class="entities-grid">
            <!-- Pay & Wise Mobile -->
            <div class="entity-card mobile-card">
                <div class="card-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <h3>Pay & Wise</h3>
                <p class="card-description">
                    Application de paiement mobile sécurisée avec gestion 
                    de budget et analyse financière.
                </p>
                <div class="card-features">
                    <span class="feature-tag">Paiement NFC</span>
                    <span class="feature-tag">Gestion budget</span>
                </div>
                <div class="card-actions">
                    <div class="store-buttons">
                        <a href="#" class="store-button">
                            <i class="fab fa-apple"></i>
                            <span>App Store</span>
                        </a>
                        <a href="#" class="store-button">
                            <i class="fab fa-google-play"></i>
                            <span>Play Store</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- TaskFlow -->
            <div class="entity-card mobile-card">
                <div class="card-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3>TaskFlow</h3>
                <p class="card-description">
                    Application de gestion de tâches collaborative avec 
                    synchronisation cloud et rappels intelligents.
                </p>
                <div class="card-features">
                    <span class="feature-tag">Collaboration équipe</span>
                    <span class="feature-tag">Synchronisation temps réel</span>
                </div>
                <div class="card-actions">
                    <div class="store-buttons">
                        <a href="#" class="store-button">
                            <i class="fab fa-apple"></i>
                            <span>App Store</span>
                        </a>
                        <a href="#" class="store-button">
                            <i class="fab fa-google-play"></i>
                            <span>Play Store</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section CTA -->
<section class="entities-cta">
    <div class="container">
        <div class="cta-content">
            <h2>Prêt à transformer votre expérience numérique ?</h2>
            <p>Rejoignez des milliers d'utilisateurs satisfaits de notre écosystème</p>
            <div class="cta-buttons">
                <a href="<?php echo url('auth'); ?>" class="cta-button primary">
                    <span>Créer un compte</span>
                    <i class="fas fa-rocket"></i>
                </a>
                <a href="#saas" class="cta-button secondary">
                    <span>Découvrir nos solutions</span>
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Inclusion des fichiers CSS et JS -->
<link rel="stylesheet" href="<?php echo asset('css/entities.css'); ?>">
<script>
    // Navigation et interactions pour la page entities
document.addEventListener('DOMContentLoaded', function() {
    // Navigation smooth vers les sections
    document.querySelectorAll('.nav-category').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                // Mettre à jour la navigation active
                document.querySelectorAll('.nav-category').forEach(item => {
                    item.classList.remove('active');
                });
                this.classList.add('active');
                
                // Scroll vers la section
                const offsetTop = targetSection.offsetTop - 100;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Mettre à jour la navigation active au scroll
    const sections = document.querySelectorAll('.category-section');
    const navLinks = document.querySelectorAll('.nav-category');
    
    function updateActiveNav() {
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 120;
            const sectionHeight = section.clientHeight;
            
            if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    }
    
    // Écouter le scroll
    window.addEventListener('scroll', updateActiveNav);
    
    // Animation des cards au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observer toutes les cards
    document.querySelectorAll('.entity-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
    
    // Gestion des boutons de téléchargement
    document.querySelectorAll('.card-button.secondary').forEach(button => {
        button.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Téléchargement...</span>';
            this.disabled = true;
            
            // Simulation de téléchargement
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-check"></i><span>Téléchargé</span>';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-download"></i><span>Télécharger</span>';
                    this.disabled = false;
                }, 2000);
            }, 1500);
        });
    });
    
    console.log('Entities page loaded successfully');
});
</script>