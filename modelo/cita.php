<?php
require_once 'conexion.php';

class Cita {
    private $conn;
    private $table_name = "citas";

    public $id;
    public $fecha;
    public $hora;
    public $servicio;
    public $mascota_id;
    public $usuario_id;
    public $estado;
    public $notas;
    public $fecha_registro;

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    public function crear() {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                    (fecha, hora, servicio, mascota_id, usuario_id, estado, notas) 
                    VALUES (:fecha, :hora, :servicio, :mascota_id, :usuario_id, :estado, :notas)";
            
            $stmt = $this->conn->prepare($query);

            // Sanitizar inputs
            $this->fecha = htmlspecialchars(strip_tags($this->fecha));
            $this->hora = htmlspecialchars(strip_tags($this->hora));
            $this->servicio = htmlspecialchars(strip_tags($this->servicio));
            $this->mascota_id = htmlspecialchars(strip_tags($this->mascota_id));
            $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
            $this->estado = 'pendiente';
            $this->notas = htmlspecialchars(strip_tags($this->notas ?? ''));

            // Vincular parámetros
            $stmt->bindParam(":fecha", $this->fecha);
            $stmt->bindParam(":hora", $this->hora);
            $stmt->bindParam(":servicio", $this->servicio);
            $stmt->bindParam(":mascota_id", $this->mascota_id);
            $stmt->bindParam(":usuario_id", $this->usuario_id);
            $stmt->bindParam(":estado", $this->estado);
            $stmt->bindParam(":notas", $this->notas);

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
                        hora = :hora,
                        servicio = :servicio,
                        estado = :estado,
                        notas = :notas
                    WHERE id = :id AND usuario_id = :usuario_id";

            $stmt = $this->conn->prepare($query);

            // Sanitizar datos
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->fecha = htmlspecialchars(strip_tags($this->fecha));
            $this->hora = htmlspecialchars(strip_tags($this->hora));
            $this->servicio = htmlspecialchars(strip_tags($this->servicio));
            $this->estado = htmlspecialchars(strip_tags($this->estado));
            $this->notas = htmlspecialchars(strip_tags($this->notas ?? ''));
            $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));

            // Vincular parámetros
            $stmt->bindParam(":id", $this->id);
            $stmt->bindParam(":fecha", $this->fecha);
            $stmt->bindParam(":hora", $this->hora);
            $stmt->bindParam(":servicio", $this->servicio);
            $stmt->bindParam(":estado", $this->estado);
            $stmt->bindParam(":notas", $this->notas);
            $stmt->bindParam(":usuario_id", $this->usuario_id);

            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            throw new Exception("Error al actualizar la cita: " . $e->getMessage());
        }
    }

    public function listarPorMascota($mascota_id) {
        try {
            $query = "SELECT c.*, m.nombre as mascota_nombre 
                    FROM " . $this->table_name . " c
                    INNER JOIN mascotas m ON c.mascota_id = m.id
                    WHERE c.mascota_id = :mascota_id
                    ORDER BY c.fecha DESC, c.hora DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":mascota_id", $mascota_id);
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
                    INNER JOIN mascotas m ON c.mascota_id = m.id
                    WHERE c.usuario_id = :usuario_id
                    ORDER BY c.fecha DESC, c.hora DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":usuario_id", $usuario_id);
            $stmt->execute();

            return $stmt;
        } catch(PDOException $e) {
            throw new Exception("Error al listar citas por usuario: " . $e->getMessage());
        }
    }

    public function eliminar($id, $usuario_id) {
        try {
            $query = "DELETE FROM " . $this->table_name . " 
                     WHERE id = :id AND usuario_id = :usuario_id";
            $stmt = $this->conn->prepare($query);

            // Sanitizar datos
            $id = htmlspecialchars(strip_tags($id));
            $usuario_id = htmlspecialchars(strip_tags($usuario_id));

            // Vincular parámetros
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":usuario_id", $usuario_id);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            throw new Exception("Error al eliminar la cita: " . $e->getMessage());
        }
    }

    public function leerUno($id, $usuario_id) {
        try {
            $query = "SELECT c.*, m.nombre as mascota_nombre 
                     FROM " . $this->table_name . " c
                     INNER JOIN mascotas m ON c.mascota_id = m.id
                     WHERE c.id = :id AND c.usuario_id = :usuario_id 
                     LIMIT 0,1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":usuario_id", $usuario_id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al leer la cita: " . $e->getMessage());
        }
    }
}
?>
