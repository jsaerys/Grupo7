<?php
session_start();
require_once __DIR__ . '/configuracion/config.php';
require_once __DIR__ . '/configuracion/db.php';
require_once __DIR__ . '/controlador/AuthController.php';

$authController = new AuthController();

// Obtener la página solicitada
$page = $_GET['page'] ?? 'login';

// Rutas protegidas que requieren autenticación
$protected_routes = ['panel_principal', 'registrar_mascota'];

// Verificar si la ruta actual requiere autenticación
if (in_array($page, $protected_routes) && !isset($_SESSION['usuario_id'])) {
    redirect('/index.php?page=login');
}

// Manejo de rutas
switch ($page) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->procesarLogin();
        } else {
            require_once __DIR__ . '/vista/login.php';
        }
        break;

    case 'registro':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->procesarRegistro();
        } else {
            require_once __DIR__ . '/vista/registro.php';
        }
        break;

    case 'panel_principal':
        require_once __DIR__ . '/vista/panel_principal.php';
        break;

    case 'registrar_mascota':
        require_once __DIR__ . '/vista/registrar_mascota.php';
        break;

    case 'logout':
        $authController->logout();
        break;

    default:
        // Redirigir a login por defecto
        redirect('/index.php?page=login');
        break;
}
