<?php
require_once 'conexion.php';

class Cita {
    private $conn;
    private $table = 'citas';

    public $id;
    public $fecha;
    public $hora;
    public $servicio;
    public $mascota_id;
    public $usuario_id;
    public $estado;
    public $notas;
    public $fecha_registro;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                (fecha, hora, servicio, mascota_id, usuario_id, notas) 
                VALUES 
                (:fecha, :hora, :servicio, :mascota_id, :usuario_id, :notas)";
            
        try {
            $stmt = $this->conn->prepare($query);

            // Vincular valores
            $stmt->bindParam(":fecha", $data['fecha']);
            $stmt->bindParam(":hora", $data['hora']);
            $stmt->bindParam(":servicio", $data['servicio']);
            $stmt->bindParam(":mascota_id", $data['mascota_id']);
            $stmt->bindParam(":usuario_id", $data['usuario_id']);
            $stmt->bindParam(":notas", $data['notas']);

            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            throw new Exception("Error al crear la cita: " . $e->getMessage());
        }
    }

    public function update($data) {
        $query = "UPDATE " . $this->table . " 
                    SET fecha = :fecha,
                        hora = :hora,
                        servicio = :servicio,
                    mascota_id = :mascota_id,
                    usuario_id = :usuario_id,
                        notas = :notas
                WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);

            // Vincular valores
            $stmt->bindParam(":fecha", $data['fecha']);
            $stmt->bindParam(":hora", $data['hora']);
            $stmt->bindParam(":servicio", $data['servicio']);
            $stmt->bindParam(":mascota_id", $data['mascota_id']);
            $stmt->bindParam(":usuario_id", $data['usuario_id']);
            $stmt->bindParam(":notas", $data['notas']);
            $stmt->bindParam(":id", $data['id']);

            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            throw new Exception("Error al actualizar la cita: " . $e->getMessage());
        }
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);

            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            throw new Exception("Error al eliminar la cita: " . $e->getMessage());
        }
    }

    public function getById($id) {
        $query = "SELECT c.*, m.nombre as mascota_nombre, u.nombre as usuario_nombre
                FROM " . $this->table . " c
                INNER JOIN mascotas m ON c.mascota_id = m.id
                INNER JOIN usuarios u ON c.usuario_id = u.id
                WHERE c.id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al obtener la cita: " . $e->getMessage());
        }
    }

    public function getAll() {
        $query = "SELECT c.*, m.nombre as mascota_nombre, u.nombre as usuario_nombre
                FROM " . $this->table . " c
                INNER JOIN mascotas m ON c.mascota_id = m.id
                INNER JOIN usuarios u ON c.usuario_id = u.id
                ORDER BY c.fecha DESC, c.hora DESC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al obtener las citas: " . $e->getMessage());
        }
    }

    public function listarPorMascota($mascota_id) {
        try {
            $query = "SELECT c.*, m.nombre as mascota_nombre 
                    FROM " . $this->table . " c
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
                    FROM " . $this->table . " c
                    INNER JOIN mascotas m ON c.mascota_id = m.id
                    WHERE c.usuario_id = :usuario_id
                    ORDER BY c.fecha DESC, c.hora DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":usuario_id", $usuario_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al listar citas por usuario: " . $e->getMessage());
        }
    }

    public function eliminar($id, $usuario_id) {
        try {
            $query = "DELETE FROM " . $this->table . " 
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
                     FROM " . $this->table . " c
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
