<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require __DIR__.'/../vendor/autoload.php';
define("ROOT", dirname(__DIR__));


// Autoloader simple
spl_autoload_register(function($class) {
    // Convertir les namespace en chemin de fichier
    $class = str_replace('App\\', '', $class);
    $class = str_replace('\\', '/', $class);
    $path = ROOT . '/app/' . lcfirst($class) . '.php';

    // Vérifier si le fichier existe
    if(file_exists($path)) {
        require_once $path;
        return true;
    }
    return false;
});

// Charger les variables d'environnement depuis .env
if(file_exists(ROOT . '/.env')) {
    $lines = file(ROOT . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach($lines as $line) {
        if(strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($name, $value) = explode('=', $line, 2);
            putenv(trim($name).'='.trim($value));
            $_ENV[trim($name)] = trim($value);
        }
    }
}


// Charger l'application
$app = new App\Core\App();