<?php
// nest/app/views/partials/nav.php
$navCurrent = $_GET['page'] ?? 'home';
?>
<nav class="site-nav" id="siteNav">
    <div class="container">
        <div class="nav-inner">
            <a href="<?php echo url('home'); ?>" class="nav-logo">
                <span class="logo-badge">N</span>
                <span>Nest<?php echo '<span class="gradient-text">Corporation</span>'; ?></span>
            </a>

            <div class="nav-links" id="navLinks">
                <a href="<?php echo url('home'); ?>" class="nav-link <?php echo $navCurrent === 'home' ? 'active' : ''; ?>">Accueil</a>
                <a href="<?php echo url('services'); ?>" class="nav-link <?php echo $navCurrent === 'services' ? 'active' : ''; ?>">Services</a>
                <a href="<?php echo url('catalog'); ?>" class="nav-link <?php echo $navCurrent === 'catalog' || $navCurrent === 'product' ? 'active' : ''; ?>">Catalogue</a>
                <a href="<?php echo url('projects'); ?>" class="nav-link <?php echo $navCurrent === 'projects' ? 'active' : ''; ?>">Projets</a>
                <a href="<?php echo url('about'); ?>" class="nav-link <?php echo $navCurrent === 'about' ? 'active' : ''; ?>">À propos</a>
                <a href="<?php echo url('contact'); ?>" class="nav-link <?php echo $navCurrent === 'contact' ? 'active' : ''; ?>">Contact</a>

                <?php if (isAuthenticated()): ?>
                    <div class="nav-user">
                        <?php if (isAdmin()): ?>
                            <a href="<?php echo url('admin'); ?>" class="nav-cta"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        <?php else: ?>
                            <a href="<?php echo url('admin'); ?>" class="nav-cta"><i class="fas fa-user"></i> Mon espace</a>
                        <?php endif; ?>
                        <a href="/nest/app/api/auth/logout.php" class="nav-cta ghost"><i class="fas fa-sign-out-alt"></i></a>
                    </div>
                <?php else: ?>
                    <a href="<?php echo url('auth'); ?>" class="nav-cta"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                <?php endif; ?>
            </div>

            <button class="nav-burger" id="navBurger" aria-label="Menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</nav>
