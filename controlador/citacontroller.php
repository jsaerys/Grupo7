<?php
require_once '../modelo/cita.php';

class CitaController {
    private $cita;

    public function __construct() {
        $this->cita = new Cita();
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar datos requeridos
                if (empty($_POST['id_mascota']) || empty($_POST['fecha']) || empty($_POST['motivo'])) {
                    throw new Exception('Todos los campos son requeridos');
                }

                // Asignar valores
                $this->cita->id_mascota = $_POST['id_mascota'];
                $this->cita->fecha = $_POST['fecha'];
                $this->cita->motivo = $_POST['motivo'];
                $this->cita->estado = 'pendiente';

                // Crear la cita
                if ($this->cita->crear()) {
                    http_response_code(200);
                    echo json_encode(['message' => 'Cita creada con éxito']);
                } else {
                    throw new Exception('Error al crear la cita');
                }
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
        }
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (empty($_POST['id'])) {
                    throw new Exception('ID de cita requerido');
                }

                $this->cita->id = $_POST['id'];
                $this->cita->estado = $_POST['estado'] ?? 'pendiente';
                
                if (isset($_POST['fecha'])) $this->cita->fecha = $_POST['fecha'];
                if (isset($_POST['motivo'])) $this->cita->motivo = $_POST['motivo'];

                if ($this->cita->actualizar()) {
                    http_response_code(200);
                    echo json_encode(['message' => 'Cita actualizada con éxito']);
                } else {
                    throw new Exception('Error al actualizar la cita');
                }
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
        }
    }

    public function cancelar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (empty($_POST['id'])) {
                    throw new Exception('ID de cita requerido');
                }

                $this->cita->id = $_POST['id'];
                $this->cita->estado = 'cancelada';

                if ($this->cita->actualizar()) {
                    http_response_code(200);
                    echo json_encode(['message' => 'Cita cancelada con éxito']);
                } else {
                    throw new Exception('Error al cancelar la cita');
                }
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(['error' => $e->getMessage()]);
            }
        }
    }

    public function listarPorMascota($id_mascota) {
        try {
            if (empty($id_mascota)) {
                throw new Exception('ID de mascota no proporcionado');
            }
            $stmt = $this->cita->listarPorMascota($id_mascota);
            $citas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $citas[] = $row;
            }
            return ['success' => true, 'data' => $citas];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function listarPorUsuario($usuario_id) {
        try {
            if (empty($usuario_id)) {
                throw new Exception('ID de usuario no proporcionado');
            }
            $stmt = $this->cita->listarPorUsuario($usuario_id);
            $citas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $citas[] = $row;
            }
            return ['success' => true, 'data' => $citas];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

// Manejo de solicitudes AJAX
if (isset($_GET['action'])) {
    $controller = new CitaController();
    
    switch ($_GET['action']) {
        case 'crear':
            $controller->crear();
            break;
        case 'actualizar':
            $controller->actualizar();
            break;
        case 'cancelar':
            $controller->cancelar();
            break;
        case 'listarPorMascota':
            $resultado = $controller->listarPorMascota($_GET['id_mascota'] ?? '');
            if ($resultado['success']) {
                http_response_code(200);
            } else {
                http_response_code(400);
            }
            echo json_encode($resultado);
            break;
        case 'listarPorUsuario':
            $resultado = $controller->listarPorUsuario($_GET['usuario_id'] ?? '');
            if ($resultado['success']) {
                http_response_code(200);
            } else {
                http_response_code(400);
            }
            echo json_encode($resultado);
            break;
    }
}
?>
