<?php
// nest/app/views/pages/contact.php
include __DIR__ . '/../partials/header.php';
?>
<section class="page-hero">
    <div class="container">
        <h1>Contactez-<span class="gradient-text">nous</span></h1>
        <p>Un projet logiciel, un appareil électronique à concevoir ? Parlons-en.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact-grid">
            <div class="animate-on-scroll">
                <h2 style="font-size:2rem; margin-bottom:16px;">Nos <span class="gradient-text">Coordonnées</span></h2>
                <p style="color:#666;">Nous répondons rapidement à toutes les demandes.</p>
                <ul class="contact-info-list">
                    <li>
                        <span class="contact-icon"><i class="fas fa-envelope"></i></span>
                        <div>
                            <strong>Email</strong><br>
                            <a href="mailto:<?php echo APP_EMAIL; ?>" style="color:var(--primary-blue);"><?php echo APP_EMAIL; ?></a>
                        </div>
                    </li>
                    <li>
                        <span class="contact-icon"><i class="fas fa-phone"></i></span>
                        <div>
                            <strong>Téléphone / WhatsApp</strong><br>
                            <a href="tel:<?php echo str_replace(' ', '', APP_PHONE); ?>"><?php echo APP_PHONE; ?></a>
                        </div>
                    </li>
                    <li>
                        <span class="contact-icon"><i class="fas fa-map-marker-alt"></i></span>
                        <div>
                            <strong>Localisation</strong><br>
                            <?php echo APP_LOCATION; ?>
                        </div>
                    </li>
                    <li>
                        <span class="contact-icon"><i class="fas fa-cube"></i></span>
                        <div>
                            <strong>Catalogue</strong><br>
                            <a href="<?php echo url('catalog'); ?>" style="color:var(--primary-blue);">Découvrir nos produits</a>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="animate-on-scroll" style="animation-delay:0.1s">
                <form id="contactForm" class="mission-card" style="border-radius:18px;">
                    <h3 style="margin-bottom:20px;">Envoyez-nous un message</h3>
                    <div class="form-group">
                        <label for="c-name">Nom complet *</label>
                        <input type="text" id="c-name" class="form-control" required placeholder="Votre nom">
                    </div>
                    <div class="form-group">
                        <label for="c-email">Email *</label>
                        <input type="email" id="c-email" class="form-control" required placeholder="votre@email.com">
                    </div>
                    <div class="form-group">
                        <label for="c-subject">Sujet</label>
                        <input type="text" id="c-subject" class="form-control" placeholder="Ex : Conception d'un objet connecté">
                    </div>
                    <div class="form-group">
                        <label for="c-message">Message *</label>
                        <textarea id="c-message" class="form-control" required placeholder="Décrivez votre projet..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-dark" style="width:100%; justify-content:center;">
                        <i class="fas fa-paper-plane"></i> Envoyer le message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="cta-band">
    <div class="container">
        <h2>Besoin d'un produit ou d'un logiciel ?</h2>
        <p>Explorez notre catalogue : gratuit, paiement unique ou abonnement.</p>
        <div class="hero-actions">
            <a href="<?php echo url('catalog'); ?>" class="btn btn-light">Explorer le catalogue</a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('contactForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...';
            // Mailto fallback simple
            const name = document.getElementById('c-name').value;
            const email = document.getElementById('c-email').value;
            const subject = encodeURIComponent(document.getElementById('c-subject').value || 'Demande via site');
            const message = encodeURIComponent('De : ' + name + ' (' + email + ')\n\n' + document.getElementById('c-message').value);
            window.location.href = 'mailto:<?php echo APP_EMAIL; ?>?subject=' + subject + '&body=' + message;
            btn.innerHTML = '<i class="fas fa-check"></i> Redirection email...';
        });
    }
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
