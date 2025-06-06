<?php
session_start();
require_once '../modelo/conexion.php';
require_once '../modelo/mascota.php';

class MascotaController {
    private $model;

    public function __construct() {
        $conexion = new Conexion();
        $this->model = new Mascota($conexion->getConexion());
    }

    public function create() {
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        $usuario_id = $_SESSION['user']['id'];
        $nombre = $_POST['nombre'];
        $tipo = $_POST['tipo'];
        $raza = $_POST['raza'];

        try {
            $result = $this->model->crear($usuario_id, $nombre, $tipo, $raza);
            echo json_encode(['success' => true, 'message' => 'Mascota creada exitosamente']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function list() {
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        $usuario_id = $_SESSION['user']['id'];
        try {
            $mascotas = $this->model->listarPorUsuario($usuario_id);
            echo json_encode($mascotas);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete() {
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            return;
        }

        $id = $_GET['id'];
        $usuario_id = $_SESSION['user']['id'];

        try {
            $this->model->eliminar($id, $usuario_id);
            echo json_encode(['success' => true, 'message' => 'Mascota eliminada exitosamente']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

// Router
$controller = new MascotaController();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'list':
        $controller->list();
        break;
    case 'delete':
        $controller->delete();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}
?>
