<?php
// nest/app/views/pages/services.php
include __DIR__ . '/../partials/header.php';
?>
<section class="page-hero">
    <div class="container">
        <h1>Nos <span class="gradient-text">Services</span></h1>
        <p>De la conception logicielle à la fabrication d'appareils électroniques intelligents, nous couvrons tout le cycle d'ingénierie.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2>Ingénierie <span class="gradient-text">Logicielle</span></h2>
            <p>Conception et déploiement de logiciels sur mesure, en local ou sur VPS/cloud.</p>
        </div>
        <div class="services-grid">
            <div class="service-card animate-on-scroll">
                <div class="service-icon"><i class="fas fa-pen-ruler"></i></div>
                <h3>Conception &amp; Architecture</h3>
                <p>Analyse des besoins, spécifications, architecture logicielle robuste et scalable.</p>
                <ul>
                    <li>Étude et cahier des charges</li>
                    <li>Architecture &amp; modélisation</li>
                    <li>Prototypage rapide</li>
                </ul>
            </div>
            <div class="service-card animate-on-scroll" style="animation-delay:0.1s">
                <div class="service-icon"><i class="fas fa-code"></i></div>
                <h3>Développement Logiciel</h3>
                <p>Développement d'applications PC, web et mobiles dans les technologies modernes.</p>
                <ul>
                    <li>Logiciels PC &amp; portables</li>
                    <li>Applications Android</li>
                    <li>API &amp; intégrations</li>
                </ul>
            </div>
            <div class="service-card animate-on-scroll" style="animation-delay:0.2s">
                <div class="service-icon"><i class="fas fa-cloud"></i></div>
                <h3>Déploiement VPS &amp; Cloud</h3>
                <p>Mise en production de vos logiciels en ligne (SaaS) sur VPS ou cloud, avec sécurité.</p>
                <ul>
                    <li>Déploiement VPS &amp; cloud</li>
                    <li>Configuration serveur</li>
                    <li>Sécurité &amp; sauvegardes</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background:#f8f9fb;">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <h2>Électronique &amp; <span class="gradient-text">Systèmes Embarqués</span></h2>
            <p>Conception et réalisation de machines et d'appareils intelligents en IoT.</p>
        </div>
        <div class="services-grid">
            <div class="service-card animate-on-scroll">
                <div class="service-icon"><i class="fas fa-microchip"></i></div>
                <h3>Conception Électronique</h3>
                <p>Schémas, PCB, choix de composants et fabrication de cartes électroniques.</p>
                <ul>
                    <li>Schémas &amp; PCB</li>
                    <li>Choix des composants</li>
                    <li>Prototypage &amp; tests</li>
                </ul>
            </div>
            <div class="service-card animate-on-scroll" style="animation-delay:0.1s">
                <div class="service-icon"><i class="fas fa-wifi"></i></div>
                <h3>Objets Connectés (IoT)</h3>
                <p>Appareils intelligents connectés avec supervision à distance et automatisation.</p>
                <ul>
                    <li>Capteurs &amp; actionneurs</li>
                    <li>Communication sans fil</li>
                    <li>Plateformes IoT</li>
                </ul>
            </div>
            <div class="service-card animate-on-scroll" style="animation-delay:0.2s">
                <div class="service-icon"><i class="fas fa-cogs"></i></div>
                <h3>Machines &amp; Appareils</h3>
                <p>Réalisation complète de machines : l'appareil, son logiciel de pilotage et ses accessoires.</p>
                <ul>
                    <li>Machines spéciales</li>
                    <li>Logiciels de pilotage</li>
                    <li>Accessoires &amp; extensions</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="mission-grid">
            <div class="mission-visual animate-on-scroll">
                <div class="mission-card">
                    <h4><i class="fas fa-industry"></i> Fabrication locale</h4>
                    <p style="color:#666;">Chaque produit est disponible en plusieurs formules :</p>
                    <ul class="mission-points">
                        <li>
                            <span class="check"><i class="fas fa-check"></i></span>
                            <span><strong>Gratuit</strong> — téléchargeable immédiatement</span>
                        </li>
                        <li>
                            <span class="check"><i class="fas fa-check"></i></span>
                            <span><strong>Paiement unique</strong> — licence définitive</span>
                        </li>
                        <li>
                            <span class="check"><i class="fas fa-check"></i></span>
                            <span><strong>Abonnement</strong> — mensuel ou annuel, avec période d'essai</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mission-copy animate-on-scroll" style="animation-delay:0.15s">
                <h2>Un produit complet, <span class="gradient-text">du matériel au logiciel</span></h2>
                <p>
                    Pour nos appareils électroniques, nous livrons bien plus que le matériel :
                    chaque machine ou objet connecté s'accompagne de son <strong>logiciel de pilotage</strong>
                    et de ses <strong>accessoires</strong> — le tout conçu en interne.
                </p>
                <p>
                    Consultez le catalogue pour découvrir nos logiciels PC, logiciels en ligne,
                    applications Android et appareils électroniques.
                </p>
                <div class="hero-actions" style="justify-content:flex-start; margin-top:28px;">
                    <a href="<?php echo url('catalog'); ?>" class="btn btn-dark">Voir le catalogue <i class="fas fa-arrow-right"></i></a>
                    <a href="<?php echo url('contact'); ?>" class="btn btn-outline">Demander un devis</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
