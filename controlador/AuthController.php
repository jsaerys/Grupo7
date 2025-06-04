<?php
require_once __DIR__ . '/../modelo/Usuario.php';
require_once __DIR__ . '/../configuracion/config.php';

class AuthController {
    private $conexion;

    public function __construct() {
        $this->conexion = conectarDB();
    }

    public function procesarLogin() {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        if (!$email || !$password) {
            $error = "Por favor, complete todos los campos";
            include __DIR__ . '/../vista/login.php';
            return;
        }

        $stmt = $this->conexion->prepare("SELECT id, nombre, password FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($usuario = $resultado->fetch_assoc()) {
            if (password_verify($password, $usuario['password'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                redirect('/index.php?page=panel_principal');
            }
        }

        $error = "Credenciales incorrectas";
        include __DIR__ . '/../vista/login.php';
    }

    public function procesarRegistro() {
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validaciones
        if (!$nombre || !$email || !$telefono || !$password || !$confirm_password) {
            $error = "Por favor, complete todos los campos";
            include __DIR__ . '/../vista/registro.php';
            return;
        }

        if ($password !== $confirm_password) {
            $error = "Las contraseñas no coinciden";
            include __DIR__ . '/../vista/registro.php';
            return;
        }

        // Verificar si el email ya existe
        $stmt = $this->conexion->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Este correo electrónico ya está registrado";
            include __DIR__ . '/../vista/registro.php';
            return;
        }

        // Hash de la contraseña
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insertar nuevo usuario
        $stmt = $this->conexion->prepare("INSERT INTO usuarios (nombre, email, telefono, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $email, $telefono, $password_hash);

        if ($stmt->execute()) {
            $_SESSION['usuario_id'] = $stmt->insert_id;
            $_SESSION['usuario_nombre'] = $nombre;
            redirect('/index.php?page=panel_principal');
        } else {
            $error = "Error al registrar el usuario";
            include __DIR__ . '/../vista/registro.php';
        }
    }

    public function logout() {
        session_destroy();
        redirect('/index.php?page=login');
    }
}
