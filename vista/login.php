<?php
session_start();

// Si ya está logueado, redirigir al index
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Guau</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/main.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo">🐾 Guau</a>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="productos.php">Productos</a></li>
            <li><a href="nosotros.php">Sobre Nosotros</a></li>
            <li><a href="contacto.php">Contacto</a></li>
            <li><a href="login.php" class="login-btn active">Ingresar</a></li>
        </ul>
    </nav>

    <div class="main-content-wrapper">
        <div class="login-container">
            <span class="logo">🐾 Guau</span>
            <form id="loginForm" method="POST" action="../controlador/procesar_usuario.php">
                <input type="hidden" name="action" value="login">
                <div>
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Iniciar Sesión</button>
                <p class="info">¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
            </form>
        </div>
    </div>

    <footer class="footer">
        <span>&copy; 2024 Guau - Tienda de Mascotas. Todos los derechos reservados.</span>
    </footer>

    <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('../controlador/procesar_usuario.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Bienvenido!',
                    text: 'Iniciando sesión...',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'index.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al iniciar sesión'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al procesar la solicitud'
            });
        });
    });
    </script>
</body>
</html> 