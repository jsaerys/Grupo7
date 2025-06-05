<?php
class Conexion {
    private $host = 'localhost';
    private $db = 'veterinaria';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';
    protected $conexion;

    public function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
            $opciones = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->conexion = new PDO($dsn, $this->user, $this->pass, $opciones);
        } catch(PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
            exit;
        }
    }

    public function getConexion() {
        return $this->conexion;
    }
} 