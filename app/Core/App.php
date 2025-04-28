<?php
namespace App\Core;

class App
{
    protected string $controller = 'HomeController';
    protected string $method = 'index';
    protected array $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // Si un premier segment d'URL existe, tentons de trouver le contrôleur correspondant
        if (isset($url[0]) && $url[0] != 'api') {
            $controllerName = ucfirst($url[0]) . 'Controller';

            if (file_exists("../app/Controllers/{$controllerName}.php")) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        if(!isset($_SESSION['userId']) && $this->controller != 'HomeController' && $this->controller != 'UserController' && $this->controller != 'ContactController' && $this->controller != 'FaqController') {
            header('Location: /');
            exit();
        }

        // Création de l'instance du contrôleur
        $controllerClass = "\\App\\Controllers\\" . $this->controller;
        $controller = new $controllerClass();

        // Vérification de la méthode à appeler
        if (isset($url[1])) {
            if (method_exists($controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Récupérer les paramètres restants
        $this->params = $url ? array_values($url) : [];

        // Appel de la méthode du contrôleur avec les paramètres
        call_user_func_array([$controller, $this->method], $this->params);
    }

    /**
     * Analyse l'URL pour la transformer en array de segments.
     */
    public function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }

        // Si pas d'URL dans GET, analyser REQUEST_URI pour trouver la route
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        $basePath = '/App/'; // Chemin de base de l'application

        if (strpos($requestUri, $basePath) !== false) {
            $path = substr($requestUri, strpos($requestUri, $basePath) + strlen($basePath));
            $path = trim($path, '/');

            if (!empty($path)) {
                return explode('/', $path);
            }
        }

        return []; // Retourne un tableau vide pour la route par défaut
    }

}