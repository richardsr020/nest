<?php
// nest/app/data/database.php
require_once __DIR__ . '/../config.php';

class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            self::boot();
        }
        return self::$connection;
    }

    /**
     * Connexion + initialisation automatique de la base de données.
     * Si la base n'existe pas ou que le schéma est absent, l'installateur
     * est exécuté (voir app/core/installer.php) puis on reconnecte.
     */
    private static function boot() {
        require_once __DIR__ . '/../core/installer.php';

        try {
            self::$connection = self::connect();
        } catch (PDOException $e) {
            // Base absente ou serveur indisponible -> tentative d'installation automatique.
            try {
                Installer::ensureInstalled();
                self::$connection = self::connect();
            } catch (Throwable $installError) {
                self::fatal('Erreur de connexion à la base de données. Vérifiez la configuration.', $installError);
            }
        }

        // Base existante mais schéma manquant (installation partielle).
        try {
            if (!Installer::isInstalled()) {
                Installer::ensureInstalled();
                self::$connection = self::connect();
            }
        } catch (Throwable $installError) {
            self::fatal('Erreur d\'initialisation de la base de données.', $installError);
        }
    }

    private static function connect() {
        return new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }

    private static function fatal($message, $error) {
        error_log($message . ' : ' . $error->getMessage());
        if (APP_ENV === 'development') {
            die($message . '<br><small>' . htmlspecialchars($error->getMessage()) . '</small>');
        }
        die($message);
    }
}
