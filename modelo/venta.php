<?php
require_once 'conexion.php';

class Venta {
    private $conn;
    private $table_name = "ventas";

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    public function crear($venta) {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                    (usuario_id, productos, total, metodo_pago) 
                    VALUES (:usuario_id, :productos, :total, :metodo_pago)";
            
            $stmt = $this->conn->prepare($query);

            // Sanitizar inputs
            $usuario_id = htmlspecialchars(strip_tags($venta['usuario_id']));
            $productos = $venta['productos']; // No sanitizamos JSON string
            $total = floatval($venta['total']);
            $metodo_pago = htmlspecialchars(strip_tags($venta['metodo_pago']));

            // Vincular parámetros
            $stmt->bindParam(":usuario_id", $usuario_id);
            $stmt->bindParam(":productos", $productos);
            $stmt->bindParam(":total", $total);
            $stmt->bindParam(":metodo_pago", $metodo_pago);

            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            throw new Exception("Error al crear la venta: " . $e->getMessage());
        }
    }

    public function listarPorUsuario($usuario_id) {
        try {
            $query = "SELECT v.*, u.nombre as nombre_cliente 
                    FROM " . $this->table_name . " v
                    INNER JOIN usuarios u ON v.usuario_id = u.id
                    WHERE v.usuario_id = :usuario_id
                    ORDER BY v.fecha DESC";

        $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":usuario_id", $usuario_id);
            $stmt->execute();

            return $stmt;
        } catch(PDOException $e) {
            throw new Exception("Error al listar ventas: " . $e->getMessage());
        }
    }

    public function obtenerPorId($id) {
        try {
            $query = "SELECT v.*, u.nombre as nombre_cliente 
                    FROM " . $this->table_name . " v
                    INNER JOIN usuarios u ON v.usuario_id = u.id
                    WHERE v.id = :id
                    LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
        $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al obtener la venta: " . $e->getMessage());
        }
    }
}
?>
