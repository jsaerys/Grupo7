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

    public function crear($usuario_id, $nombre, $tipo, $raza) {
        $query = "INSERT INTO " . $this->table_name . " SET nombre=:nombre, especie=:tipo, raza=:raza, usuario_id=:usuario_id";
        $stmt = $this->conn->prepare($query);

        // Sanitizar inputs
        $nombre = htmlspecialchars(strip_tags($nombre));
        $tipo = htmlspecialchars(strip_tags($tipo));
        $raza = htmlspecialchars(strip_tags($raza));
        $usuario_id = htmlspecialchars(strip_tags($usuario_id));

        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":tipo", $tipo);
        $stmt->bindParam(":raza", $raza);
        $stmt->bindParam(":usuario_id", $usuario_id);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        throw new Exception("Error al crear la mascota");
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

    public function actualizar() {
        $query = "UPDATE " . $this->table_name . " SET nombre=:nombre, especie=:especie, raza=:raza, edad=:edad, id_cliente=:id_cliente WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->especie = htmlspecialchars(strip_tags($this->especie));
        $this->raza = htmlspecialchars(strip_tags($this->raza));
        $this->edad = htmlspecialchars(strip_tags($this->edad));
        $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":especie", $this->especie);
        $stmt->bindParam(":raza", $this->raza);
        $stmt->bindParam(":edad", $this->edad);
        $stmt->bindParam(":id_cliente", $this->id_cliente);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function eliminar($id, $usuario_id) {
        // Verificar que la mascota pertenezca al usuario
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->conn->prepare($query);

        // Sanitizar inputs
        $id = htmlspecialchars(strip_tags($id));
        $usuario_id = htmlspecialchars(strip_tags($usuario_id));

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":usuario_id", $usuario_id);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return true;
            }
            throw new Exception("No se encontró la mascota o no pertenece al usuario");
        }
        throw new Exception("Error al eliminar la mascota");
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
        $query = "SELECT id, nombre, especie as tipo, raza FROM " . $this->table_name . " WHERE usuario_id = :usuario_id ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        
        // Sanitizar input
        $usuario_id = htmlspecialchars(strip_tags($usuario_id));
        $stmt->bindParam(":usuario_id", $usuario_id);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
