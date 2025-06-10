<?php
require_once '../modelo/Cita.php';
require_once '../config/database.php';

class CitaController {
    private $cita;
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->cita = new Cita($this->conn);
    }

    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');
        
        switch ($action) {
            case 'create':
                $this->createCita();
                break;
            case 'update':
                $this->updateCita();
                break;
            case 'delete':
                $this->deleteCita();
                break;
            case 'get':
                $this->getCita();
                break;
            case 'listar':
                $this->listarCitas();
                break;
            case 'listarPorUsuario':
                $this->listarCitasPorUsuario();
                break;
            default:
                $this->sendResponse(false, 'Acción no válida');
        }
    }

    private function createCita() {
        try {
            $data = $this->getCitaData();
            
            if ($this->cita->create($data)) {
                $this->sendResponse(true, 'Cita creada exitosamente');
            } else {
                $this->sendResponse(false, 'Error al crear la cita');
            }
        } catch (Exception $e) {
            $this->sendResponse(false, $e->getMessage());
        }
    }

    private function updateCita() {
        try {
            $data = $this->getCitaData();
            
            if ($this->cita->update($data)) {
                $this->sendResponse(true, 'Cita actualizada exitosamente');
            } else {
                $this->sendResponse(false, 'Error al actualizar la cita');
            }
        } catch (Exception $e) {
            $this->sendResponse(false, $e->getMessage());
        }
    }

    private function deleteCita() {
        try {
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            if (!$id) {
                throw new Exception('ID de cita no proporcionado');
            }
            
            if ($this->cita->delete($id)) {
                $this->sendResponse(true, 'Cita eliminada exitosamente');
            } else {
                $this->sendResponse(false, 'Error al eliminar la cita');
            }
        } catch (Exception $e) {
            $this->sendResponse(false, $e->getMessage());
        }
    }

    private function getCita() {
        try {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception('ID de cita no proporcionado');
            }
            
            $cita = $this->cita->getById($id);
            if ($cita) {
                $this->sendResponse(true, 'Cita encontrada', ['cita' => $cita]);
            } else {
                $this->sendResponse(false, 'Cita no encontrada');
            }
        } catch (Exception $e) {
            $this->sendResponse(false, $e->getMessage());
        }
    }

    private function listarCitas() {
        try {
            $citas = $this->cita->getAll();
            $this->sendResponse(true, 'Citas obtenidas exitosamente', ['citas' => $citas]);
        } catch (Exception $e) {
            $this->sendResponse(false, $e->getMessage());
        }
    }

    private function listarCitasPorUsuario() {
        try {
            if (!isset($_SESSION)) {
                session_start();
            }
            
            if (!isset($_SESSION['user'])) {
                throw new Exception('Usuario no autenticado');
            }

            $usuario_id = $_SESSION['user']['id'];
            $citas = $this->cita->listarPorUsuario($usuario_id);
            
            if ($citas === false) {
                throw new Exception('Error al obtener las citas');
            }
            
            $this->sendResponse(true, 'Citas obtenidas exitosamente', ['citas' => $citas]);
        } catch (Exception $e) {
            $this->sendResponse(false, $e->getMessage());
        }
    }

    private function getCitaData() {
        // Separar la fecha y hora del datetime-local
        $fechaHora = isset($_POST['fecha_hora']) ? $_POST['fecha_hora'] : null;
        if ($fechaHora) {
            $datetime = new DateTime($fechaHora);
            $fecha = $datetime->format('Y-m-d');
            $hora = $datetime->format('H:i:s');
        } else {
            throw new Exception("La fecha y hora son requeridas");
        }

        $data = [
            'fecha' => $fecha,
            'hora' => $hora,
            'servicio' => $_POST['servicio'] ?? null,
            'mascota_id' => $_POST['mascota_id'] ?? null,
            'usuario_id' => $_POST['usuario_id'] ?? null,
            'notas' => $_POST['notas'] ?? ''
        ];

        if (isset($_POST['id'])) {
            $data['id'] = $_POST['id'];
        }

        // Validar datos requeridos
        foreach (['fecha', 'hora', 'servicio', 'mascota_id', 'usuario_id'] as $campo) {
            if (empty($data[$campo])) {
                throw new Exception("El campo {$campo} es requerido");
            }
        }

        return $data;
    }

    private function sendResponse($success, $message, $data = []) {
        header('Content-Type: application/json');
        echo json_encode(array_merge(
            ['success' => $success, 'message' => $message],
            $data
        ));
        exit;
    }
}

// Iniciar el controlador
$controller = new CitaController();
$controller->handleRequest();
?>
