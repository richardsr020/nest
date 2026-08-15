<?php
// nest/app/views/pages/404.php
include __DIR__ . '/../partials/header.php';
?>
<section class="page-hero">
    <div class="container" style="text-align:center;">
        <div class="error-code">404</div>
        <h1>Page <span class="gradient-text">introuvable</span></h1>
        <p>La page que vous cherchez n'existe pas ou a été déplacée.</p>
        <div class="hero-actions" style="justify-content:center; margin-top:32px;">
            <a href="<?php echo url('home'); ?>" class="btn btn-dark"><i class="fas fa-home"></i> Retour à l'accueil</a>
            <a href="<?php echo url('catalog'); ?>" class="btn btn-outline">Voir le catalogue</a>
        </div>
    </div>
</section>
<?php include __DIR__ . '/../partials/footer.php'; ?>
