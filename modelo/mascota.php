<?php
require_once 'conexion.php';

class Mascota {
    private $conn;
    private $table_name = "mascotas";

    public $id;
    public $nombre;
    public $especie;
    public $raza;
    public $edad;
    public $id_cliente;

    public function __construct() {
        $database = new Conexion();
        $this->conn = $database->getConexion();
    }

    public function crear($usuario_id, $nombre, $especie, $raza) {
        try {
            $query = "INSERT INTO " . $this->table_name . " 
                    (usuario_id, nombre, especie, raza) 
                    VALUES (:usuario_id, :nombre, :especie, :raza)";
            
            $stmt = $this->conn->prepare($query);

            // Sanitizar inputs
            $usuario_id = htmlspecialchars(strip_tags($usuario_id));
            $nombre = htmlspecialchars(strip_tags($nombre));
            $especie = htmlspecialchars(strip_tags($especie));
            $raza = htmlspecialchars(strip_tags($raza));

            // Vincular parámetros
            $stmt->bindParam(":usuario_id", $usuario_id);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":especie", $especie);
            $stmt->bindParam(":raza", $raza);

            if($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            throw new Exception("Error al crear la mascota");
        } catch(PDOException $e) {
            throw new Exception("Error al crear la mascota: " . $e->getMessage());
        }
    }

    public function leer() {
        $query = "SELECT m.id, m.nombre, m.especie, m.raza, m.edad, u.nombre as nombre_dueno 
                 FROM " . $this->table_name . " m 
                 INNER JOIN usuarios u ON m.usuario_id = u.id 
                 ORDER BY m.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function actualizar($id, $nombre, $especie, $raza) {
        try {
            $query = "UPDATE " . $this->table_name . " 
                     SET nombre = :nombre, especie = :especie, raza = :raza 
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);

            // Sanitizar inputs
            $id = htmlspecialchars(strip_tags($id));
            $nombre = htmlspecialchars(strip_tags($nombre));
            $especie = htmlspecialchars(strip_tags($especie));
            $raza = htmlspecialchars(strip_tags($raza));

            // Vincular parámetros
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":especie", $especie);
            $stmt->bindParam(":raza", $raza);

            if($stmt->execute()) {
                return true;
            }
            throw new Exception("Error al actualizar la mascota");
        } catch(PDOException $e) {
            throw new Exception("Error al actualizar la mascota: " . $e->getMessage());
        }
    }

    public function eliminar($id, $usuario_id) {
        try {
            $query = "DELETE FROM " . $this->table_name . " 
                     WHERE id = :id AND usuario_id = :usuario_id";
            
            $stmt = $this->conn->prepare($query);

            // Sanitizar inputs
            $id = htmlspecialchars(strip_tags($id));
            $usuario_id = htmlspecialchars(strip_tags($usuario_id));

            // Vincular parámetros
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":usuario_id", $usuario_id);

            if($stmt->execute()) {
                return true;
            }
            throw new Exception("Error al eliminar la mascota");
        } catch(PDOException $e) {
            throw new Exception("Error al eliminar la mascota: " . $e->getMessage());
        }
    }

    public function leerUno($id, $usuario_id) {
        $query = "SELECT id, nombre, especie as tipo, raza, usuario_id FROM " . $this->table_name . " WHERE id = :id AND usuario_id = :usuario_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar inputs
        $id = htmlspecialchars(strip_tags($id));
        $usuario_id = htmlspecialchars(strip_tags($usuario_id));

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row;
        }
        throw new Exception("No se encontró la mascota o no pertenece al usuario");
    }

    public function listarPorUsuario($usuario_id) {
        try {
            $query = "SELECT id, nombre, especie, raza 
                     FROM " . $this->table_name . " 
                     WHERE usuario_id = :usuario_id 
                     ORDER BY nombre ASC";
            
            $stmt = $this->conn->prepare($query);
            
            // Sanitizar input
            $usuario_id = htmlspecialchars(strip_tags($usuario_id));
            
            // Vincular parámetro
            $stmt->bindParam(":usuario_id", $usuario_id);
            $stmt->execute();

            return $stmt;
        } catch(PDOException $e) {
            throw new Exception("Error al listar mascotas: " . $e->getMessage());
        }
    }

    public function obtenerPorId($id) {
        try {
            $query = "SELECT m.*, u.nombre as dueno 
                     FROM " . $this->table_name . " m 
                     LEFT JOIN usuarios u ON m.usuario_id = u.id 
                     WHERE m.id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            // Sanitizar input
            $id = htmlspecialchars(strip_tags($id));
            
            // Vincular parámetro
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            return $stmt;
        } catch(PDOException $e) {
            throw new Exception("Error al obtener la mascota: " . $e->getMessage());
        }
    }

    public function listarTodas() {
        try {
            $query = "SELECT m.*, u.nombre as dueno 
                     FROM " . $this->table_name . " m 
                     LEFT JOIN usuarios u ON m.usuario_id = u.id 
                     ORDER BY m.id DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt;
        } catch(PDOException $e) {
            throw new Exception("Error al listar todas las mascotas: " . $e->getMessage());
        }
    }
}
?>
