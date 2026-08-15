<?php
// nest/app/core/installer.php
// Installation automatique de la base de données au démarrage du site.
// Dépend de config.php (constantes DB_*, BASE_PATH, LOG_PATH).

class Installer {

    /** Chemin du script SQL de création du schéma + données initiales. */
    public static function schemaPath() {
        return BASE_PATH . '/app/data/nest-software.sql';
    }

    /** Indique si la base semble déjà initialisée (table `users` présente). */
    public static function isInstalled() {
        try {
            $pdo = self::connectToDb();
            $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Crée la base (si absente), le schéma et les données initiales.
     * Retourne true si l'installation a été effectuée, false si rien à faire
     * (base déjà prête). Lance une PDOException en cas d'échec.
     */
    public static function ensureInstalled() {
        if (self::isInstalled()) {
            return false;
        }

        $schemaFile = self::schemaPath();
        if (!is_file($schemaFile)) {
            throw new RuntimeException('Script SQL d\'installation introuvable : ' . $schemaFile);
        }

        // Connexion au serveur MySQL sans base sélectionnée, avec exécution multi-requêtes.
        $dsn = 'mysql:host=' . DB_HOST . ';charset=' . DB_CHARSET;
        $pdo = new PDO(
            $dsn,
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
            ]
        );

        $sql = file_get_contents($schemaFile);
        if ($sql === false) {
            throw new RuntimeException('Impossible de lire le script SQL d\'installation.');
        }

        $pdo->exec($sql);

        // Création du répertoire d'uploads s'il n'existe pas.
        if (!is_dir(UPLOAD_PATH)) {
            @mkdir(UPLOAD_PATH, 0775, true);
        }

        self::log('Base de données initialisée automatiquement via ' . basename($schemaFile));

        return true;
    }

    /** Connecte PDO à la base nommée DB_NAME (échec si la base n'existe pas). */
    private static function connectToDb() {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        return new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    private static function log($message) {
        @file_put_contents(
            LOG_PATH . 'install.log',
            '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL,
            FILE_APPEND
        );
    }
}
