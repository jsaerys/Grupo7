<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso no autorizado']);
    exit;
}

require_once '../modelo/mascota.php';
require_once '../modelo/cliente.php';
require_once '../modelo/venta.php';
require_once '../modelo/producto.php';

$entity = $_GET['entity'] ?? '';
$action = $_GET['action'] ?? 'read';

try {
    $response = [];
    
    switch ($entity) {
        case 'mascotas':
            $mascota = new Mascota();
            $data = $mascota->leer();
            $response = [];
            while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
                $response[] = $row;
            }
            break;
            
        case 'clientes':
            $cliente = new Cliente();
            $data = $cliente->leer();
            $response = [];
            while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
                $response[] = $row;
            }
            break;
            
        case 'ventas':
            $venta = new Venta();
            $data = $venta->leer();
            $response = [];
            while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
                $response[] = $row;
            }
            break;
            
        case 'productos':
            $producto = new Producto();
            $data = $producto->leer();
            $response = [];
            while ($row = $data->fetch(PDO::FETCH_ASSOC)) {
                $response[] = $row;
            }
            break;
            
        default:
            throw new Exception('Entidad no válida');
    }
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
