<?php
/**
 * Configuration de la base de données
 * Connexion PDO avec pattern Singleton
 */

define('DB_HOST', '127.0.0.1');
define('DB_PORT', 8889);
define('DB_NAME', 'studentlife_hub');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_CHARSET', 'utf8mb4');

class Database
{
    private static ?PDO $instance = null;

    /**
     * Empêche l'instanciation directe
     */
    private function __construct() {}

    /**
     * Retourne l'instance unique PDO (Singleton)
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET . ';port=' . DB_PORT;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // En production, ne pas afficher le message d'erreur brut
                error_log('Erreur de connexion DB: ' . $e->getMessage());
                die(json_encode([
                    'error' => true,
                    'message' => 'Impossible de se connecter à la base de données. Veuillez réessayer plus tard.'
                ]));
            }
        }
        return self::$instance;
    }

    /**
     * Empêche le clonage
     */
    private function __clone() {}
}
