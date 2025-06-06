<?php
session_start();
$page = 'contacto';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto - Guau</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo">🐾 Guau</a>
        <ul>
            <li><a href="index.php">Inicio</a></li>

            <li><a href="nosotros.php">Sobre Nosotros</a></li>
            <li><a href="contacto.php" class="active">Contacto</a></li>
            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
                    <li><a href="admin/index.php">Admin</a></li>
                <?php endif; ?>
                <li><a href="#" onclick="cerrarSesion()" class="login-btn">Cerrar Sesión</a></li>
            <?php else: ?>
                <li><a href="login.php" class="login-btn">Ingresar</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="contact-container">
        <div class="contact-info">
            <h2>Contáctanos</h2>
            
            <div class="contact-item">
                <h3>📍 Dirección</h3>
                <p>Calle 5 #3-45</p>
                <p>Puente Nacional, Santander</p>
                <p>Colombia</p>
            </div>

            <div class="contact-item">
                <h3>📞 Teléfonos</h3>
                <p>Fijo: (607) 754-1234</p>
                <p>Celular: 321 456 7890</p>
                <p>WhatsApp: 321 456 7890</p>
            </div>

            <div class="contact-item">
                <h3>✉️ Correo Electrónico</h3>
                <p>contacto@guaupets.com</p>
                <p>ventas@guaupets.com</p>
            </div>

            <div class="contact-item">
                <h3>🕒 Horario de Atención</h3>
                <p>Lunes a Viernes: 8:00 AM - 6:00 PM</p>
                <p>Sábados: 9:00 AM - 4:00 PM</p>
                <p>Domingos y Festivos: 9:00 AM - 1:00 PM</p>
            </div>

            <div class="contact-item">
                <h3>🌐 Redes Sociales</h3>
                <div class="social-links">
                    <a href="#" target="_blank">Facebook</a>
                    <a href="#" target="_blank">Instagram</a>
                    <a href="#" target="_blank">WhatsApp</a>
                </div>
            </div>
        </div>

        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15890.916985841654!2d-73.68333367771386!3d5.876666936913307!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e41e33938748af3%3A0x8098e08b3d1e6c0d!2sPuente%20Nacional%2C%20Santander!5e0!3m2!1ses!2sco!4v1709066119813!5m2!1ses!2sco" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>

    <footer class="footer">
        <span>&copy; 2025 Guau - Tienda de Mascotas. Todos los derechos reservados.</span>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function cerrarSesion() {
        Swal.fire({
            title: '¿Cerrar sesión?',
            text: '¿Estás seguro que deseas cerrar sesión?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ff934f',
            cancelButtonColor: '#357186',
            confirmButtonText: 'Sí, cerrar sesión',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../controlador/procesar_usuario.php?action=logout';
            }
        });
    }
    </script>
</body>
</html> 