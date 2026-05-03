<?php
/**
 * Contrôleur de base
 * Tous les contrôleurs héritent de cette classe
 */
abstract class Controller
{
    protected string $viewsPath;

    public function __construct()
    {
        $this->viewsPath = dirname(__DIR__) . '/app/views/';
    }

    /**
     * Charge et affiche une vue avec le layout
     *
     * @param string $view   Chemin relatif de la vue (ex: 'dashboard/index')
     * @param array  $data   Données à passer à la vue
     * @param bool   $layout Inclure le layout complet (header/sidebar/footer)
     */
    protected function render(string $view, array $data = [], bool $layout = true): void
    {
        // Extrait les données pour les rendre disponibles dans la vue
        extract($data);

        $viewFile = $this->viewsPath . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new RuntimeException("Vue introuvable : $viewFile");
        }

        if ($layout) {
            // Capture le contenu de la vue
            ob_start();
            require $viewFile;
            $content = ob_get_clean();

            // Affiche le layout complet
            require $this->viewsPath . 'layout/header.php';
            require $this->viewsPath . 'layout/sidebar.php';
            echo '<main class="main-content">';
            echo $content;
            echo '</main>';
            require $this->viewsPath . 'layout/footer.php';
        } else {
            require $viewFile;
        }
    }

    /**
     * Redirige vers une URL
     *
     * @param string $url URL de destination (relative au BASE_URL)
     */
    protected function redirect(string $url): void
    {
        $fullUrl = (strpos($url, 'http') === 0) ? $url : BASE_URL . '/' . ltrim($url, '/');
        header('Location: ' . $fullUrl);
        exit;
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Force l'authentification - redirige vers login si non connecté
     */
    protected function requireAuth(): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Retourne l'ID de l'utilisateur connecté
     */
    protected function getUserId(): int
    {
        return (int) ($_SESSION['user_id'] ?? 0);
    }

    /**
     * Retourne les données de l'utilisateur connecté
     */
    protected function getCurrentUser(): array
    {
        return $_SESSION['user'] ?? [];
    }

    /**
     * Définit un message flash dans la session
     *
     * @param string $type    Type: 'success', 'error', 'warning', 'info'
     * @param string $message Le message à afficher
     */
    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type'    => $type,
            'message' => $message,
        ];
    }

    /**
     * Récupère et supprime le message flash de la session
     */
    protected function getFlash(): ?array
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Vérifie si la requête est un POST
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Nettoie et retourne une valeur POST
     */
    protected function post(string $key, mixed $default = null): mixed
    {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }

    /**
     * Nettoie et retourne une valeur GET
     */
    protected function get(string $key, mixed $default = null): mixed
    {
        return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
    }

    /**
     * Échappe les caractères HTML pour l'affichage
     */
    protected function e(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
