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

    public function __construct() {
        parent::__construct();
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

    public function crear($datos) {
        $query = "INSERT INTO productos (nombre, descripcion, precio, imagen, stock, categoria) 
                 VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);
        return $stmt->execute([
            $datos['nombre'],
            $datos['descripcion'],
            $datos['precio'],
            $datos['imagen'],
            $datos['stock'],
            $datos['categoria']
        ]);
    }

    public function actualizar($datos) {
        $query = "UPDATE productos SET 
                 nombre = ?, 
                 descripcion = ?, 
                 precio = ?, 
                 stock = ?, 
                 categoria = ?";
        
        $params = [
            $datos['nombre'],
            $datos['descripcion'],
            $datos['precio'],
            $datos['stock'],
            $datos['categoria']
        ];

        if (!empty($datos['imagen'])) {
            $query .= ", imagen = ?";
            $params[] = $datos['imagen'];
        }

        $query .= " WHERE id = ?";
        $params[] = $datos['id'];

        $stmt = $this->conexion->prepare($query);
        return $stmt->execute($params);
    }

    public function eliminar($id) {
        $query = "UPDATE productos SET activo = 0 WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        return $stmt->execute([$id]);
    }

    public function buscar($termino) {
        $query = "SELECT * FROM productos 
                 WHERE activo = 1 
                 AND (nombre LIKE ? OR descripcion LIKE ? OR categoria LIKE ?)";
        $termino = "%$termino%";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute([$termino, $termino, $termino]);
        return $stmt->fetchAll();
    }
} 