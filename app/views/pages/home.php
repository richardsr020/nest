<?php
// nest/app/views/pages/home.php
$page_title = "Accueil - " . APP_NAME;
include __DIR__ . '/../partials/header.php';
?>

<!-- ===== HERO ===== -->
<section class="hero" id="accueil">
    <div class="particles" id="particles"></div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge animate-slide-up">
                <span class="dot"></span>
                <span>Ingénierie logicielle &amp; électronique — depuis l'Afrique</span>
            </div>
            <h1 class="animate-slide-up" style="animation-delay: 0.1s">
                Nous concevons, nous fabriquons,<br>
                <span class="gradient-text animate-gradient">nous innovons.</span>
            </h1>
            <p class="hero-sub animate-slide-up" style="animation-delay: 0.25s">
                <?php echo APP_NAME; ?> conçoit des logiciels (déployés localement ou sur VPS/cloud) et
                des appareils électroniques intelligents (systèmes embarqués, IoT). Notre ambition :
                devenir le premier fabricant d'objets électroniques en Afrique et réduire notre dépendance à l'importation.
            </p>
            <div class="hero-actions animate-slide-up" style="animation-delay: 0.4s">
                <a href="<?php echo url('catalog'); ?>" class="btn btn-dark">
                    Découvrir notre catalogue <i class="fas fa-arrow-right"></i>
                </a>
                <a href="<?php echo url('services'); ?>" class="btn btn-outline">
                    Nos services <i class="fas fa-microchip"></i>
                </a>
            </div>

            <div class="hero-features">
                <div class="hero-feature">
                    <div class="stat-number counter" data-target="30">0</div>
                    <div class="stat-label">Produits &amp; solutions</div>
                </div>
                <div class="hero-feature">
                    <div class="stat-number counter" data-target="4">0</div>
                    <div class="stat-label">Familles de produits</div>
                </div>
                <div class="hero-feature">
                    <div class="stat-number counter" data-target="100">0</div>
                    <div class="stat-label">% fabriqué en Afrique</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== SERVICES ===== -->
<section class="section" id="services">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2>Nos <span class="gradient-text">Domaines d'Expertise</span></h2>
            <p>Deux pôles complémentaires qui travaillent en synergie, du logiciel à l'objet connecté.</p>
        </div>
        <div class="services-grid">
            <div class="service-card animate-on-scroll">
                <div class="service-icon"><i class="fas fa-code"></i></div>
                <h3>Ingénierie Logicielle</h3>
                <p>Conception, développement et déploiement de logiciels sur mesure : applications locales, portables, web et SaaS sur VPS ou cloud.</p>
                <ul>
                    <li>Architecture &amp; conception logicielle</li>
                    <li>Déploiement local et sur VPS / cloud</li>
                    <li>Logiciels PC, SaaS, applications Android</li>
                    <li>Maintenance &amp; évolution</li>
                </ul>
                <a href="<?php echo url('services'); ?>" class="card-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="service-card animate-on-scroll" style="animation-delay: 0.1s">
                <div class="service-icon"><i class="fas fa-microchip"></i></div>
                <h3>Électronique &amp; Embarqué</h3>
                <p>Conception et réalisation de systèmes embarqués, machines et appareils intelligents connectés (IoT).</p>
                <ul>
                    <li>Conception de cartes électroniques</li>
                    <li>Systèmes embarqués &amp; firmware</li>
                    <li>Objets connectés (IoT)</li>
                    <li>Logiciels de pilotage des appareils</li>
                </ul>
                <a href="<?php echo url('services'); ?>" class="card-link">En savoir plus <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="service-card animate-on-scroll" style="animation-delay: 0.2s">
                <div class="service-icon"><i class="fas fa-industry"></i></div>
                <h3>Fabrication Africaine</h3>
                <p>Ambition : devenir le premier fabricant d'objets électroniques en Afrique et réduire notre dépendance à la Chine.</p>
                <ul>
                    <li>Assemblage et production locale</li>
                    <li>Appareils, machines &amp; accessoires</li>
                    <li>Création d'emplois locaux</li>
                    <li>Souveraineté technologique</li>
                </ul>
                <a href="<?php echo url('about'); ?>" class="card-link">Notre mission <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<!-- ===== PRODUITS VEDETTES ===== -->
<?php if (!empty($featuredProducts)): ?>
<section class="section" style="background: #f8f9fb;" id="produits">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2>Produits <span class="gradient-text">À la une</span></h2>
            <p>Logiciels, SaaS, applications Android et appareils électroniques fabriqués par <?php echo APP_NAME; ?>.</p>
        </div>
        <div class="products-grid">
            <?php foreach ($featuredProducts as $product): ?>
            <a href="<?php echo url('product', ['id' => $product['id']]); ?>" class="product-card animate-on-scroll">
                <div class="product-card-top">
                    <div class="product-icon" style="background: <?php echo e($product['category_color']); ?>;">
                        <i class="fas <?php echo e(categoryIcon($product['category_slug'])); ?>"></i>
                    </div>
                    <span class="pricing-badge <?php echo pricingBadgeClass($product['pricing_type']); ?>">
                        <?php echo e($product['pricing_type'] === PRICING_FREE ? 'Gratuit' : ($product['pricing_type'] === PRICING_SUBSCRIPTION ? 'Abonnement' : 'Unique')); ?>
                    </span>
                </div>
                <div class="product-body">
                    <span class="product-category"><?php echo e($product['category_name']); ?></span>
                    <h3><?php echo e($product['name']); ?></h3>
                    <p class="product-desc"><?php echo e($product['short_description'] ?: mb_substr($product['description'], 0, 90) . '…'); ?></p>
                    <div class="product-price">
                        <?php echo e(formatPriceLabel($product)); ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center; margin-top: 44px;">
            <a href="<?php echo url('catalog'); ?>" class="btn btn-dark">Voir tout le catalogue <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===== STATS ===== -->
