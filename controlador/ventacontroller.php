<?php
session_start();
require_once '../modelo/venta.php';

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$action = $_GET['action'] ?? '';
$model = new Venta();

switch ($action) {
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validar datos requeridos
                if (empty($_POST['productos']) || empty($_POST['total']) || empty($_POST['metodo_pago'])) {
                    throw new Exception("Todos los campos son requeridos");
                }

                // Validar que el carrito no esté vacío
                $productos = json_decode($_POST['productos'], true);
                if (empty($productos)) {
                    throw new Exception("El carrito está vacío");
                }

                // Validar el total
                $total = floatval($_POST['total']);
                if ($total <= 0) {
                    throw new Exception("El total debe ser mayor a 0");
                }

                // Crear la venta
                $venta = [
                    'usuario_id' => $_SESSION['user']['id'],
                    'productos' => $_POST['productos'], // JSON string de productos
                    'total' => $total,
                    'metodo_pago' => $_POST['metodo_pago']
                ];

                if ($model->crear($venta)) {
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Venta registrada exitosamente'
                    ]);
                } else {
                    throw new Exception("Error al registrar la venta");
                }
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode([
                    'success' => false, 
                    'message' => $e->getMessage()
                ]);
            }
        }
        break;

    default:
        http_response_code(404);
        echo json_encode([
            'success' => false, 
            'message' => 'Acción no encontrada'
        ]);
        break;
}
?> 