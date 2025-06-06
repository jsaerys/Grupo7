<?php
require_once 'conexion.php';

class Venta {
    private $conn;
    private $table_name = "ventas";

    public $id;
    public $id_producto;
    public $id_cliente;
    public $cantidad;
    public $fecha;

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConnection();
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " SET id_producto=:id_producto, id_cliente=:id_cliente, cantidad=:cantidad, fecha=:fecha";
        $stmt = $this->conn->prepare($query);

        $this->id_producto = htmlspecialchars(strip_tags($this->id_producto));
        $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->fecha = htmlspecialchars(strip_tags($this->fecha));

        $stmt->bindParam(":id_producto", $this->id_producto);
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":fecha", $this->fecha);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function leer() {
        $query = "SELECT id, id_producto, id_cliente, cantidad, fecha FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function actualizar() {
        $query = "UPDATE " . $this->table_name . " SET id_producto=:id_producto, id_cliente=:id_cliente, cantidad=:cantidad, fecha=:fecha WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id_producto = htmlspecialchars(strip_tags($this->id_producto));
        $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->fecha = htmlspecialchars(strip_tags($this->fecha));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id_producto", $this->id_producto);
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":fecha", $this->fecha);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function eliminar() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function leerUno() {
        $query = "SELECT id, id_producto, id_cliente, cantidad, fecha FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id_producto = $row['id_producto'];
            $this->id_cliente = $row['id_cliente'];
            $this->cantidad = $row['cantidad'];
            $this->fecha = $row['fecha'];
            return true;
        }
        return false;
    }
}
?>
