<?php
// nest/app/views/pages/about.php
include __DIR__ . '/../partials/header.php';
?>
<section class="page-hero">
    <div class="container">
        <h1>À propos de <span class="gradient-text"><?php echo APP_NAME; ?></span></h1>
        <p>Une entreprise d'ingénierie africaine avec une ambition industrielle : devenir le premier fabricant d'objets électroniques du continent.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="mission-grid">
            <div class="mission-copy animate-on-scroll">
                <h2>Notre <span class="gradient-text">Mission</span></h2>
                <p>
                    <?php echo APP_NAME; ?> est une entreprise d'ingénierie spécialisée dans deux domaines
                    complémentaires : l'<strong>ingénierie logicielle</strong> (conception et déploiement de
                    logiciels en local et sur VPS/cloud) et l'<strong>ingénierie électronique</strong>
                    (systèmes embarqués, machines, appareils intelligents en IoT).
                </p>
                <p>
                    Notre ambition est claire : <strong>devenir le premier fabricant de tous types d'objets
                    électroniques en Afrique</strong> et ainsi réduire notre dépendance aux importations,
                    notamment envers la Chine.
                </p>
                <ul class="mission-points">
                    <li>
                        <span class="check"><i class="fas fa-check"></i></span>
                        <span><strong>Souveraineté technologique</strong> — concevoir et produire sur le continent.</span>
                    </li>
                    <li>
                        <span class="check"><i class="fas fa-check"></i></span>
                        <span><strong>Emplois qualifiés</strong> — développer l'ingénierie africaine.</span>
                    </li>
                    <li>
                        <span class="check"><i class="fas fa-check"></i></span>
                        <span><strong>Innovation intégrée</strong> — l'appareil, son logiciel de pilotage et ses accessoires.</span>
                    </li>
                </ul>
            </div>
            <div class="mission-visual animate-on-scroll" style="animation-delay:0.15s">
                <div class="mission-card">
                    <h4><i class="fas fa-bullseye"></i> Nos valeurs</h4>
                    <div class="progress-track">
                        <div class="progress-item">
                            <div class="progress-label"><span>Innovation</span><span>100%</span></div>
                            <div class="progress-bar"><div class="progress-fill" style="width:100%"></div></div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-label"><span>Qualité</span><span>100%</span></div>
                            <div class="progress-bar"><div class="progress-fill" style="width:100%"></div></div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-label"><span>Fabrication locale</span><span>100%</span></div>
                            <div class="progress-bar"><div class="progress-fill" style="width:100%"></div></div>
                        </div>
                    </div>
                    <div class="hero-actions" style="justify-content:flex-start; margin-top:26px;">
                        <a href="<?php echo url('catalog'); ?>" class="btn btn-dark">Nos produits</a>
                        <a href="<?php echo url('projects'); ?>" class="btn btn-outline">Nos réalisations</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="stats-band">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2>Pourquoi <span class="gradient-text">nous choisir</span></h2>
            <p>Une approche intégrée, de la première idée au produit fini.</p>
        </div>
        <div class="stats-grid">
            <div class="stat-item animate-on-scroll">
                <div class="stat-number">100%</div>
                <div class="stat-label">Ingénierie intégrée</div>
            </div>
            <div class="stat-item animate-on-scroll" style="animation-delay:0.1s">
                <div class="stat-number">2</div>
                <div class="stat-label">Pôles d'expertise</div>
            </div>
            <div class="stat-item animate-on-scroll" style="animation-delay:0.2s">
                <div class="stat-number">4</div>
                <div class="stat-label">Familles de produits</div>
            </div>
            <div class="stat-item animate-on-scroll" style="animation-delay:0.3s">
                <div class="stat-number">3</div>
                <div class="stat-label">Formules de tarification</div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
