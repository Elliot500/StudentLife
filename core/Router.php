<?php
/**
 * Router - Analyse l'URL et dispatche vers le bon contrôleur/action
 */
class Router
{
    private string $controllersPath;
    private array $publicRoutes = ['auth/login', 'auth/register', 'home/index'];

    public function __construct()
    {
        $this->controllersPath = dirname(__DIR__) . '/app/controllers/';
    }

    /**
     * Dispatche la requête vers le bon contrôleur et la bonne action
     */
    public function dispatch(): void
    {
        $url = $this->parseUrl();

        $controllerName = $url[0] ?? 'home';
        $actionName     = $url[1] ?? 'index';
        $param          = $url[2] ?? null;

        // Normalisation
        $controllerName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $controllerName));
        $actionName     = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $actionName));

        $routeKey = $controllerName . '/' . $actionName;

        // Vérifie l'authentification (sauf pour les routes publiques)
        if (!in_array($routeKey, $this->publicRoutes) && !$this->isLoggedIn()) {
            $this->redirectToLogin();
            return;
        }

        // Construit le nom de la classe contrôleur
        $controllerClass = ucfirst($controllerName) . 'Controller';
        $controllerFile  = $this->controllersPath . $controllerClass . '.php';

        if (!file_exists($controllerFile)) {
            $this->handle404();
            return;
        }

        require_once $controllerFile;

        if (!class_exists($controllerClass)) {
            $this->handle404();
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $actionName)) {
            $this->handle404();
            return;
        }

        // Appelle l'action avec ou sans paramètre
        if ($param !== null) {
            $controller->$actionName($param);
        } else {
            $controller->$actionName();
        }
    }

    /**
     * Parse l'URL en tableau de segments
     */
    private function parseUrl(): array
    {
        $url = $_GET['url'] ?? '';
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return array_values(array_filter(explode('/', $url)));
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    private function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Redirige vers la page de connexion
     */
    private function redirectToLogin(): void
    {
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }

    /**
     * Gère les erreurs 404
     */
    private function handle404(): void
    {
        http_response_code(404);
        echo '<h1>404 - Page non trouvée</h1>';
        echo '<p><a href="' . BASE_URL . '">Retour à l\'accueil</a></p>';
        exit;
    }
}
