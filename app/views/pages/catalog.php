<?php
// nest/app/views/pages/catalog.php
$types = [
    '' => ['label' => 'Tous', 'icon' => 'fa-th-large'],
    'desktop' => ['label' => 'Logiciels PC', 'icon' => 'fa-desktop'],
    'saas' => ['label' => 'Logiciels en ligne', 'icon' => 'fa-cloud'],
    'android' => ['label' => 'Applications Android', 'icon' => 'fa-android'],
    'hardware' => ['label' => 'Appareils électroniques', 'icon' => 'fa-microchip'],
];
$pricings = [
    '' => ['label' => 'Tous les prix', 'icon' => 'fa-dollar-sign'],
    'free' => ['label' => 'Gratuit', 'icon' => 'fa-gift'],
    'one_time' => ['label' => 'Paiement unique', 'icon' => 'fa-credit-card'],
    'subscription' => ['label' => 'Abonnement', 'icon' => 'fa-sync-alt'],
];
include __DIR__ . '/../partials/header.php';
?>
<section class="page-hero">
    <div class="container">
        <h1>Notre <span class="gradient-text">Catalogue</span></h1>
        <p>Logiciels PC, logiciels en ligne (SaaS), applications Android et appareils électroniques — gratuits, à paiement unique ou par abonnement.</p>
    </div>
</section>

<section class="section" style="padding-top:40px;">
    <div class="container">
        <!-- Filtres -->
        <div class="catalog-filters animate-on-scroll">
            <div class="filter-row">
                <?php foreach ($types as $key => $type): ?>
                    <a href="<?php echo url('catalog', array_merge(['type' => $key], $activePricing ? ['pricing' => $activePricing] : [], $searchQuery ? ['q' => $searchQuery] : [])); ?>"
                       class="filter-btn <?php echo ($activeType === $key) ? 'active' : ''; ?>">
                        <i class="fas <?php echo $type['icon']; ?>"></i> <?php echo $type['label']; ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="filter-divider"></div>
            <div class="filter-row">
                <?php foreach ($pricings as $key => $pricing): ?>
                    <a href="<?php echo url('catalog', array_merge($activeType ? ['type' => $activeType] : [], ['pricing' => $key], $searchQuery ? ['q' => $searchQuery] : [])); ?>"
                       class="filter-btn <?php echo ($activePricing === $key) ? 'active' : ''; ?>">
                        <i class="fas <?php echo $pricing['icon']; ?>"></i> <?php echo $pricing['label']; ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <form method="get" action="<?php echo url('catalog'); ?>" style="margin-top:20px; display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
                <input type="hidden" name="page" value="catalog">
                <?php if ($activeType): ?><input type="hidden" name="type" value="<?php echo e($activeType); ?>"><?php endif; ?>
                <?php if ($activePricing): ?><input type="hidden" name="pricing" value="<?php echo e($activePricing); ?>"><?php endif; ?>
                <input type="text" name="q" value="<?php echo e($searchQuery); ?>" class="form-control" placeholder="Rechercher un produit..." style="max-width:360px;">
                <button type="submit" class="btn btn-dark"><i class="fas fa-search"></i> Rechercher</button>
            </form>
        </div>

        <!-- Grille -->
        <?php if (empty($products)): ?>
            <div style="text-align:center; padding:60px 0;">
                <i class="fas fa-box-open" style="font-size:3rem; color:#ccc;"></i>
                <h3 style="margin-top:20px;">Aucun produit trouvé</h3>
                <p style="color:#888;">Essayez d'autres filtres ou revenez plus tard.</p>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                <div class="product-card animate-on-scroll">
                    <a href="<?php echo url('product', ['id' => $product['id']]); ?>" style="display:flex; flex-direction:column; flex:1;">
                        <div class="product-card-top">
                            <div class="product-icon" style="background: <?php echo e($product['category_color']); ?>;">
                                <i class="fas <?php echo e(categoryIcon($product['category_slug'])); ?>"></i>
                            </div>
                            <span class="pricing-badge <?php echo pricingBadgeClass($product['pricing_type']); ?>">
                                <?php echo $product['pricing_type'] === PRICING_FREE ? 'Gratuit' : ($product['pricing_type'] === PRICING_SUBSCRIPTION ? 'Abonnement' : 'Unique'); ?>
                            </span>
                        </div>
                        <div class="product-body">
                            <span class="product-category"><?php echo e($product['category_name']); ?></span>
                            <h3><?php echo e($product['name']); ?></h3>
                            <p class="product-desc"><?php echo e($product['short_description'] ?: mb_substr($product['description'], 0, 90) . '…'); ?></p>
                            <div class="product-meta">
                                <span><i class="fas fa-tag"></i> v<?php echo e($product['version']); ?></span>
                                <span><i class="fas fa-eye"></i> <?php echo (int)$product['view_count']; ?></span>
                            </div>
                            <div class="product-price">
                                <?php echo e(formatPriceLabel($product)); ?>
                            </div>
                            <div class="product-actions" style="margin-top:14px;">
                                <button class="btn btn-dark" style="padding:10px 18px; font-size:0.88rem; width:100%; justify-content:center;"
                                        data-order-btn
                                        data-product-id="<?php echo $product['id']; ?>"
                                        data-product-name="<?php echo e($product['name']); ?>"
                                        data-pricing="<?php echo e($product['pricing_type']); ?>">
                                    <?php if ($product['pricing_type'] === PRICING_FREE): ?>
                                        <i class="fas fa-download"></i> Télécharger
                                    <?php else: ?>
                                        <i class="fas fa-shopping-cart"></i> Commander
                                    <?php endif; ?>
                                </button>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script src="<?php echo js('catalog.js'); ?>"></script>
<?php include __DIR__ . '/../partials/footer.php'; ?>
