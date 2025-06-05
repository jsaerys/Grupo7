<?php
require_once 'usuariocontroller.php';

header('Content-Type: application/json');

if (!isset($_POST['action'])) {
    echo json_encode(['error' => 'Acción no especificada']);
    exit;
}

$controller = new UsuarioController();
$action = $_POST['action'];

switch ($action) {
    case 'login':
        if (!isset($_POST['email']) || !isset($_POST['password'])) {
            echo json_encode(['error' => 'Datos incompletos']);
            exit;
        }
        $resultado = $controller->login($_POST['email'], $_POST['password']);
        echo json_encode($resultado);
        break;

    case 'register':
        $datos = [
            'nombre' => $_POST['nombre'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'telefono' => $_POST['telefono'] ?? ''
        ];
        $resultado = $controller->registrar($datos);
        echo json_encode($resultado);
        break;

    case 'update':
        if (!isset($_SESSION['user'])) {
            echo json_encode(['error' => 'Usuario no autenticado']);
            exit;
        }
        $datos = [
            'id' => $_SESSION['user']['id'],
            'nombre' => $_POST['nombre'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telefono' => $_POST['telefono'] ?? ''
        ];
        $resultado = $controller->actualizar($datos);
        echo json_encode($resultado);
        break;

    case 'change_password':
        if (!isset($_SESSION['user'])) {
            echo json_encode(['error' => 'Usuario no autenticado']);
            exit;
        }
        $resultado = $controller->cambiarPassword(
            $_SESSION['user']['id'],
            $_POST['password_actual'] ?? '',
            $_POST['password_nueva'] ?? ''
        );
        echo json_encode($resultado);
        break;

    case 'logout':
        $resultado = $controller->cerrarSesion();
        echo json_encode($resultado);
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
} 