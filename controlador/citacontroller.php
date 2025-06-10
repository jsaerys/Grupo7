<?php
session_start();
require_once '../modelo/cita.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../vista/login.php');
    exit;
}

$action = $_GET['action'] ?? '';
$model = new Cita();

switch ($action) {
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar datos requeridos
                if (empty($_POST['fecha']) || empty($_POST['hora']) || 
                    empty($_POST['servicio']) || empty($_POST['mascota_id'])) {
                    throw new Exception("Todos los campos son requeridos");
                }

                // Validar que la fecha no sea en el pasado
                $fecha = new DateTime($_POST['fecha']);
                $hoy = new DateTime();
                if ($fecha < $hoy) {
                    throw new Exception("La fecha no puede ser en el pasado");
                }

                $model->fecha = $_POST['fecha'];
                $model->hora = $_POST['hora'];
                $model->servicio = $_POST['servicio'];
                $model->mascota_id = $_POST['mascota_id'];
                $model->usuario_id = $_SESSION['user']['id'];
                $model->notas = $_POST['notas'] ?? null;

                if ($model->crear()) {
                    $_SESSION['mensaje'] = "Cita agendada exitosamente";
                    echo json_encode(['success' => true, 'message' => 'Cita agendada exitosamente']);
                } else {
                    throw new Exception("Error al agendar la cita");
                }
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;

    case 'listar':
        try {
            $citas = $model->listarPorUsuario($_SESSION['user']['id']);
            $result = [];
            while ($row = $citas->fetch(PDO::FETCH_ASSOC)) {
                $result[] = [
                    'id' => $row['id'],
                    'fecha' => $row['fecha'],
                    'hora' => $row['hora'],
                    'servicio' => $row['servicio'],
                    'mascota_nombre' => $row['mascota_nombre'],
                    'estado' => $row['estado'] ?? 'pendiente',
                    'notas' => $row['notas']
                ];
            }
            echo json_encode(['success' => true, 'citas' => $result]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'eliminar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            try {
                if ($model->eliminar($_POST['id'], $_SESSION['user']['id'])) {
                    echo json_encode(['success' => true, 'message' => 'Cita eliminada exitosamente']);
                } else {
                    throw new Exception("Error al eliminar la cita");
                }
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;

    case 'actualizar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            try {
                // Validar datos requeridos
                if (empty($_POST['fecha']) || empty($_POST['hora']) || empty($_POST['servicio'])) {
                    throw new Exception("Todos los campos son requeridos");
                }

                // Validar que la fecha no sea en el pasado
                $fecha = new DateTime($_POST['fecha']);
                $hoy = new DateTime();
                if ($fecha < $hoy) {
                    throw new Exception("La fecha no puede ser en el pasado");
                }

                $model->id = $_POST['id'];
                $model->fecha = $_POST['fecha'];
                $model->hora = $_POST['hora'];
                $model->servicio = $_POST['servicio'];
                $model->notas = $_POST['notas'] ?? null;
                $model->usuario_id = $_SESSION['user']['id'];

                if ($model->actualizar()) {
                    echo json_encode(['success' => true, 'message' => 'Cita actualizada exitosamente']);
                } else {
                    throw new Exception("Error al actualizar la cita");
                }
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Acción no encontrada']);
        break;
}
?>