<section class="stats-band" id="chiffres">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2>Nos <span class="gradient-text">Chiffres Clés</span></h2>
            <p>Une vision industrielle ambitieuse pour l'Afrique.</p>
        </div>
        <div class="stats-grid">
            <div class="stat-item animate-on-scroll">
                <div class="stat-number counter" data-target="100">0</div>
                <div class="stat-label">% logiciel développé en interne</div>
            </div>
            <div class="stat-item animate-on-scroll" style="animation-delay: 0.1s">
                <div class="stat-number counter" data-target="4">0</div>
                <div class="stat-label">familles de produits</div>
            </div>
            <div class="stat-item animate-on-scroll" style="animation-delay: 0.2s">
                <div class="stat-number counter" data-target="1">0</div>
                <div class="stat-label">objectif : 1er fabricant africain</div>
            </div>
            <div class="stat-item animate-on-scroll" style="animation-delay: 0.3s">
                <div class="stat-number counter" data-target="0">0</div>
                <div class="stat-label">dépendance à l'importation visée</div>
            </div>
        </div>
    </div>
</section>

<!-- ===== MISSION ===== -->
<section class="section" id="mission">
    <div class="container">
        <div class="mission-grid">
            <div class="mission-copy animate-on-scroll">
                <h2>Une mission : <span class="gradient-text">fabriquer en Afrique</span></h2>
                <p>
                    <?php echo APP_NAME; ?> naît d'une conviction forte : l'Afrique doit concevoir et fabriquer
                    ses propres outils technologiques. Chaque appareil électronique que nous réalisons —
                    avec son logiciel de pilotage et ses accessoires — est pensé, conçu et assemblé localement.
                </p>
                <p>
                    En réduisant notre dépendance à la Chine pour les équipements électroniques, nous
                    contribuons à la souveraineté technologique du continent et à la création d'emplois qualifiés.
                </p>
                <ul class="mission-points">
                    <li>
                        <span class="check"><i class="fas fa-check"></i></span>
                        <span><strong>Innovation locale</strong> — R&amp;D menée sur le continent, pour le continent.</span>
                    </li>
                    <li>
                        <span class="check"><i class="fas fa-check"></i></span>
                        <span><strong>Produits complets</strong> — l'appareil, son logiciel de pilotage et ses accessoires.</span>
                    </li>
                    <li>
                        <span class="check"><i class="fas fa-check"></i></span>
                        <span><strong>Tarification claire</strong> — gratuit, paiement unique ou abonnement.</span>
                    </li>
                </ul>
            </div>
            <div class="mission-visual animate-on-scroll" style="animation-delay: 0.15s">
                <div class="mission-card">
                    <h4><i class="fas fa-rocket"></i> Feuille de route</h4>
                    <div class="progress-track">
                        <div class="progress-item">
                            <div class="progress-label"><span>Logiciels &amp; SaaS</span><span>95%</span></div>
                            <div class="progress-bar"><div class="progress-fill" style="width:95%"></div></div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-label"><span>Électronique &amp; IoT</span><span>60%</span></div>
                            <div class="progress-bar"><div class="progress-fill" style="width:60%"></div></div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-label"><span>Production industrielle locale</span><span>30%</span></div>
                            <div class="progress-bar"><div class="progress-fill" style="width:30%"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== PROJETS ===== -->
<?php if (!empty($featuredProjects)): ?>
<section class="section" style="background: #f8f9fb;" id="realisations">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2>Réalisations <span class="gradient-text">Récentes</span></h2>
            <p>Quelques innovations conçues et réalisées par nos équipes.</p>
        </div>
        <div class="projects-grid">
            <?php foreach ($featuredProjects as $project): ?>
            <div class="project-card animate-on-scroll">
                <div class="project-image">
                    <?php if (!empty($project['image_path'])): ?>
                        <img src="<?php echo UPLOADS_URL . e($project['image_path']); ?>" alt="<?php echo e($project['title']); ?>">
                    <?php else: ?>
                        <i class="fas fa-<?php echo $project['category'] === 'software' ? 'code' : ($project['category'] === 'electronics' ? 'microchip' : ($project['category'] === 'iot' ? 'wifi' : 'industry')); ?>"></i>
                    <?php endif; ?>
                </div>
                <div class="project-body">
                    <span class="project-category"><?php echo e(ucfirst($project['category'])); ?></span>
                    <h3><?php echo e($project['title']); ?></h3>
                    <p><?php echo e(mb_substr($project['description'], 0, 110)); ?><?php echo mb_strlen($project['description']) > 110 ? '…' : ''; ?></p>
                    <?php if (!empty($project['tags'])): ?>
                    <div class="project-tags">
                        <?php foreach (array_slice(array_filter(array_map('trim', explode(',', $project['tags']))), 0, 3) as $tag): ?>
                            <span><?php echo e($tag); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center; margin-top: 44px;">
            <a href="<?php echo url('projects'); ?>" class="btn btn-outline">Toutes nos réalisations <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===== CTA ===== -->
<section class="cta-band" id="cta">
    <div class="container">
        <h2 class="animate-on-scroll">Prêt à innover avec nous ?</h2>
        <p class="animate-on-scroll">Parcourez notre catalogue de logiciels et d'appareils électroniques, ou parlez-nous de votre projet.</p>
        <div class="hero-actions animate-on-scroll">
            <a href="<?php echo url('catalog'); ?>" class="btn btn-light">Explorer le catalogue</a>
            <a href="<?php echo url('contact'); ?>" class="btn btn-ghost-light"><i class="fas fa-paper-plane"></i> Nous contacter</a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
