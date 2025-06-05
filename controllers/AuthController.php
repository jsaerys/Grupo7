<?php
class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            if (empty($email) || empty($password)) {
                $this->render('auth/login', [
                    'error' => 'Por favor, complete todos los campos.',
                    'email' => $email
                ]);
                return;
            }

            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['user_role'] = $user['rol'];

                $this->redirect('home');
            } else {
                $this->render('auth/login', [
                    'error' => 'Credenciales inválidas.',
                    'email' => $email
                ]);
            }
        } else {
            $this->render('auth/login', ['pageTitle' => 'Iniciar Sesión']);
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            $errors = [];

            if (empty($nombre) || empty($email) || empty($telefono) || empty($password)) {
                $errors[] = 'Por favor, complete todos los campos.';
            }

            if ($password !== $confirm_password) {
                $errors[] = 'Las contraseñas no coinciden.';
            }

            if (strlen($password) < 6) {
                $errors[] = 'La contraseña debe tener al menos 6 caracteres.';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'El correo electrónico no es válido.';
            }

            if ($this->userModel->emailExists($email)) {
                $errors[] = 'El correo electrónico ya está registrado.';
            }

            if (!empty($errors)) {
                $this->render('auth/register', [
                    'errors' => $errors,
                    'old' => [
                        'nombre' => $nombre,
                        'email' => $email,
                        'telefono' => $telefono
                    ]
                ]);
                return;
            }

            $userData = [
                'nombre' => $nombre,
                'email' => $email,
                'telefono' => $telefono,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'rol' => 'cliente'
            ];

            if ($this->userModel->create($userData)) {
                $_SESSION['success'] = 'Registro exitoso. Por favor, inicie sesión.';
                $this->redirect('auth', ['action' => 'login']);
            } else {
                $this->render('auth/register', [
                    'error' => 'Error al crear la cuenta. Por favor, intente nuevamente.',
                    'old' => [
                        'nombre' => $nombre,
                        'email' => $email,
                        'telefono' => $telefono
                    ]
                ]);
            }
        } else {
            $this->render('auth/register', ['pageTitle' => 'Registro']);
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('auth', ['action' => 'login']);
    }

    public function profile() {
        $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $userData = [
                'nombre' => $_POST['nombre'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'password' => $_POST['password'] ?? '' // Solo se actualizará si no está vacío
            ];

            if ($this->userModel->update($userId, $userData)) {
                $_SESSION['user_name'] = $userData['nombre'];
                $data['success'] = 'Perfil actualizado correctamente';
            } else {
                $data['error'] = 'Error al actualizar el perfil';
            }
        }

        $data['user'] = $this->userModel->findById($_SESSION['user_id']);
        $this->render('auth/profile', $data);
    }
} 