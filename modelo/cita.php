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
    public $estado;

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConnection();
    }

    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                    (id_mascota, fecha, motivo, estado) 
                    VALUES (:id_mascota, :fecha, :motivo, :estado)";
            
            $stmt = $this->conn->prepare($query);

            // Sanitizar y validar datos
            $this->id_mascota = htmlspecialchars(strip_tags($this->id_mascota));
            $this->fecha = htmlspecialchars(strip_tags($this->fecha));
            $this->motivo = htmlspecialchars(strip_tags($this->motivo));
            $this->estado = htmlspecialchars(strip_tags($this->estado));

            // Vincular parámetros
            $stmt->bindParam(":id_mascota", $this->id_mascota);
            $stmt->bindParam(":fecha", $this->fecha);
            $stmt->bindParam(":motivo", $this->motivo);
            $stmt->bindParam(":estado", $this->estado);

            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            throw new Exception("Error al crear la cita: " . $e->getMessage());
        }
    }

    public function actualizar() {
        try {
            $query = "UPDATE " . $this->table_name . "
                    SET fecha = :fecha,
                        motivo = :motivo,
                        estado = :estado
                    WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            // Sanitizar datos
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->fecha = htmlspecialchars(strip_tags($this->fecha));
            $this->motivo = htmlspecialchars(strip_tags($this->motivo));
            $this->estado = htmlspecialchars(strip_tags($this->estado));

            // Vincular parámetros
            $stmt->bindParam(":id", $this->id);
            $stmt->bindParam(":fecha", $this->fecha);
            $stmt->bindParam(":motivo", $this->motivo);
            $stmt->bindParam(":estado", $this->estado);

            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            throw new Exception("Error al actualizar la cita: " . $e->getMessage());
        }
    }

    public function listarPorMascota($id_mascota) {
        try {
            $query = "SELECT c.*, m.nombre as mascota_nombre 
                    FROM " . $this->table_name . " c
                    INNER JOIN mascotas m ON c.id_mascota = m.id
                    WHERE c.id_mascota = :id_mascota
                    ORDER BY c.fecha DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_mascota", $id_mascota);
            $stmt->execute();

            return $stmt;
        } catch(PDOException $e) {
            throw new Exception("Error al listar citas por mascota: " . $e->getMessage());
        }
    }

    public function listarPorUsuario($usuario_id) {
        try {
            $query = "SELECT c.*, m.nombre as mascota_nombre 
                    FROM " . $this->table_name . " c
                    INNER JOIN mascotas m ON c.id_mascota = m.id
                    WHERE m.usuario_id = :usuario_id
                    ORDER BY c.fecha DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":usuario_id", $usuario_id);
            $stmt->execute();

            return $stmt;
        } catch(PDOException $e) {
            throw new Exception("Error al listar citas por usuario: " . $e->getMessage());
        }
    }

    public function leer() {
        try {
            $query = "SELECT id, id_mascota, fecha, hora, motivo, estado FROM " . $this->table_name . " ORDER BY id DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e) {
            throw new Exception("Error al leer citas: " . $e->getMessage());
        }
    }

    public function eliminar() {
        try {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);

            $this->id = htmlspecialchars(strip_tags($this->id));
            $stmt->bindParam(":id", $this->id);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            throw new Exception("Error al eliminar la cita: " . $e->getMessage());
        }
    }

    public function leerUno() {
        try {
            $query = "SELECT id, id_mascota, fecha, hora, motivo, estado FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $this->id_mascota = $row['id_mascota'];
                $this->fecha = $row['fecha'];
                $this->hora = $row['hora'];
                $this->motivo = $row['motivo'];
                $this->estado = $row['estado'];
                return true;
            }
            return false;
        } catch(PDOException $e) {
            throw new Exception("Error al leer la cita: " . $e->getMessage());
        }
    }
}
?>
