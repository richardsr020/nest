// Configuration de l'API
const API_BASE_URL = '/api';

// Textes pour le diaporama
const textSlides = [
    "Votre écosystème numérique complet",
    "Connecter talents et opportunités",
    "Simplifier vos achats en ligne", 
    "Sécuriser vos transactions",
    "Faciliter vos communications"
];

let currentSlide = 0;

// Fonction pour changer le texte animé
function changeText() {
    const animatedText = document.getElementById('animated-text');
    if (!animatedText) return;
    
    animatedText.style.opacity = 0;
    
    setTimeout(() => {
        currentSlide = (currentSlide + 1) % textSlides.length;
        animatedText.textContent = textSlides[currentSlide];
        animatedText.style.opacity = 1;
    }, 500);
}

// Navigation entre les pages
function navigateTo(page) {
    window.location.href = page;
}

// Navbar scroll effect
function initNavbarScroll() {
    const navbar = document.getElementById('navbar');
    if (!navbar) return;
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le diaporama de texte
    const animatedText = document.getElementById('animated-text');
    if (animatedText) {
        // Démarrer immédiatement
        changeText();
        // Puis continuer toutes les 3 secondes
        setInterval(changeText, 3000);
    }
    
    // Initialiser la navbar
    initNavbarScroll();
    
    // Gestionnaire pour le bouton "Commencer"
    const ctaButton = document.getElementById('cta-button');
    if (ctaButton) {
        ctaButton.addEventListener('click', function(e) {
            e.preventDefault();
            navigateTo('/auth');
        });
    }
    
    // Gestionnaires de navigation
    const homeLink = document.getElementById('home-link');
    const authLink = document.getElementById('auth-link');
    const entitiesLink = document.getElementById('entities-link');
    
    if (homeLink) {
        homeLink.addEventListener('click', function(e) {
            e.preventDefault();
            navigateTo('/');
        });
    }
    
    if (authLink) {
        authLink.addEventListener('click', function(e) {
            e.preventDefault();
            navigateTo('/auth');
        });
    }
    
    if (entitiesLink) {
        entitiesLink.addEventListener('click', function(e) {
            e.preventDefault();
            navigateTo('/entities');
        });
    }
    
    console.log('Nest Software Corporation - Initialisé');
});