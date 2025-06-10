<?php
// Detectar automáticamente la URL base
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseFolder = '/proyectos/colaborativo/Grupo7';

// Definir constantes globales
define('BASE_URL', $protocol . $host . $baseFolder);
define('SITE_NAME', 'Guau - Tienda de Mascotas');

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'veterinaria');
define('DB_USER', 'root');
define('DB_PASS', '');

// Rutas de la aplicación
define('ROOT_PATH', dirname(__DIR__));
define('VISTA_PATH', ROOT_PATH . '/vista');
define('CONTROLADOR_PATH', ROOT_PATH . '/controlador');
define('MODELO_PATH', ROOT_PATH . '/modelo');
define('RECURSOS_PATH', ROOT_PATH . '/recursos');

// Configuración de la aplicación
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Configuración de errores en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de caracteres
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');

// Zona horaria
date_default_timezone_set('America/Bogota');

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
session_start();

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

// Función helper para generar URLs
function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
} 