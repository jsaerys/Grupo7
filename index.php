<?php
// Prevenir el envío de headers antes de session_start
ob_start();

// Iniciar sesión
session_start();

// Configuración de errores en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir constantes
define('BASE_PATH', __DIR__);
define('BASE_URL', '/proyectos/colaborativo/Grupo7');

// Cargar configuración
require_once 'config/config.php';
require_once 'config/database.php';

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

try {
    // Obtener la página y acción de la URL
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    $action = isset($_GET['action']) ? $_GET['action'] : 'index';

    // Mapeo de rutas a controladores
    $routes = [
        'home' => 'HomeController',
        'auth' => 'AuthController',
        'products' => 'ProductController',
        'admin' => 'AdminController'
    ];

    // Verificar si la ruta existe
    if (!array_key_exists($page, $routes)) {
        throw new Exception("Página no encontrada", 404);
    }

    // Crear instancia del controlador
    $controller_name = $routes[$page];
    $controller = new $controller_name();

    // Verificar si el método existe
    if (!method_exists($controller, $action)) {
        throw new Exception("Acción no encontrada", 404);
    }

    // Llamar al método del controlador
    $controller->$action();

} catch (Exception $e) {
    $status_code = $e->getCode() ?: 500;
    http_response_code($status_code);
    
    switch ($status_code) {
        case 404:
            require_once 'views/404.php';
            break;
        default:
            require_once 'views/error.php';
    }
}

// Asegurarse de que todo el output ha sido enviado
ob_end_flush(); 