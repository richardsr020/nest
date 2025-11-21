<?php 
$page_title = "Accueil - " . APP_NAME;
include __DIR__ . '/partials/header.php'; 
?>
<header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <!-- ***** Logo Start ***** -->
            <a href="<?php echo url(); ?>" class="logo">
              <span style="font-size: 1.8rem; font-weight: 700; background: linear-gradient(135deg, #0072ff, #00c6ff); -webkit-background-clip: text; background-clip: text; color: transparent;"><?php echo APP_NAME; ?></span>
            </a>
            <!-- ***** Logo End ***** -->
            <!-- ***** Menu Start ***** -->
            <ul class="nav">
              <li class="scroll-to-section"><a href="#top" class="active">Accueil</a></li>
              <li class="scroll-to-section"><a href="#services">Solutions</a></li>
              <li class="scroll-to-section"><a href="#about">À Propos</a></li>
              <li class="scroll-to-section"><a href="#clients">Témoignages</a></li>
              <li><div class="gradient-button"><a href="<?php echo url('auth'); ?>"><i class="fa fa-sign-in-alt"></i> Connexion</a></div></li> 
            </ul>        

            <a class='menu-trigger'>
                <span></span>
                <span></span>
                <span></span>
            </a>
            <!-- ***** Menu End ***** -->
          </nav>
        </div>
      </div>
    </div>
  </header>
  <!-- ***** Header Area End ***** -->
<!-- ***** Preloader Start ***** -->
<div id="js-preloader" class="js-preloader">
  <div class="preloader-inner">
    <span class="dot"></span>
    <div class="dots">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
</div>
<!-- ***** Preloader End ***** -->

<div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="row">
          <div class="col-lg-6 align-self-center">
            <div class="left-content show-up header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
              <div class="row">
                <div class="col-lg-12">
                  <h2 class="hero-title">Découvrez Notre Écosystème Numérique Complet</h2>
                  <p class="hero-description"><?php echo APP_NAME; ?> rassemble quatre plateformes innovantes conçues pour transformer votre expérience digitale. De la recherche d'emploi aux transactions sécurisées, nous couvrons tous vos besoins.</p>
                </div>
                <div class="col-lg-12">
                  <div class="white-button first-button scroll-to-section">
                    <a href="<?php echo url('auth'); ?>">Commencer l'aventure <i class="fas fa-rocket"></i></a>
                  </div>
                  
                  <div class="white-button scroll-to-section">
                    <a href="<?php echo url('entities'); ?>">Explorer nos solutions <i class="fas fa-eye"></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
              <!-- Bulles animées à la place de l'image -->
              <div class="animated-bubbles">
                <div class="bubble bubble-1"></div>
                <div class="bubble bubble-2"></div>
                <div class="bubble bubble-3"></div>
                <div class="bubble bubble-4"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="services" class="services section">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 offset-lg-2">
        <div class="section-heading wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.5s">
          <h4>Nos <em>Solutions Innovantes</em> pour Vous</h4>
          <img src="<?php echo image('heading-line-dec.png'); ?>" alt="">
          <p>Découvrez nos quatre plateformes complémentaires conçues pour travailler en parfaite synergie et révolutionner votre quotidien numérique.</p>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-3">
        <div class="service-item first-service wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.5s">
          <div class="">
            <i class="fas fa-briefcase"></i>
          </div>
          <h4><?php echo PLATFORM_SKILL; ?></h4>
          <p>Plateforme intelligente de matching entre talents et opportunités professionnelles avec algorithmes avancés.</p>
          <div class="text-button">
            <a href="<?php echo url('entities'); ?>">Découvrir <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="service-item second-service wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.7s">
          <div class="">
            <i class="fas fa-shopping-cart"></i>
          </div>
          <h4><?php echo PLATFORM_SHOPPING; ?></h4>
          <p>Marketplace nouvelle génération offrant une expérience d'achat fluide, personnalisée et sécurisée.</p>
          <div class="text-button">
            <a href="<?php echo url('entities'); ?>">Découvrir <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="service-item third-service wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.9s">
          <div class="">
            <i class="fas fa-credit-card"></i>
          </div>
          <h4><?php echo PLATFORM_PAYMENT; ?></h4>
          <p>Solutions de paiement sécurisées avec cryptage avancé et analyse financière intelligente.</p>
          <div class="text-button">
            <a href="<?php echo url('entities'); ?>">Découvrir <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="service-item fourth-service wow fadeInUp" data-wow-duration="1s" data-wow-delay="1.1s">
          <div class="">
            <i class="fas fa-envelope"></i>
          </div>
          <h4><?php echo PLATFORM_MAILER; ?></h4>
          <p>Service de messagerie professionnel avec outils collaboratifs modernes et sécurité renforcée.</p>
          <div class="text-button">
            <a href="<?php echo url('entities'); ?>">Découvrir <i class="fa fa-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="about" class="about-us section">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 align-self-center">
        <div class="section-heading wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.5s">
          <h4>À Propos de <em>Notre Mission</em> &amp; Notre Vision</h4>
          <img src="<?php echo image('heading-line-dec.png'); ?>" alt="">
          <p>Chez <?php echo APP_NAME; ?>, nous croyons en la puissance de la technologie pour simplifier la vie et créer des expériences digitales exceptionnelles.</p>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <div class="box-item wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.7s">
              <h4><a href="#">Innovation Continue</a></h4>
              <p>Recherche et développement permanents</p>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="box-item wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.9s">
              <h4><a href="#">Support 24/7</a></h4>
              <p>Assistance technique toujours disponible</p>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="box-item wow fadeInUp" data-wow-duration="1s" data-wow-delay="1.1s">
              <h4><a href="#">Sécurité Maximale</a></h4>
              <p>Protection avancée de vos données</p>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="box-item wow fadeInUp" data-wow-duration="1s" data-wow-delay="1.3s">
              <h4><a href="#">Évolutivité</a></h4>
              <p>Solutions adaptées à votre croissance</p>
            </div>
          </div>
          <div class="col-lg-12">
            <p class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="1.5s">Notre approche centrée sur l'utilisateur nous permet de créer des solutions qui s'adaptent à vos besoins, pas l'inverse. Chaque plateforme est conçue pour travailler en parfaite synergie avec les autres.</p>
            <div class="gradient-button wow fadeInUp" data-wow-duration="1s" data-wow-delay="1.7s">
              <a href="<?php echo url('auth'); ?>">Créer Mon Compte</a>
            </div>
            <span class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="1.9s">*Essai gratuit de 14 jours</span>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
          <img src="<?php echo image('about-right-dec.png'); ?>" alt="À propos de Nest">
        </div>
      </div>
    </div>
  </div>
