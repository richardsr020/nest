<?php
// nest/app/views/pages/projects.php
$projectCategories = [
    '' => ['label' => 'Tous', 'icon' => 'fa-th-large'],
    'software' => ['label' => 'Logiciels', 'icon' => 'fa-code'],
    'electronics' => ['label' => 'Électronique', 'icon' => 'fa-microchip'],
    'iot' => ['label' => 'Objets connectés', 'icon' => 'fa-wifi'],
    'manufacturing' => ['label' => 'Fabrication', 'icon' => 'fa-industry'],
];
include __DIR__ . '/../partials/header.php';
?>
<section class="page-hero">
    <div class="container">
        <h1>Nos <span class="gradient-text">Réalisations</span></h1>
        <p>Des projets conçus et réalisés par nos équipes : logiciels, électronique, objets connectés et fabrication.</p>
    </div>
</section>

<section class="section" style="padding-top:40px;">
    <div class="container">
        <div class="filter-row" style="justify-content:center; margin-bottom:44px;">
            <?php foreach ($projectCategories as $key => $cat): ?>
                <a href="<?php echo url('projects', $key ? ['category' => $key] : []); ?>"
                   class="filter-btn <?php echo ($activeCategory === $key) ? 'active' : ''; ?>">
                    <i class="fas <?php echo $cat['icon']; ?>"></i> <?php echo $cat['label']; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($projects)): ?>
            <div style="text-align:center; padding:60px 0;">
                <i class="fas fa-folder-open" style="font-size:3rem; color:#ccc;"></i>
                <h3 style="margin-top:20px;">Aucune réalisation pour l'instant</h3>
                <p style="color:#888;">Revenez bientôt pour découvrir nos nouveaux projets.</p>
            </div>
        <?php else: ?>
            <div class="projects-grid">
                <?php foreach ($projects as $project): ?>
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
                        <p><?php echo e(mb_substr($project['description'], 0, 120)); ?><?php echo mb_strlen($project['description']) > 120 ? '…' : ''; ?></p>
                        <?php if (!empty($project['tags'])): ?>
                        <div class="project-tags">
                            <?php foreach (array_filter(array_map('trim', explode(',', $project['tags']))) as $tag): ?>
                                <span><?php echo e($tag); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($project['link'])): ?>
                        <div class="product-actions" style="margin-top:16px;">
                            <a href="<?php echo e($project['link']); ?>" target="_blank" rel="noopener" class="btn btn-dark" style="padding:9px 16px; font-size:0.85rem;">
                                <i class="fas fa-external-link-alt"></i> Voir le projet
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../partials/footer.php'; ?>
