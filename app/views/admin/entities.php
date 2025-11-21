<div class="admin-entities">
    <!-- Header Admin -->
    <div class="admin-header">
        <div class="container">
            <div class="admin-nav">
                <a href="<?php echo url('admin'); ?>" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour au dashboard</span>
                </a>
                <h1>Gestion des Entités</h1>
                <button class="btn-primary" onclick="openEntityModal()">
                    <i class="fas fa-plus"></i>
                    <span>Nouvelle Entité</span>
                </button>
            </div>
        </div>
    </div>

    <nav class="admin-navbar">
        <div class="container">
            <div class="nav-links">
                <a href="<?php echo url('admin'); ?>" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?php echo url('admin/entities'); ?>" class="nav-link active">
                    <i class="fas fa-cube"></i>
                    <span>Entités</span>
                </a>
                <a href="<?php echo url('admin/users'); ?>" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Filtres et Recherche -->
        <div class="entities-filters">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Rechercher une entité...">
            </div>
            <div class="filter-buttons">
                <select id="categoryFilter">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <select id="typeFilter">
                    <option value="">Tous les types</option>
                    <option value="saas">SaaS</option>
                    <option value="desktop">Bureau</option>
                    <option value="mobile">Mobile</option>
                </select>
            </div>
        </div>

        <!-- Liste des Entités -->
        <div class="entities-grid" id="entitiesList">
            <?php foreach ($entities as $entity): ?>
            <div class="entity-admin-card" data-category="<?php echo $entity['category_id']; ?>" data-type="<?php echo $entity['type']; ?>">
                <div class="card-header">
                    <div class="entity-type <?php echo $entity['type']; ?>">
                        <i class="fas fa-<?php echo $entity['type'] === 'saas' ? 'cloud' : ($entity['type'] === 'desktop' ? 'desktop' : 'mobile-alt'); ?>"></i>
                        <span><?php echo strtoupper($entity['type']); ?></span>
                    </div>
                    <div class="entity-actions">
                        <button class="btn-icon" onclick="editEntity(<?php echo $entity['id']; ?>)">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-icon danger" onclick="deleteEntity(<?php echo $entity['id']; ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <h3><?php echo htmlspecialchars($entity['name']); ?></h3>
                    <p class="entity-description"><?php echo htmlspecialchars($entity['short_description'] ?: $entity['description']); ?></p>
                    
                    <div class="entity-meta">
                        <span class="meta-item">
                            <i class="fas fa-layer-group"></i>
                            <?php echo $entity['category_name']; ?>
                        </span>
                        <?php if ($entity['version']): ?>
                        <span class="meta-item">
                            <i class="fas fa-tag"></i>
                            v<?php echo $entity['version']; ?>
                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="entity-stats">
                        <div class="stat">
                            <i class="fas fa-eye"></i>
                            <span><?php echo $entity['view_count']; ?> vues</span>
                        </div>
                        <div class="stat">
                            <i class="fas fa-download"></i>
                            <span><?php echo $entity['download_count']; ?> dl</span>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="status-badge <?php echo $entity['is_active'] ? 'active' : 'inactive'; ?>">
                        <?php echo $entity['is_active'] ? 'Actif' : 'Inactif'; ?>
                    </div>
                    <div class="featured-badge <?php echo $entity['is_featured'] ? 'featured' : ''; ?>">
                        <?php echo $entity['is_featured'] ? '⭐ En vedette' : ''; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Entité -->
<div id="entityModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Nouvelle Entité</h2>
            <button class="modal-close" onclick="closeEntityModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="entityForm" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="entityName">Nom de l'entité *</label>
                        <input type="text" id="entityName" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="entityCategory">Catégorie *</label>
                        <select id="entityCategory" name="category_id" required>
                            <option value="">Sélectionner une catégorie</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="entityType">Type *</label>
                        <select id="entityType" name="type" required onchange="toggleTypeFields()">
                            <option value="">Sélectionner un type</option>
                            <option value="saas">Logiciel SaaS</option>
                            <option value="desktop">Logiciel Bureau</option>
                            <option value="mobile">Application Mobile</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="entityVersion">Version</label>
                        <input type="text" id="entityVersion" name="version" placeholder="ex: 1.0.0">
                    </div>
                </div>

                <div class="form-group">
                    <label for="entityDescription">Description *</label>
                    <textarea id="entityDescription" name="description" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="entityShortDescription">Description courte</label>
                    <textarea id="entityShortDescription" name="short_description" rows="2" maxlength="300"></textarea>
                </div>

                <!-- Champs spécifiques au type -->
                <div id="saasFields" class="type-fields" style="display: none;">
                    <div class="form-group">
                        <label for="websiteUrl">URL du site web *</label>
                        <input type="url" id="websiteUrl" name="website_url" placeholder="https://">
                    </div>
                </div>

                <div id="desktopFields" class="type-fields" style="display: none;">
                    <div class="form-group">
                        <label for="desktopFile">Fichier (.zip) *</label>
                        <input type="file" id="desktopFile" name="file" accept=".zip">
                    </div>
                </div>

                <div id="mobileFields" class="type-fields" style="display: none;">
                    <div class="form-group">
                        <label for="playStoreUrl">Google Play Store</label>
                        <input type="url" id="playStoreUrl" name="play_store_url" placeholder="https://play.google.com/">
                    </div>
                    <div class="form-group">
                        <label for="appStoreUrl">Apple App Store</label>
                        <input type="url" id="appStoreUrl" name="app_store_url" placeholder="https://apps.apple.com/">
                    </div>
                    <div class="form-group">
                        <label for="mobileFile">Fichier APK (optionnel)</label>
                        <input type="file" id="mobileFile" name="file" accept=".apk">
                    </div>
                </div>

                <div class="form-group">
                    <label for="entityIcon">Icône</label>
                    <input type="file" id="entityIcon" name="icon" accept="image/png, image/jpeg, image/svg+xml">
                </div>

                <div class="form-group">
                    <label>Fonctionnalités</label>
                    <div id="featuresContainer">
                        <div class="feature-input">
                            <input type="text" name="features[]" placeholder="Ajouter une fonctionnalité">
                            <button type="button" class="btn-icon" onclick="addFeatureField()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-checkboxes">
                    <label class="checkbox">
                        <input type="checkbox" name="is_featured" id="isFeatured">
                        <span class="checkmark"></span>
                        Mettre en vedette
                    </label>
                    <label class="checkbox">
                        <input type="checkbox" name="is_active" id="isActive" checked>
                        <span class="checkmark"></span>
                        Activer l'entité
                    </label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeEntityModal()">Annuler</button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    <span id="submitButtonText">Créer l'entité</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Inclusion des assets -->
<link rel="stylesheet" href="<?php echo asset('css/admin.css'); ?>">
<script src="<?php echo asset('js/admin-entities.js'); ?>"></script>