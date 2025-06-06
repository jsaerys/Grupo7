<?php
require_once __DIR__ . '/../modelo/usuario.php';

class UsuarioController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Usuario();
    }

    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            return ['error' => 'Por favor, complete todos los campos'];
        }

        $usuario = $this->modelo->login($email, $password);
        if ($usuario) {
            // Iniciar sesión
            session_start();
            $_SESSION['user'] = [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'email' => $usuario['email'],
                'rol' => $usuario['rol']
            ];
            return ['success' => true];
        }

        return ['error' => 'Credenciales incorrectas'];
    }

    public function registrar($datos) {
        // Validaciones
        if (empty($datos['nombre']) || empty($datos['email']) || 
            empty($datos['password']) || empty($datos['telefono'])) {
            return ['error' => 'Por favor, complete todos los campos'];
        }

        if ($this->modelo->verificarEmail($datos['email'])) {
            return ['error' => 'El email ya está registrado'];
        }

        if ($this->modelo->registrar($datos)) {
            return ['success' => 'Usuario registrado correctamente'];
        }

        return ['error' => 'Error al registrar el usuario'];
    }

    public function actualizar($datos) {
        if (empty($datos['nombre']) || empty($datos['email']) || empty($datos['telefono'])) {
            return ['error' => 'Por favor, complete todos los campos'];
        }

        if ($this->modelo->actualizar($datos)) {
            return ['success' => 'Datos actualizados correctamente'];
        }

        return ['error' => 'Error al actualizar los datos'];
    }

    public function cambiarPassword($id, $passwordActual, $nuevaPassword) {
        $usuario = $this->modelo->obtenerPorId($id);
        if (!$usuario) {
            return ['error' => 'Usuario no encontrado'];
        }

        if (!password_verify($passwordActual, $usuario['password'])) {
            return ['error' => 'La contraseña actual es incorrecta'];
        }

        if ($this->modelo->cambiarPassword($id, $nuevaPassword)) {
            return ['success' => 'Contraseña actualizada correctamente'];
        }

        return ['error' => 'Error al actualizar la contraseña'];
    }

    public function cerrarSesion() {
        session_start();
        session_destroy();
        return ['success' => true];
    }
} 