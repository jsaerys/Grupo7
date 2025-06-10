<?php
require_once 'conexion.php';

class Producto extends Conexion {
    private $id;
    private $nombre;
    private $descripcion;
    private $precio;
    private $imagen;
    private $stock;
    private $categoria;
    private $conn;
    private $table = 'productos';

    public function __construct($db) {
        parent::__construct();
        $this->conn = $db;
    }

    public function obtenerTodos() {
        $query = "SELECT * FROM productos WHERE activo = 1";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM productos WHERE id = ? AND activo = 1";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                (nombre, descripcion, precio, stock, categoria, imagen, activo) 
                VALUES 
                (:nombre, :descripcion, :precio, :stock, :categoria, :imagen, 1)";

        try {
            $stmt = $this->conn->prepare($query);

            // Sanitizar datos
            $nombre = htmlspecialchars(strip_tags($data['nombre']));
            $descripcion = htmlspecialchars(strip_tags($data['descripcion']));
            $precio = floatval($data['precio']);
            $stock = intval($data['stock']);
            $categoria = htmlspecialchars(strip_tags($data['categoria']));
            $imagen = isset($data['imagen']) ? htmlspecialchars(strip_tags($data['imagen'])) : '';

            // Vincular valores
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":descripcion", $descripcion);
            $stmt->bindParam(":precio", $precio);
            $stmt->bindParam(":stock", $stock);
            $stmt->bindParam(":categoria", $categoria);
            $stmt->bindParam(":imagen", $imagen);

            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            throw new Exception("Error al crear el producto: " . $e->getMessage());
        }
    }

    public function update($data) {
        $setFields = [];
        $params = [];

        // Construir dinámicamente los campos a actualizar
        foreach(['nombre', 'descripcion', 'precio', 'stock', 'categoria', 'imagen'] as $field) {
            if(isset($data[$field]) && !empty($data[$field])) {
                $setFields[] = "$field = :$field";
                $params[$field] = htmlspecialchars(strip_tags($data[$field]));
            }
        }

        if(empty($setFields)) {
            return false;
        }

        $query = "UPDATE " . $this->table . " 
                SET " . implode(", ", $setFields) . "
                WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            
            // Vincular ID
            $params['id'] = $data['id'];
            
            // Vincular todos los parámetros
            foreach($params as $key => &$value) {
                if($key === 'precio') {
                    $value = floatval($value);
                } elseif($key === 'stock') {
                    $value = intval($value);
                }
                $stmt->bindParam(":$key", $value);
            }

            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            throw new Exception("Error al actualizar el producto: " . $e->getMessage());
        }
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al obtener el producto: " . $e->getMessage());
        }
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " WHERE activo = 1 ORDER BY id DESC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al obtener los productos: " . $e->getMessage());
        }
    }

    public function toggleStatus($id, $status) {
        $query = "UPDATE " . $this->table . " 
                SET activo = :status 
                WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":status", $status, PDO::PARAM_BOOL);
            $stmt->bindParam(":id", $id);

            if($stmt->execute()) {
                return true;
            }
            return false;
        } catch(PDOException $e) {
            throw new Exception("Error al cambiar el estado del producto: " . $e->getMessage());
        }
    }

    public function search($term) {
        $query = "SELECT * FROM " . $this->table . " 
                WHERE nombre LIKE :term 
                OR descripcion LIKE :term 
                OR categoria LIKE :term";

        try {
            $stmt = $this->conn->prepare($query);
            $searchTerm = "%{$term}%";
            $stmt->bindParam(":term", $searchTerm);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al buscar productos: " . $e->getMessage());
        }
    }
} 