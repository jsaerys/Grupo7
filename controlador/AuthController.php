<?php
require_once __DIR__ . '/../modelo/Usuario.php';

class AuthController {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario();
            $auth = $usuario->validar($_POST['email'], $_POST['password']);

            if ($auth) {
                session_start();
                $_SESSION['usuario'] = $auth;
                
                // Redirigir al panel principal (fuera de index.php)
                header('Location: ../vista/panel_principal.php');
                exit;
            } else {
                $error = "Credenciales inválidas";
                require_once __DIR__ . '/../vista/login.php';
            }
        } else {
            require_once __DIR__ . '/../vista/login.php';
        }
    }

    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirmar = $_POST['confirmar_password'];

            if ($password !== $confirmar) {
                $error = "Las contraseñas no coinciden";
                require_once __DIR__ . '/../vista/registro.php';
                return;
            }

            $usuario = new Usuario();
            $existe = $usuario->existeEmail($email);

            if ($existe) {
                $error = "El correo ya está registrado";
                require_once __DIR__ . '/../vista/registro.php';
                return;
            }

            $registrado = $usuario->crear($nombre, $email, $password);

            if ($registrado) {
                header('Location: index.php?page=login');
                exit;
            } else {
                $error = "Error al registrar usuario";
                require_once __DIR__ . '/../vista/registro.php';
            }
        } else {
            require_once __DIR__ . '/../vista/registro.php';
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
