<?php
namespace App\Core;

class Controller
{
    /**
     * Charge et affiche une vue avec des données
     *
     * @param string $view Chemin de la vue à charger
     * @param array $data Données à passer à la vue
     * @param bool $withLayout Inclure header et footer
     * @return void
     */
    protected function view(string $view, array $data = [], bool $withLayout = true): void
    {
        // Extraction des données pour les rendre disponibles comme variables dans la vue
        extract($data);

        if ($withLayout) {
            require_once '../app/views/header-view.php';
        }

        require_once '../app/views/' . $view . '.php';

        if ($withLayout) {
            require_once '../app/views/footer-view.php';
        }
    }

    /**
     * Redirige vers une URL
     *
     * @param string $url URL de redirection
     * @return void
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . getenv("HOST") . $url);
        if (!isset($_SESSION['redirect_count'])) {
            $_SESSION['redirect_count'] = 0;
        }

        $_SESSION['redirect_count']++;

        if ($_SESSION['redirect_count'] > 3) {
            $_SESSION['redirect_count'] = 0;
            echo "Erreur: Trop de redirections. Vérifiez la logique de votre application.";
            exit;
        }
        exit;
    }

    /**
     * Vérifie si l'utilisateur est connecté
     *
     * @return bool
     */
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['userId']);
    }

    /**
     * Nécessite une connexion utilisateur, sinon redirige vers login
     *
     * @return void
     */
    protected function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/user/login');
        }
    }

    /**
     * Récupère une instance de la base de données
     *
     * @return Database
     */
    protected function getDatabase(): Database
    {
        return Database::getInstance();
    }

    /**
     * Retourne les données en format JSON (pour les API)
     *
     * @param array $data Données à renvoyer
     * @param int $statusCode Code HTTP
     * @return void
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}