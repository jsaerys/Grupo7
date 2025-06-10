<?php
session_start();
require_once '../modelo/mascota.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../vista/login.php');
    exit;
}

$action = $_GET['action'] ?? '';
$model = new Mascota();

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $model->crear(
                    $_SESSION['user']['id'],
                    $_POST['nombre'],
                    $_POST['tipo'],
                    $_POST['raza']
                );
                $_SESSION['mensaje'] = 'Mascota agregada exitosamente';
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        header('Location: ../vista/cliente/panel.php?tab=mascotas');
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            try {
                $model->eliminar($_GET['id'], $_SESSION['user']['id']);
                $_SESSION['mensaje'] = 'Mascota eliminada exitosamente';
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        header('Location: ../vista/cliente/panel.php?tab=mascotas');
        break;

    default:
        header('Location: ../vista/cliente/panel.php?tab=mascotas');
}

exit;
?>
