-- =============================================
-- NEST SOFTWARE - SCRIPT D'INSTALLATION DE BASE DE DONNÉES
-- Version: 3.1.0
-- Date: 2026
-- Auteur: Nest Corporation
-- Compatible: MariaDB/MySQL
--
-- Usage :
--   - Exécuté automatiquement par app/core/installer.php au démarrage du site
--     si la base n'est pas encore initialisée (table `users` absente).
--   - Ré-exécutable manuellement : le script recrée proprement les tables.
--
-- NB : Aucun compte utilisateur n'est inséré. Le PREMIER utilisateur qui
--      crée un compte devient automatiquement ADMINISTRATEUR (super_admin).
-- =============================================

-- Création de la base de données (si elle n'existe pas déjà)
CREATE DATABASE IF NOT EXISTS `nest_software`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `nest_software`;

SET FOREIGN_KEY_CHECKS = 0;

-- =============================================
-- SUPPRESSION DES ANCIENNES TABLES (ordre inverse des dépendances)
-- =============================================
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `remember_tokens`;
DROP TABLE IF EXISTS `admin_logs`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `product_stats`;
DROP TABLE IF EXISTS `product_features`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `categories`;

-- =============================================
-- TABLE: users - Gestion des utilisateurs
-- =============================================
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('user','admin','super_admin') DEFAULT 'user',

    -- Sécurité
    `login_attempts` INT DEFAULT 0,
    `locked_until` TIMESTAMP NULL,

    -- Statut
    `is_active` TINYINT(1) DEFAULT 1,
    `avatar` VARCHAR(255) DEFAULT NULL,
    `last_login` TIMESTAMP NULL,

    -- Consentements
    `accepted_terms` TINYINT(1) DEFAULT 0,
    `newsletter_subscribed` TINYINT(1) DEFAULT 0,
    `accepted_terms_at` TIMESTAMP NULL,
    `newsletter_subscribed_at` TIMESTAMP NULL,

    -- Timestamps
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Index
    INDEX `idx_email` (`email`),
    INDEX `idx_role` (`role`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: categories - Familles de produits
-- =============================================
CREATE TABLE `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `icon` VARCHAR(50) DEFAULT NULL,
    `color` VARCHAR(7) DEFAULT '#0066FF',
    `is_active` TINYINT(1) DEFAULT 1,
    `display_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Index
    INDEX `idx_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: products - Produits (logiciels, SaaS, Android, appareils)
-- =============================================
CREATE TABLE `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(200) NOT NULL UNIQUE,
    `description` TEXT NOT NULL,
    `short_description` VARCHAR(300) DEFAULT NULL,

    -- Classification
    `category_id` INT NOT NULL,

    -- Prix (USD)
    `pricing_type` ENUM('free','one_time','subscription') NOT NULL DEFAULT 'one_time',
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `subscription_period` ENUM('monthly','yearly') NULL,
    `trial_days` INT DEFAULT 0,

    -- Métadonnées
    `version` VARCHAR(50) DEFAULT '1.0.0',
    `developer` VARCHAR(100) DEFAULT 'Nest Corporation',

    -- URLs
    `website_url` VARCHAR(255) DEFAULT NULL,
    `documentation_url` VARCHAR(255) DEFAULT NULL,
    `play_store_url` VARCHAR(255) DEFAULT NULL,
    `app_store_url` VARCHAR(255) DEFAULT NULL,

    -- Fichiers et médias
    `icon_path` VARCHAR(255) DEFAULT NULL,
    `image_path` VARCHAR(255) DEFAULT NULL,
    `file_path` VARCHAR(255) DEFAULT NULL,
    `file_size` BIGINT(20) DEFAULT 0,

    -- Liaison entre produits (appareil -> logiciel de pilotage / accessoires)
    `parent_id` INT DEFAULT NULL,
    `link_type` ENUM('control_software','accessory') NULL,

    -- Statut
    `is_featured` TINYINT(1) DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,

    -- Statistiques
    `download_count` INT DEFAULT 0,
    `view_count` INT DEFAULT 0,

    -- Dates
    `release_date` DATE DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Clés étrangères
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
    FOREIGN KEY (`parent_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,

    -- Index
    INDEX `idx_category` (`category_id`),
    INDEX `idx_pricing` (`pricing_type`),
    INDEX `idx_parent` (`parent_id`),
    INDEX `idx_featured` (`is_featured`),
    INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: product_features - Fonctionnalités des produits
-- =============================================
CREATE TABLE `product_features` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `feature_text` VARCHAR(200) NOT NULL,
    `display_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Clés étrangères
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,

    -- Index
    INDEX `idx_product` (`product_id`),
    INDEX `idx_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: product_stats - Statistiques des produits
-- =============================================
CREATE TABLE `product_stats` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `stat_type` ENUM('download','view','order') NOT NULL,
    `user_id` INT DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Clés étrangères
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,

    -- Index
    INDEX `idx_product_type` (`product_id`, `stat_type`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: projects - Projets / réalisations
-- =============================================
CREATE TABLE `projects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(200) NOT NULL UNIQUE,
    `description` TEXT NOT NULL,
    `category` ENUM('software','electronics','iot','manufacturing') NOT NULL DEFAULT 'software',
    `client` VARCHAR(150) DEFAULT NULL,
    `year` VARCHAR(10) DEFAULT NULL,
    `image_path` VARCHAR(255) DEFAULT NULL,
    `link` VARCHAR(255) DEFAULT NULL,
    `tags` VARCHAR(255) DEFAULT NULL,
    `is_featured` TINYINT(1) DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Index
    INDEX `idx_category` (`category`),
    INDEX `idx_featured` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: orders - Commandes (gratuit/unique/abonnement)
-- =============================================
CREATE TABLE `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT DEFAULT NULL,
    `product_id` INT NOT NULL,
    `product_name` VARCHAR(200) NOT NULL,
    `pricing_type` ENUM('free','one_time','subscription') NOT NULL,
    `subscription_period` ENUM('monthly','yearly') NULL,
    `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `currency` VARCHAR(3) DEFAULT 'USD',
    `status` ENUM('pending','confirmed') DEFAULT 'pending',
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Clés étrangères
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),

    -- Index
    INDEX `idx_user` (`user_id`),
    INDEX `idx_product` (`product_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: admin_logs - Journal d'administration
-- =============================================
CREATE TABLE `admin_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `action` VARCHAR(100) NOT NULL,
    `description` TEXT NOT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Clés étrangères
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,

    -- Index
    INDEX `idx_user_action` (`user_id`, `action`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: remember_tokens - Tokens de connexion persistante
-- =============================================
CREATE TABLE `remember_tokens` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `token` VARCHAR(64) NOT NULL UNIQUE,
    `expires_at` TIMESTAMP NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Clés étrangères
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,

    -- Index
    INDEX `idx_token` (`token`),
    INDEX `idx_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: sessions - Sessions utilisateurs
-- =============================================
CREATE TABLE `sessions` (
    `id` VARCHAR(128) PRIMARY KEY,
    `user_id` INT NOT NULL,
    `ip_address` VARCHAR(45),
    `user_agent` TEXT,
    `payload` TEXT,
    `last_activity` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Clés étrangères
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,

    -- Index
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_last_activity` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- DONNÉES INITIALES (contenu de démonstration)
-- NB : AUCUN UTILISATEUR n'est inséré volontairement :
--      le premier compte créé deviendra automatiquement administrateur.
-- =============================================

-- =============================================
-- CATÉGORIES (4 familles de produits)
-- =============================================
INSERT INTO `categories` (`name`, `slug`, `description`, `icon`, `color`, `display_order`, `is_active`) VALUES
(
    'Logiciels PC',
    'desktop',
    'Logiciels téléchargeables pour Windows, macOS et Linux',
    'fa-desktop',
    '#0066FF',
    1,
    1
),
(
    'Logiciels en ligne',
    'saas',
    'Solutions SaaS accessibles depuis n''importe quel navigateur',
    'fa-cloud',
    '#00D4FF',
    2,
    1
),
(
    'Applications Android',
    'android',
    'Applications mobiles pour Android, téléchargeables ou en abonnement',
    'fa-android',
    '#16a34a',
    3,
    1
),
(
    'Appareils électroniques',
    'hardware',
    'Objets connectés, machines et appareils intelligents fabriqués par Nest',
    'fa-microchip',
    '#9d4edd',
    4,
    1
);

-- =============================================
-- PRODUITS
-- =============================================

-- --- Logiciels PC ---
INSERT INTO `products` (
    `name`, `slug`, `description`, `short_description`, `category_id`, `pricing_type`,
    `price`, `version`, `developer`, `website_url`, `is_featured`, `is_active`, `release_date`
) VALUES
(
    'Nest Accounting',
    'nest-accounting',
    'Logiciel de comptabilité professionnelle avec gestion de facturation, suivi de trésorerie, rapports financiers et support multi-devises. Conçu pour les PME et les indépendants.',
    'Comptabilité et facturation professionnelles',
    1,
    'one_time',
    199.00,
    '3.2.0',
    'Nest Corporation',
    'https://nest-software.com/accounting',
    1,
    1,
    '2025-11-15'
),
(
    'Nest Office Pro',
    'nest-office-pro',
    'Suite bureautique complète avec traitement de texte, tableur, présentations et stockage cloud. Mises à jour régulières incluses dans l''abonnement.',
    'Suite bureautique complète en abonnement',
    1,
    'subscription',
    9.99,
    '2.5.1',
    'Nest Corporation',
    'https://nest-software.com/office',
    1,
    1,
    '2025-12-01'
);

-- --- Logiciels en ligne (SaaS) ---
INSERT INTO `products` (
    `name`, `slug`, `description`, `short_description`, `category_id`, `pricing_type`,
    `price`, `subscription_period`, `trial_days`, `version`, `website_url`, `is_featured`, `is_active`, `release_date`
) VALUES
(
    'Nest CRM Cloud',
    'nest-crm-cloud',
    'Solution CRM en ligne pour gérer vos clients, opportunités et pipelines de vente. Accès depuis n''importe quel navigateur, sans installation.',
    'Gestion de la relation client en ligne',
    2,
    'subscription',
    29.00,
    'monthly',
    14,
    '4.0.0',
    'https://crm.nest-software.com',
    1,
    1,
    '2026-01-10'
),
(
    'Nest Analytics Web',
    'nest-analytics-web',
    'Plateforme d''analyse de données en ligne avec tableaux de bord interactifs, rapports automatisés et intégrations API.',
    'Analyse de données en ligne',
    2,
    'one_time',
    149.00,
    NULL,
    7,
    '1.8.0',
    'https://analytics.nest-software.com',
    0,
    1,
    '2025-10-20'
);

-- --- Applications Android ---
INSERT INTO `products` (
    `name`, `slug`, `description`, `short_description`, `category_id`, `pricing_type`,
    `price`, `play_store_url`, `is_featured`, `is_active`, `release_date`
) VALUES
(
    'Nest Pay',
    'nest-pay',
    'Application Android de paiement et de gestion de budget avec portefeuille numérique, alertes de dépenses et transferts sécurisés.',
    'Paiement mobile et gestion de budget',
    3,
    'free',
    0.00,
    'https://play.google.com/store',
    1,
    1,
    '2026-02-01'
),
(
    'Nest Home Remote',
    'nest-home-remote',
    'Application Android de pilotage de nos appareils intelligents : commande vocale, scénarios automatisés et suivi de consommation énergétique.',
    'Télécommande universelle pour appareils Nest',
    3,
    'subscription',
    2.99,
    'https://play.google.com/store',
    1,
    1,
    '2026-03-05'
);

-- --- Appareils électroniques ---
INSERT INTO `products` (
    `name`, `slug`, `description`, `short_description`, `category_id`, `pricing_type`,
    `price`, `version`, `is_featured`, `is_active`, `release_date`
) VALUES
(
    'NestHome Hub',
    'nesthome-hub',
    'Hub domotique central fabriqué par Nest : contrôle de vos appareils connectés, capteurs et automatisations. Compatible Wi-Fi, Zigbee et Bluetooth. Livré avec sa box et son alimentation.',
    'Hub domotique intelligent fabriqué localement',
    4,
    'one_time',
    89.00,
    '1.0.0',
    1,
    1,
    '2026-04-01'
),
(
    'NestSense Capteur',
    'nestsense-capteur',
    'Capteur de température et d''humidité connecté, fonctionne avec le NestHome Hub. Batterie longue durée et configuration simplifiée.',
    'Capteur température/humidité connecté',
    4,
    'one_time',
    24.50,
    '1.0.0',
    0,
    1,
    '2026-04-15'
);

-- =============================================
-- PRODUITS LIÉS (logiciels de pilotage / accessoires)
-- =============================================

-- Logiciel de pilotage du NestHome Hub (SaaS en ligne)
INSERT INTO `products` (
    `name`, `slug`, `description`, `short_description`, `category_id`, `pricing_type`,
    `price`, `subscription_period`, `trial_days`, `parent_id`, `link_type`, `is_active`, `release_date`
) VALUES
(
    'NestHome Dashboard',
    'nesthome-dashboard',
    'Logiciel de pilotage du NestHome Hub : configuration, scénarios d''automatisation, suivi de consommation et alertes en temps réel.',
    'Logiciel de pilotage du NestHome Hub',
    2,
    'subscription',
    3.99,
    'monthly',
    30,
    7,
    'control_software',
    1,
    '2026-04-01'
);

-- Accessoire : module d'extension radio du NestHome Hub
INSERT INTO `products` (
    `name`, `slug`, `description`, `short_description`, `category_id`, `pricing_type`,
    `price`, `parent_id`, `link_type`, `is_active`, `release_date`
) VALUES
(
    'NestHome Antenne',
    'nesthome-antenne',
    'Antenne d''extension pour étendre la portée radio du NestHome Hub jusqu''à 100 mètres.',
    'Extension de portée pour NestHome Hub',
    4,
    'one_time',
    12.00,
    7,
    'accessory',
    1,
    '2026-04-10'
);

-- =============================================
-- FONCTIONNALITÉS DES PRODUITS
-- =============================================
INSERT INTO `product_features` (`product_id`, `feature_text`, `display_order`) VALUES
-- Nest Accounting
(1, 'Facturation et devis illimités', 1),
(1, 'Suivi de trésorerie en temps réel', 2),
(1, 'Rapports financiers automatisés', 3),
(1, 'Support multi-devises', 4),

-- Nest Office Pro
(2, 'Traitement de texte avancé', 1),
(2, 'Tableur avec formules et graphiques', 2),
(2, 'Présentations professionnelles', 3),
(2, 'Stockage cloud 100 Go', 4),

-- Nest CRM Cloud
(3, 'Pipelines de vente personnalisables', 1),
(3, 'Suivi des prospects et opportunités', 2),
(3, 'Automatisation des relances', 3),
(3, 'Intégrations email et WhatsApp', 4),

-- Nest Analytics Web
(4, 'Tableaux de bord interactifs', 1),
(4, 'Rapports automatisés', 2),
(4, 'Intégration API REST', 3),
(4, 'Multi-utilisateurs', 4),

-- Nest Pay
(5, 'Portefeuille numérique sécurisé', 1),
(5, 'Alertes de dépenses en temps réel', 2),
(5, 'Transferts entre utilisateurs', 3),
(5, 'Historique et catégorisation', 4),

-- Nest Home Remote
(6, 'Commande vocale', 1),
(6, 'Scénarios d''automatisation', 2),
(6, 'Suivi de consommation énergétique', 3),
(6, 'Compatible NestHome Hub', 4),

-- NestHome Hub
(7, 'Contrôle de 50+ appareils connectés', 1),
(7, 'Compatibilité Wi-Fi, Zigbee, Bluetooth', 2),
(7, 'Automatisations personnalisables', 3),
(7, 'Fabriqué et assemblé en Afrique', 4),

-- NestSense Capteur
(8, 'Mesure température et humidité', 1),
(8, 'Batterie longue durée (12 mois)', 2),
(8, 'Configuration simplifiée via NestHome Dashboard', 3),
(8, 'Alerte en cas de valeurs anormales', 4),

-- NestHome Dashboard
(9, 'Configuration du hub', 1),
(9, 'Scénarios d''automatisation', 2),
(9, 'Suivi de consommation', 3),
(9, 'Alertes temps réel', 4),

-- NestHome Antenne
(10, 'Portée étendue à 100 m', 1),
(10, 'Installation plug & play', 2);

-- =============================================
-- PROJETS / RÉALISATIONS
-- =============================================
INSERT INTO `projects` (`title`, `slug`, `description`, `category`, `client`, `year`, `tags`, `is_featured`, `is_active`) VALUES
(
    'NestHome Hub',
    'nesthome-hub-project',
    'Conception et réalisation complète d''un hub domotique : électronique embarquée, firmware, logiciel de pilotage en ligne et application Android. Produit assemblé localement.',
    'electronics',
    'Nest Corporation',
    '2026',
    'IoT, embarqué, domotique',
    1,
    1
),
(
    'Nest Accounting',
    'nest-accounting-project',
    'Développement d''un logiciel de comptabilité desktop avec licence à paiement unique, déployé sur les postes clients et disponible en version portable.',
    'software',
    'PME & indépendants',
    '2025',
    'PHP, MySQL, desktop',
    1,
    1
),
(
    'Nest CRM Cloud',
    'nest-crm-cloud-project',
    'Conception d''une solution SaaS déployée sur VPS cloud avec abonnement mensuel, essai gratuit 14 jours et infrastructure sécurisée.',
    'software',
    'Équipes commerciales',
    '2026',
    'SaaS, cloud, VPS',
    1,
    1
),
(
    'Machine de tri intelligent',
    'machine-tri-intelligent',
    'Prototype d''une machine de tri automatisée pour l''agro-industrie : capteurs, système embarqué et supervision à distance. Objectif : industrialiser la fabrication en Afrique.',
    'manufacturing',
    'Agro-industrie',
    '2026',
    'embarqué, capteurs, supervision',
    0,
    1
);

-- =============================================
-- STATISTIQUES DE DÉMONSTRATION
-- =============================================
INSERT INTO `product_stats` (`product_id`, `stat_type`, `user_id`, `ip_address`) VALUES
(1, 'view', NULL, '127.0.0.1'),
(1, 'download', NULL, '127.0.0.1'),
(2, 'view', NULL, '192.168.1.100'),
(3, 'view', NULL, '127.0.0.1'),
(3, 'order', NULL, '127.0.0.1'),
(4, 'view', NULL, '192.168.1.101'),
(5, 'view', NULL, '192.168.1.102'),
(5, 'download', NULL, '192.168.1.102'),
(6, 'view', NULL, '127.0.0.1'),
(7, 'view', NULL, '127.0.0.1'),
(7, 'order', NULL, '192.168.1.103'),
(8, 'view', NULL, '127.0.0.1');

-- =============================================
-- COMMANDES DE DÉMONSTRATION (commandes "invité", user_id NULL)
-- =============================================
INSERT INTO `orders` (`user_id`, `product_id`, `product_name`, `pricing_type`, `subscription_period`, `amount`, `status`, `ip_address`) VALUES
(NULL, 3, 'Nest CRM Cloud', 'subscription', 'monthly', 29.00, 'confirmed', '127.0.0.1'),
(NULL, 7, 'NestHome Hub', 'one_time', NULL, 89.00, 'confirmed', '127.0.0.1'),
(NULL, 5, 'Nest Pay', 'free', NULL, 0.00, 'confirmed', '127.0.0.1');

-- =============================================
-- VUES POUR LES STATISTIQUES
-- =============================================
DROP VIEW IF EXISTS `user_stats`;
DROP VIEW IF EXISTS `product_stats_summary`;
DROP VIEW IF EXISTS `admin_dashboard_stats`;

-- Vue statistiques utilisateurs
CREATE VIEW `user_stats` AS
SELECT
    COUNT(*) as total_users,
    COUNT(CASE WHEN `role` = 'admin' OR `role` = 'super_admin' THEN 1 END) as admin_users,
    COUNT(CASE WHEN `newsletter_subscribed` = 1 THEN 1 END) as newsletter_subscribers,
    COUNT(CASE WHEN `is_active` = 1 THEN 1 END) as active_users,
    COUNT(CASE WHEN DATE(`created_at`) = CURDATE() THEN 1 END) as new_users_today,
    DATE(`created_at`) as registration_date
FROM `users`
GROUP BY DATE(`created_at`);

-- Vue statistiques produits
CREATE VIEW `product_stats_summary` AS
SELECT
    COUNT(*) as total_products,
    COUNT(CASE WHEN `is_active` = 1 THEN 1 END) as active_products,
    COUNT(CASE WHEN `category_id` = 1 THEN 1 END) as desktop_products,
    COUNT(CASE WHEN `category_id` = 2 THEN 1 END) as saas_products,
    COUNT(CASE WHEN `category_id` = 3 THEN 1 END) as android_products,
    COUNT(CASE WHEN `category_id` = 4 THEN 1 END) as hardware_products,
    COUNT(CASE WHEN `pricing_type` = 'free' THEN 1 END) as free_products,
    COUNT(CASE WHEN `pricing_type` = 'one_time' THEN 1 END) as one_time_products,
    COUNT(CASE WHEN `pricing_type` = 'subscription' THEN 1 END) as subscription_products,
    SUM(`download_count`) as total_downloads,
    SUM(`view_count`) as total_views,
    COUNT(CASE WHEN `is_featured` = 1 THEN 1 END) as featured_products
FROM `products`;

-- Vue dashboard admin
CREATE VIEW `admin_dashboard_stats` AS
SELECT
    (SELECT COUNT(*) FROM `users`) as total_users,
    (SELECT COUNT(*) FROM `products`) as total_products,
    (SELECT COUNT(*) FROM `projects`) as total_projects,
    (SELECT COUNT(*) FROM `orders`) as total_orders,
    (SELECT IFNULL(SUM(`amount`),0) FROM `orders` WHERE `status` = 'confirmed') as total_revenue,
    (SELECT COUNT(*) FROM `orders` WHERE DATE(`created_at`) = CURDATE()) as orders_today,
    (SELECT COUNT(*) FROM `users` WHERE DATE(`created_at`) = CURDATE()) as new_users_today,
    (SELECT COUNT(*) FROM `admin_logs`) as total_logs;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- RAPPORT FINAL D'INSTALLATION
-- =============================================

SELECT '=== BASE DE DONNÉES NEST SOFTWARE INSTALLÉE AVEC SUCCÈS ===' as message;

SELECT
    'STRUCTURE' as type,
    '11 tables + 3 vues + contenu de démonstration' as details;

SELECT
    'COMPTES' as type,
    'Aucun compte pré-créé : le PREMIER utilisateur inscrit devient ADMINISTRATEUR (super_admin)' as details;

SELECT '=== PRÊT POUR LA PRODUCTION ===' as statut;
