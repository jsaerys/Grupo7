<?php
require_once __DIR__ . '/controlador/AuthController.php';

$authController = new AuthController();

$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        $authController->login();
        break;
    case 'registro':
        $authController->registro();
        break;
    case 'logout':
        $authController->logout();
        break;
    default:
        echo "Página no encontrada";
        break;
}
