<?php
// nest/app/views/pages/product.php
$pricingLabel = $product['pricing_type'] === PRICING_FREE ? 'Gratuit' : ($product['pricing_type'] === PRICING_SUBSCRIPTION ? 'Abonnement' : 'Paiement unique');
$controlSoftware = [];
$accessories = [];
if (!empty($linked)) {
    foreach ($linked as $item) {
        if ($item['link_type'] === 'control_software') {
            $controlSoftware[] = $item;
        } elseif ($item['link_type'] === 'accessory') {
            $accessories[] = $item;
        }
    }
}
include __DIR__ . '/../partials/header.php';
?>

<section class="page-hero" style="padding-bottom:60px;">
    <div class="container">
        <nav class="breadcrumb-nav">
            <a href="<?php echo url('catalog'); ?>">Catalogue</a>
            <span>/</span>
            <span><?php echo e($product['category_name'] ?? 'Produit'); ?></span>
        </nav>
        <h1><?php echo e($product['name']); ?></h1>
        <p style="max-width:720px;"><?php echo e($product['short_description'] ?: mb_substr($product['description'], 0, 140) . '…'); ?></p>
    </div>
</section>

<section class="section" style="padding-top:56px;">
    <div class="container">
        <div class="product-detail-grid">
            <!-- Visuel -->
            <div class="animate-on-scroll">
                <?php if (!empty($product['image_path'])): ?>
                    <img src="<?php echo UPLOADS_URL . e($product['image_path']); ?>"
                         alt="<?php echo e($product['name']); ?>" class="product-image">
                <?php else: ?>
                    <div class="product-visual" style="background: <?php echo e($product['category_color'] ?? '#0066ff'); ?>;">
                        <i class="fas <?php echo e(categoryIcon($product['category_slug'] ?? '')); ?>"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Infos -->
            <div class="animate-on-scroll" style="animation-delay:0.1s">
                <div class="product-category">
                    <i class="fas <?php echo e(categoryIcon($product['category_slug'] ?? '')); ?>"></i>
                    <?php echo e($product['category_name'] ?? ''); ?>
                </div>
                <h2><?php echo e($product['name']); ?></h2>
                <div class="product-meta">
                    <span><i class="fas fa-tag"></i> v<?php echo e($product['version']); ?></span>
                    <?php if (!empty($product['developer'])): ?>
                        <span><i class="fas fa-user-cog"></i> <?php echo e($product['developer']); ?></span>
                    <?php endif; ?>
                    <?php if (!empty($product['release_date'])): ?>
                        <span><i class="fas fa-calendar-alt"></i> <?php echo date('d/m/Y', strtotime($product['release_date'])); ?></span>
                    <?php endif; ?>
                    <span><i class="fas fa-eye"></i> <?php echo (int)$product['view_count']; ?> vues</span>
                </div>

                <p class="product-detail-desc"><?php echo nl2br(e($product['description'])); ?></p>

                <?php if (!empty($product['features'])): ?>
                    <h3 style="font-size:1.2rem; margin:24px 0 12px;"><i class="fas fa-check-circle" style="color:var(--primary-blue);"></i> Caractéristiques</h3>
                    <ul class="feature-list">
                        <?php foreach ($product['features'] as $feature): ?>
                            <li><i class="fas fa-check"></i> <?php echo e($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <!-- Prix -->
                <div class="price-box">
                    <div>
                        <span class="price-label"><?php echo $pricingLabel; ?></span>
                        <div class="price-main"><?php echo e(formatPriceLabel($product)); ?></div>
                        <?php if ($product['pricing_type'] === PRICING_SUBSCRIPTION): ?>
                            <div class="price-note">
                                <?php if ((int)$product['trial_days'] > 0): ?>
                                    <span class="badge-trial"><i class="fas fa-gift"></i> <?php echo (int)$product['trial_days']; ?> jours d'essai</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="product-actions">
                        <button class="btn btn-dark" style="padding:14px 26px; font-size:1rem;"
                                data-order-btn
                                data-product-id="<?php echo $product['id']; ?>"
                                data-product-name="<?php echo e($product['name']); ?>"
                                data-pricing="<?php echo e($product['pricing_type']); ?>">
                            <?php if ($product['pricing_type'] === PRICING_FREE): ?>
                                <i class="fas fa-download"></i> Télécharger gratuitement
                            <?php else: ?>
                                <i class="fas fa-shopping-cart"></i> Commander
                            <?php endif; ?>
                        </button>
                    </div>
                </div>

                <?php if (!empty($product['website_url']) || !empty($product['documentation_url']) || !empty($product['play_store_url']) || !empty($product['app_store_url'])): ?>
                    <div class="store-links">
                        <?php if (!empty($product['play_store_url'])): ?>
                            <a href="<?php echo e($product['play_store_url']); ?>" target="_blank" rel="noopener" class="store-link">
                                <i class="fab fa-google-play"></i> Google Play
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($product['app_store_url'])): ?>
                            <a href="<?php echo e($product['app_store_url']); ?>" target="_blank" rel="noopener" class="store-link">
                                <i class="fab fa-apple"></i> App Store
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($product['website_url'])): ?>
                            <a href="<?php echo e($product['website_url']); ?>" target="_blank" rel="noopener" class="store-link">
                                <i class="fas fa-globe"></i> Site web
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($product['documentation_url'])): ?>
                            <a href="<?php echo e($product['documentation_url']); ?>" target="_blank" rel="noopener" class="store-link">
                                <i class="fas fa-book"></i> Documentation
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($parent): ?>
        <!-- Composant d'un bundle -->
        <div class="bundle-note animate-on-scroll" style="margin-top:56px;">
            <div class="bundle-note-inner">
                <span class="bundle-icon"><i class="fas fa-layer-group"></i></span>
                <div>
                    <strong>Cet élément accompagne :</strong><br>
                    <a href="<?php echo url('product', ['id' => $parent['id']]); ?>">
                        <?php echo e($parent['name']); ?>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Bundle : logiciel de pilotage + accessoires -->
        <?php if ($controlSoftware || $accessories): ?>
        <div class="bundle-section" style="margin-top:56px;">
            <div class="section-header animate-on-scroll">
                <h2>Le <span class="gradient-text">Bundle Complet</span></h2>
                <p>Ce produit s'accompagne de son logiciel de pilotage et de ses accessoires, conçus par <?php echo APP_NAME; ?>.</p>
            </div>

            <?php if ($controlSoftware): ?>
            <h3 class="bundle-title"><i class="fas fa-laptop-code"></i> Logiciel de pilotage</h3>
            <div class="products-grid">
                <?php foreach ($controlSoftware as $item): ?>
                <div class="product-card animate-on-scroll" style="cursor:pointer;" onclick="location.href='<?php echo url('product', ['id' => $item['id']]); ?>';">
                    <div class="product-card-top">
                        <div class="product-icon" style="background: <?php echo e($item['category_color'] ?? '#0066ff'); ?>;">
                            <i class="fas <?php echo e(categoryIcon($item['category_slug'] ?? 'desktop')); ?>"></i>
                        </div>
                        <span class="pricing-badge <?php echo pricingBadgeClass($item['pricing_type']); ?>">
                            <?php echo $item['pricing_type'] === PRICING_FREE ? 'Gratuit' : ($item['pricing_type'] === PRICING_SUBSCRIPTION ? 'Abonnement' : 'Unique'); ?>
                        </span>
                    </div>
                    <div class="product-body">
                        <span class="product-category"><?php echo e($item['category_name'] ?? ''); ?></span>
                        <h3><?php echo e($item['name']); ?></h3>
                        <p class="product-desc"><?php echo e(mb_substr($item['description'], 0, 90)); ?>…</p>
                        <div class="product-price"><?php echo e(formatPriceLabel($item)); ?></div>
                        <div class="product-actions" style="margin-top:14px;">
                            <button class="btn btn-dark" style="padding:10px 18px; width:100%; justify-content:center;"
                                    onclick="event.stopPropagation();"
                                    data-order-btn
                                    data-product-id="<?php echo $item['id']; ?>"
                                    data-product-name="<?php echo e($item['name']); ?>"
                                    data-pricing="<?php echo e($item['pricing_type']); ?>">
                                <i class="fas fa-download"></i> <?php echo $item['pricing_type'] === PRICING_FREE ? 'Télécharger' : 'Commander'; ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if ($accessories): ?>
            <h3 class="bundle-title" style="margin-top:44px;"><i class="fas fa-plug"></i> Accessoires</h3>
            <div class="products-grid">
                <?php foreach ($accessories as $item): ?>
                <div class="product-card animate-on-scroll" style="cursor:pointer;" onclick="location.href='<?php echo url('product', ['id' => $item['id']]); ?>';">
                    <div class="product-card-top">
                        <div class="product-icon" style="background: <?php echo e($item['category_color'] ?? '#0066ff'); ?>;">
                            <i class="fas <?php echo e(categoryIcon($item['category_slug'] ?? 'hardware')); ?>"></i>
                        </div>
                        <span class="pricing-badge <?php echo pricingBadgeClass($item['pricing_type']); ?>">
                            <?php echo $item['pricing_type'] === PRICING_FREE ? 'Gratuit' : ($item['pricing_type'] === PRICING_SUBSCRIPTION ? 'Abonnement' : 'Unique'); ?>
                        </span>
                    </div>
                    <div class="product-body">
                        <span class="product-category"><?php echo e($item['category_name'] ?? ''); ?></span>
                        <h3><?php echo e($item['name']); ?></h3>
                        <p class="product-desc"><?php echo e(mb_substr($item['description'], 0, 90)); ?>…</p>
                        <div class="product-price"><?php echo e(formatPriceLabel($item)); ?></div>
                        <div class="product-actions" style="margin-top:14px;">
                            <button class="btn btn-dark" style="padding:10px 18px; width:100%; justify-content:center;"
                                    onclick="event.stopPropagation();"
                                    data-order-btn
                                    data-product-id="<?php echo $item['id']; ?>"
                                    data-product-name="<?php echo e($item['name']); ?>"
                                    data-pricing="<?php echo e($item['pricing_type']); ?>">
                                <i class="fas fa-shopping-cart"></i> Commander
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<script src="<?php echo js('catalog.js'); ?>"></script>
<?php include __DIR__ . '/../partials/footer.php'; ?>
