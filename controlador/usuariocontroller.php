<?php
session_start();
require_once __DIR__ . '/../modelo/usuario.php';

class UsuarioController {
    private $modelo;

    public function __construct() {
        $this->modelo = new Usuario();
    }

    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Por favor, complete todos los campos.';
            header('Location: ../vista/login.php');
            exit();
        }

        $usuario = $this->modelo->login($email, $password);
        if ($usuario) {
            // Iniciar sesión
            $_SESSION['user'] = [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'email' => $usuario['email'],
                'tipo' => $usuario['rol'] // Guardamos el rol como tipo para consistencia
            ];
            $is_admin = (strtolower(trim($usuario['rol'])) === 'admin');
            $_SESSION['is_admin'] = $is_admin; // Establecer explícitamente is_admin en la sesión
            $_SESSION['username'] = $usuario['nombre']; // Necesario para la verificación en el menú
            $_SESSION['message'] = 'Inicio de sesión exitoso.';
            // --- DEBUG START ---
            error_log('User Role: ' . $usuario['rol']);
            error_log('Is Admin (after trim/lower): ' . (strtolower(trim($usuario['rol'])) === 'admin' ? 'true' : 'false'));
            // --- DEBUG END ---
            if ($is_admin) {
                header('Location: ../vista/admin/dashboard.php');
                exit();
            } else {
                header('Location: ../vista/cliente/panel.php');
                exit();
            }
            exit();
        }

        $_SESSION['error'] = 'Credenciales incorrectas.';
        header('Location: ../vista/login.php');
        exit();
    }

    public function registrar($datos) {
        // Validaciones
        if (empty($datos['nombre']) || empty($datos['email']) || 
            empty($datos['password']) || empty($datos['telefono'])) {
            $_SESSION['error'] = 'Por favor, complete todos los campos.';
            header('Location: ../vista/registro.php');
            exit();
        }

        if ($this->modelo->verificarEmail($datos['email'])) {
            $_SESSION['error'] = 'El email ya está registrado.';
            header('Location: ../vista/registro.php');
            exit();
        }

        if ($this->modelo->registrar($datos)) {
            $_SESSION['message'] = 'Usuario registrado correctamente. Por favor, inicia sesión.';
            header('Location: ../vista/login.php');
            exit();
        }

        $_SESSION['error'] = 'Error al registrar el usuario.';
        header('Location: ../vista/registro.php');
        exit();
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
        // Destruir todas las variables de sesión
        $_SESSION = array();
        
        // Si hay una cookie de sesión, destruirla también
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
        
        // Destruir la sesión
        session_destroy();
        
        // Iniciar una nueva sesión limpia para mensajes
        session_start();
        $_SESSION['message'] = 'Sesión cerrada correctamente.';
        
        // Redirigir al index
        header('Location: ../vista/index.php');
        exit();
    }

    public function listar() {
        try {
            $usuarios = $this->modelo->listarTodos();
            echo json_encode([
                'success' => true,
                'usuarios' => $usuarios
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function obtener($id) {
        try {
            $usuario = $this->modelo->obtenerPorId($id);
            if ($usuario) {
                echo json_encode([
                    'success' => true,
                    'usuario' => $usuario
                ]);
            } else {
                throw new Exception('Usuario no encontrado');
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function eliminar($id) {
        try {
            if ($this->modelo->eliminar($id)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario eliminado exitosamente'
                ]);
            } else {
                throw new Exception('Error al eliminar el usuario');
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}

$controller = new UsuarioController();

if (isset($_POST['action']) || isset($_GET['action'])) {
    $action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];
    switch ($action) {
        case 'login':
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $controller->login($email, $password);
            break;
        case 'logout':
            $controller->cerrarSesion();
            break;
        case 'register':
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'password' => $_POST['password'] ?? ''
            ];
            $controller->registrar($datos);
            break;
        case 'listar':
            $controller->listar();
            break;
        case 'get':
            $id = $_GET['id'] ?? null;
            if ($id) {
                $controller->obtener($id);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'ID de usuario no proporcionado'
                ]);
            }
            break;
        case 'actualizar':
            $datos = [
                'id' => $_POST['id'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telefono' => $_POST['telefono'] ?? ''
            ];
            echo json_encode($controller->actualizar($datos));
            break;
        case 'eliminar':
            $id = $_POST['id'] ?? null;
            if ($id) {
                $controller->eliminar($id);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'ID de usuario no proporcionado'
                ]);
            }
            break;
        default:
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Acción no válida'
            ]);
            break;
    }
} else {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'No se especificó ninguna acción'
    ]);
}