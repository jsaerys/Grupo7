<?php
session_start();

// Si ya hay una sesión activa, redirigir según el rol
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['tipo'] === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: cliente/panel.php');
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Guau - Ingreso</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        body {
            background-color: #f5f5f5;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .logo {
            font-size: 2rem;
            text-align: center;
            display: block;
            margin-bottom: 2rem;
            color: #498f9d;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        label {
            font-weight: 500;
            color: #666;
        }
        input {
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }
        button {
            background: #498f9d;
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background: #3a7a86;
        }
        .info {
            margin-top: 1.5rem;
            text-align: center;
        }
        .info a {
            color: #498f9d;
            text-decoration: none;
            transition: color 0.3s;
        }
        .info a:hover {
            color: #3a7a86;
        }
        .message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <span class="logo">🐾 Guau</span>
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="message">' . htmlspecialchars($_SESSION['message']) . '</div>';
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="error">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <form id="loginForm" action="../../controlador/usuariocontroller.php" method="POST">
            <input type="hidden" name="action" value="login">
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
            </button>
        </form>
        <div class="info">
            <a href="index.php" class="d-block mt-3">
                <i class="bi bi-house-door me-1"></i>Volver al inicio
            </a>
            <a href="registro.php" class="d-block mt-2">
                <i class="bi bi-person-plus me-1"></i>Registrarse
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module">
        import Swal from 'sweetalert2';
        
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            try {
                const formData = new FormData(e.target);
                const response = await fetch('../../controlador/usuariocontroller.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Bienvenido!',
                        text: 'Iniciando sesión...',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al iniciar sesión'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al conectar con el servidor'
                });
            }
        });
    </script>
</body>
</html>
