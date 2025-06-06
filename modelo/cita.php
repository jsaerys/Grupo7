<?php
require_once 'conexion.php';

class Cita {
    private $conn;
    private $table_name = "citas";

    public $id;
    public $id_mascota;
    public $fecha;
    public $hora;
    public $motivo;

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConnection();
    }

    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " SET id_mascota=:id_mascota, fecha=:fecha, hora=:hora, motivo=:motivo";
        $stmt = $this->conn->prepare($query);

        $this->id_mascota = htmlspecialchars(strip_tags($this->id_mascota));
        $this->fecha = htmlspecialchars(strip_tags($this->fecha));
        $this->hora = htmlspecialchars(strip_tags($this->hora));
        $this->motivo = htmlspecialchars(strip_tags($this->motivo));

        $stmt->bindParam(":id_mascota", $this->id_mascota);
        $stmt->bindParam(":fecha", $this->fecha);
        $stmt->bindParam(":hora", $this->hora);
        $stmt->bindParam(":motivo", $this->motivo);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function leer() {
        $query = "SELECT id, id_mascota, fecha, hora, motivo FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function actualizar() {
        $query = "UPDATE " . $this->table_name . " SET id_mascota=:id_mascota, fecha=:fecha, hora=:hora, motivo=:motivo WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->id_mascota = htmlspecialchars(strip_tags($this->id_mascota));
        $this->fecha = htmlspecialchars(strip_tags($this->fecha));
        $this->hora = htmlspecialchars(strip_tags($this->hora));
        $this->motivo = htmlspecialchars(strip_tags($this->motivo));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id_mascota", $this->id_mascota);
        $stmt->bindParam(":fecha", $this->fecha);
        $stmt->bindParam(":hora", $this->hora);
        $stmt->bindParam(":motivo", $this->motivo);
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
        $query = "SELECT id, id_mascota, fecha, hora, motivo FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id_mascota = $row['id_mascota'];
            $this->fecha = $row['fecha'];
            $this->hora = $row['hora'];
            $this->motivo = $row['motivo'];
            return true;
        }
        return false;
    }
}
?>