</div>

<div id="clients" class="the-clients">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 offset-lg-2">
        <div class="section-heading wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.5s">
          <h4>Ce Que Disent <em>Nos Clients</em> De Notre Écosystème</h4>
          <img src="<?php echo image('heading-line-dec.png'); ?>" alt="">
          <p>Découvrez les retours d'expérience de ceux qui ont déjà adopté nos solutions innovantes.</p>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="naccs">
          <div class="grid">
            <div class="row">
              <div class="col-lg-7 align-self-center">
                <div class="menu">
                  <div class="first-thumb active">
                    <div class="thumb">
                      <div class="row">
                        <div class="col-lg-4 col-sm-4 col-12">
                          <h4>Sarah Tech Solutions</h4>
                          <span class="date">15 Janvier 2024</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                          <span class="category">Services Professionnels</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-12">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <span class="rating">4.9</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div>
                    <div class="thumb">
                      <div class="row">
                        <div class="col-lg-4 col-sm-4 col-12">
                          <h4>Martin Retail Group</h4>
                          <span class="date">12 Janvier 2024</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                          <span class="category">Commerce Digital</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-12">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <span class="rating">4.8</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div>
                    <div class="thumb">
                      <div class="row">
                        <div class="col-lg-4 col-sm-4 col-12">
                          <h4>Innovation Startup</h4>
                          <span class="date">8 Janvier 2024</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 d-none d-sm-block">
                          <span class="category">Startup Technologique</span>
                        </div>
                        <div class="col-lg-4 col-sm-4 col-12">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-half-alt"></i>
                            <span class="rating">4.7</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> 
              <div class="col-lg-5">
                <ul class="nacc">
                  <li class="active">
                    <div>
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="client-content">
                              <img src="<?php echo image('quote.png'); ?>" alt="">
                              <p>"L'écosystème Nest a transformé notre façon de travailler. Skill nous a permis de recruter les meilleurs talents, tandis que Pay & Wise a sécurisé toutes nos transactions. Une intégration parfaite !"</p>
                            </div>
                            <div class="down-content">
                              <img src="<?php echo image('client-image.jpg'); ?>" alt="">
                              <div class="right-content">
                                <h4>Sarah Chen</h4>
                                <span>CEO de Sarah Tech Solutions</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div>
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="client-content">
                              <img src="<?php echo image('quote.png'); ?>" alt="">
                              <p>"i-Shopping a boosté nos ventes en ligne de 40% en seulement 3 mois. L'expérience utilisateur est exceptionnelle et l'intégration avec Pay & Wise rend le processus d'achat incroyablement fluide."</p>
                            </div>
                            <div class="down-content">
                              <img src="<?php echo image('client-image.jpg'); ?>" alt="">
                              <div class="right-content">
                                <h4>Martin Dubois</h4>
                                <span>Directeur de Martin Retail</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div>
                      <div class="thumb">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="client-content">
                              <img src="<?php echo image('quote.png'); ?>" alt="">
                              <p>"En tant que startup, nous avions besoin de solutions évolutives et abordables. Nest a parfaitement répondu à nos attentes. Mailer a révolutionné notre communication d'équipe."</p>
                            </div>
                            <div class="down-content">
                              <img src="<?php echo image('client-image.jpg'); ?>" alt="">
                              <div class="right-content">
                                <h4>Alex Rivera</h4>
                                <span>Fondateur de Innovation Startup</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>          
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<footer id="newsletter">
  <div class="container">
    <div class="row">
      <div class="col-lg-8 offset-lg-2">
        <div class="section-heading wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.5s">
          <h4>Rejoignez notre liste de diffusion pour recevoir les dernières actualités</h4>
        </div>
      </div>
      <div class="col-lg-6 offset-lg-3">
        <form id="search" action="#" method="GET">
          <div class="row">
            <div class="col-lg-8 col-sm-8">
              <fieldset>
                <input type="email" name="email" class="email" placeholder="Votre adresse email..." autocomplete="on" required>
              </fieldset>
            </div>
            <div class="col-lg-4 col-sm-4">
              <fieldset>
                <button type="submit" class="main-button">S'abonner <i class="fa fa-angle-right"></i></button>
              </fieldset>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-3">
        <div class="footer-widget wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.5s">
          <h4>Nous Contacter</h4>
          <p>Paris, 75001, France</p>
          <p><a href="#">+33 1 23 45 67 89</a></p>
          <p><a href="#">contact@nest-software.com</a></p>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="footer-widget wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.7s">
          <h4>Navigation</h4>
          <ul>
            <li><a href="#top">Accueil</a></li>
            <li><a href="#services">Solutions</a></li>
            <li><a href="#about">À Propos</a></li>
            <li><a href="#clients">Témoignages</a></li>
            <li><a href="<?php echo url('entities'); ?>">Entités</a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="footer-widget wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.9s">
          <h4>Nos Solutions</h4>
          <ul>
            <li><a href="<?php echo url('entities'); ?>"><?php echo PLATFORM_SKILL; ?></a></li>
            <li><a href="<?php echo url('entities'); ?>"><?php echo PLATFORM_SHOPPING; ?></a></li>
            <li><a href="<?php echo url('entities'); ?>"><?php echo PLATFORM_PAYMENT; ?></a></li>
            <li><a href="<?php echo url('entities'); ?>"><?php echo PLATFORM_MAILER; ?></a></li>
            <li><a href="<?php echo url('auth'); ?>">Espace Client</a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="footer-widget wow fadeInUp" data-wow-duration="1s" data-wow-delay="1.1s">
          <h4>Notre Société</h4>
          <div class="logo">
            <span style="font-size: 1.5rem; font-weight: 700; background: linear-gradient(135deg, #0072ff, #00c6ff); -webkit-background-clip: text; background-clip: text; color: transparent;"><?php echo APP_NAME; ?></span>
          </div>
          <p>Pionnier des écosystèmes numériques intégrés, nous créons des solutions innovantes qui transforment l'expérience digitale.</p>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="copyright-text wow fadeInUp" data-wow-duration="1s" data-wow-delay="1.3s">
          <p>Copyright © 2024 <?php echo APP_NAME; ?>. Tous droits réservés.</p>
        </div>
      </div>
    </div>
  </div>
</footer>

<?php include __DIR__ . '/partials/footer.php'; ?>