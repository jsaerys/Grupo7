<?php
// Configuración de la aplicación
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/proyectos/colaborativo/Grupo7');
}

// Configuración de errores en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de caracteres
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');

// Zona horaria
date_default_timezone_set('America/Bogota');

// Configuración de sesión (solo si no está iniciada)
if (session_status() === PHP_SESSION_NONE) {
    // Configurar opciones de sesión antes de iniciarla
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

// Autoload de clases
spl_autoload_register(function ($class_name) {
    $paths = [
        'controllers/',
        'models/',
        'helpers/'
    ];
    
    foreach ($paths as $path) {
        $file = BASE_PATH . '/' . $path . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
}); 