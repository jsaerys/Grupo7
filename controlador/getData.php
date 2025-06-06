<?php
require_once '../config/conexion.php';

class DataProvider {
    private $conn;

    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->getConexion();
    }

    public function getMascotas() {
        $query = "SELECT id, nombre, especie, raza FROM mascotas";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClientes() {
        $query = "SELECT id, nombre, email, telefono FROM clientes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductos() {
        $query = "SELECT id, nombre, precio, stock FROM productos";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Acceso no autorizado']);
    exit;
}

$dataProvider = new DataProvider();
$tipo = $_GET['tipo'] ?? '';

header('Content-Type: application/json');

try {
    switch($tipo) {
        case 'mascotas':
            echo json_encode($dataProvider->getMascotas());
            break;
        case 'clientes':
            echo json_encode($dataProvider->getClientes());
            break;
        case 'productos':
            echo json_encode($dataProvider->getProductos());
            break;
        default:
            throw new Exception('Tipo de datos no válido');
    }
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
?>
