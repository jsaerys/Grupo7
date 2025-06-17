<?php
// Configurar el tiempo de vida de la sesión (8 horas)
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Sesión expirada']);
        exit;
    } else {
        $_SESSION['error'] = 'Por favor inicie sesión para continuar.';
        header('Location: /vista/login.php');
    exit;
}
}

require_once __DIR__ . '/../modelo/mascota.php';

$action = $_GET['action'] ?? '';
$model = new Mascota();

switch ($action) {
    case 'listarPorUsuario':
        try {
            $stmt = $model->listarPorUsuario($_SESSION['user']['id']);
            $mascotas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $mascotas[] = [
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'especie' => $row['especie'],
                    'raza' => $row['raza']
                ];
            }
            echo json_encode(['success' => true, 'mascotas' => $mascotas]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (empty($_POST['nombre']) || empty($_POST['especie']) || empty($_POST['raza'])) {
                    throw new Exception('Todos los campos son requeridos');
                }

                $id = $model->crear(
                    $_SESSION['user']['id'],
                    $_POST['nombre'],
                    $_POST['especie'],
                    $_POST['raza']
                );

                echo json_encode([
                    'success' => true,
                    'message' => 'Mascota agregada exitosamente',
                    'mascota' => [
                        'id' => $id,
                        'nombre' => $_POST['nombre'],
                        'especie' => $_POST['especie'],
                        'raza' => $_POST['raza']
                    ]
                ]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }
        break;

    case 'eliminar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            try {
                $model->eliminar($_POST['id'], $_SESSION['user']['id']);
                echo json_encode([
                    'success' => true,
                    'message' => 'Mascota eliminada exitosamente'
                ]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'ID de mascota no proporcionado'
            ]);
        }
        break;

    case 'get':
        try {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if (!$id) {
                throw new Exception('ID de mascota no proporcionado');
            }
            
            $stmt = $model->obtenerPorId($id);
            $mascota = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($mascota) {
                echo json_encode([
                    'success' => true,
                    'mascota' => $mascota
                ]);
            } else {
                throw new Exception('Mascota no encontrada');
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        break;

    case 'actualizar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (empty($_POST['id']) || empty($_POST['nombre']) || empty($_POST['especie']) || empty($_POST['raza'])) {
                    throw new Exception('Todos los campos son requeridos');
                }

                // Obtener la mascota actual para mantener el usuario_id
                $stmt = $model->obtenerPorId($_POST['id']);
                $mascota = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$mascota) {
                    throw new Exception('Mascota no encontrada');
                }

                $model->actualizar(
                    $_POST['id'],
                    $_POST['nombre'],
                    $_POST['especie'],
                    $_POST['raza']
                );

                echo json_encode([
                    'success' => true,
                    'message' => 'Mascota actualizada exitosamente'
                ]);
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }
        break;

    case 'listar':
        try {
            $stmt = $model->listarTodas();
            $mascotas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $mascotas[] = [
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'especie' => $row['especie'],
                    'raza' => $row['raza'],
                    'dueno' => $row['dueno']
                ];
            }
            echo json_encode(['success' => true, 'mascotas' => $mascotas]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Acción no encontrada']);
        break;
}
?>
