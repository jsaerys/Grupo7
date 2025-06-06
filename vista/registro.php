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
    <title>Registrarse - Guau</title>
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
            <li><a href="login.php" class="login-btn">Ingresar</a></li>
        </ul>
    </nav>

    <div class="main-content-wrapper">
        <div class="login-container">
            <span class="logo">🐾 Guau</span>
            <form id="registerForm" method="POST" action="../controlador/procesar_usuario.php">
                <input type="hidden" name="action" value="register">
                <div>
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required autocomplete="name">
                </div>
                <div>
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required autocomplete="email">
                </div>
                <div>
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password">
                </div>
                <div>
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" required autocomplete="tel">
                </div>
                <button type="submit">Registrarse</button>
                <p class="info">¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            </form>
        </div>
    </div>

    <footer class="footer">
        <span>&copy; 2024 Guau - Tienda de Mascotas. Todos los derechos reservados.</span>
    </footer>

    <script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
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
                    title: '¡Registro Exitoso!',
                    text: 'Ahora puedes iniciar sesión.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'login.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al registrar usuario'
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
