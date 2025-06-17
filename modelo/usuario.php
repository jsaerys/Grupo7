<?php
require_once 'conexion.php';

class Usuario extends Conexion {
    private $id;
    private $nombre;
    private $email;
    private $telefono;
    private $password;
    private $rol;

    public function __construct() {
        parent::__construct();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM usuarios WHERE email = ? AND activo = 1";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($password, $usuario['password'])) {
            return $usuario;
        }
        return false;
    }

    public function registrar($datos) {
        $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);
        
        $rol = $datos['rol'] ?? 'cliente'; // Default to 'cliente' if not provided
        
        $query = "INSERT INTO usuarios (nombre, email, telefono, password, rol) 
                 VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);
        return $stmt->execute([
            $datos['nombre'],
            $datos['email'],
            $datos['telefono'],
            $passwordHash,
            $rol
        ]);
    }

    public function obtenerPorId($id) {
        $query = "SELECT id, nombre, email, telefono, rol FROM usuarios WHERE id = ? AND activo = 1";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function actualizar($datos) {
        $query = "UPDATE usuarios SET nombre = ?, email = ?, telefono = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        return $stmt->execute([
            $datos['nombre'],
            $datos['email'],
            $datos['telefono'],
            $datos['id']
        ]);
    }

    public function cambiarPassword($id, $nuevaPassword) {
        $passwordHash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        $query = "UPDATE usuarios SET password = ? WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        return $stmt->execute([$passwordHash, $id]);
    }

    public function verificarEmail($email) {
        $query = "SELECT COUNT(*) FROM usuarios WHERE email = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function listarTodos() {
        try {
            $query = "SELECT id, nombre, email, telefono, rol, fecha_registro 
                     FROM usuarios 
                     WHERE activo = 1 AND rol = 'cliente' 
                     ORDER BY fecha_registro DESC";
            $stmt = $this->conexion->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            throw new Exception("Error al listar usuarios: " . $e->getMessage());
        }
    }

    public function eliminar($id) {
        try {
            // Soft delete - actualizar el campo activo a 0
            $query = "UPDATE usuarios SET activo = 0 WHERE id = ?";
            $stmt = $this->conexion->prepare($query);
            return $stmt->execute([$id]);
        } catch(PDOException $e) {
            throw new Exception("Error al eliminar usuario: " . $e->getMessage());
        }
    }
} 