<?php
// Copie ce fichier en database.php et remplis tes identifiants
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3306);        // 8889 si MAMP
define('DB_NAME', 'studentlife_hub');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_CHARSET', 'utf8mb4');

class Database
{
    private static ?PDO $instance = null;
    private function __construct() {}

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET.';port='.DB_PORT;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                error_log('Erreur DB: ' . $e->getMessage());
                die(json_encode(['error' => true, 'message' => 'Connexion impossible.']));
            }
        }
        return self::$instance;
    }

    private function __clone() {}
}
