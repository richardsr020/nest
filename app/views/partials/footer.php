<?php
// nest/app/views/partials/footer.php
?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="<?php echo url('home'); ?>" class="nav-logo">
                    <span class="logo-badge">N</span>
                    <span>Nest<?php echo '<span class="gradient-text">Corporation</span>'; ?></span>
                </a>
                <p>Ingénierie logicielle et électronique. Conception, réalisation et fabrication de machines et d'appareils intelligents en Afrique.</p>
            </div>
            <div class="footer-col">
                <h4>Navigation</h4>
                <ul>
                    <li><a href="<?php echo url('home'); ?>">Accueil</a></li>
                    <li><a href="<?php echo url('services'); ?>">Services</a></li>
                    <li><a href="<?php echo url('catalog'); ?>">Catalogue</a></li>
                    <li><a href="<?php echo url('projects'); ?>">Projets</a></li>
                    <li><a href="<?php echo url('about'); ?>">À propos</a></li>
                    <li><a href="<?php echo url('contact'); ?>">Contact</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Catalogue</h4>
                <ul>
                    <li><a href="<?php echo url('catalog', ['type' => 'desktop']); ?>">Logiciels PC</a></li>
                    <li><a href="<?php echo url('catalog', ['type' => 'saas']); ?>">Logiciels en ligne</a></li>
                    <li><a href="<?php echo url('catalog', ['type' => 'android']); ?>">Applications Android</a></li>
                    <li><a href="<?php echo url('catalog', ['type' => 'hardware']); ?>">Appareils électroniques</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <ul>
                    <li><a href="mailto:<?php echo APP_EMAIL; ?>"><i class="fas fa-envelope"></i> <?php echo APP_EMAIL; ?></a></li>
                    <li><a href="tel:<?php echo str_replace(' ', '', APP_PHONE); ?>"><i class="fas fa-phone"></i> <?php echo APP_PHONE; ?></a></li>
                    <li><i class="fas fa-map-marker-alt"></i> <?php echo APP_LOCATION; ?></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© <?php echo date('Y'); ?> <?php echo APP_NAME; ?> Corporation. Tous droits réservés.</p>
        </div>
    </div>
</footer>
<script src="<?php echo js('main.js'); ?>"></script>
</body>
</html>
