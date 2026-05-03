<?php
// Quand utilisé comme router script du serveur PHP intégré :
// php -S localhost:8000 index.php
// → les fichiers statiques (CSS, images…) sont servis directement
if (php_sapi_name() === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if (is_file($file)) {
        return false; // laisser le serveur servir le fichier statique
    }
}

session_start();

define('ROOT_PATH', dirname(__DIR__));
define('BASE_URL', '');

// Extraire l'URL depuis REQUEST_URI (pas de .htaccess avec le serveur intégré)
if (empty($_GET['url'])) {
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
    if ($path !== '' && $path !== 'index.php') {
        $_GET['url'] = $path;
    }
}

spl_autoload_register(function (string $class): void {
    $paths = [
        ROOT_PATH . '/core/' . $class . '.php',
        ROOT_PATH . '/app/controllers/' . $class . '.php',
        ROOT_PATH . '/app/models/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/app/helpers/functions.php';

$router = new Router();
$router->dispatch();
