<?php
session_start();
$page = 'contacto';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Guau</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        :root {
            --primary-color: #498f9d;
            --secondary-color: #e67e22;
        }
        .navbar {
            background-color: var(--primary-color) !important;
        }
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .text-primary {
            color: var(--primary-color) !important;
        }
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .logo-img {
            height: 40px;
            width: auto;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="../img/logo.png" alt="Logo" class="logo-img">
                <span class="h4 mb-0" style="color: white;">Guau</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="nosotros.php">Sobre Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contacto.php">Contacto</a>
                    </li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/dashboard.php">Panel Admin</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="controlador/usuariocontroller.php?action=logout">Cerrar Sesión</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Ingresar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-5">
        <div class="container">
            <h1 class="text-center mb-5">Contacto</h1>
            
            <div class="row g-4">
                <!-- Tarjeta de Teléfonos -->
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title mb-4">
                                <i class="bi bi-telephone-fill text-primary me-2"></i>Teléfonos
                            </h3>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3">
                                    <strong><i class="bi bi-telephone me-2"></i>Fijo:</strong>
                                    <a href="tel:6077541234" class="text-decoration-none ms-2">(607) 754-1234</a>
                                </li>
                                <li class="mb-3">
                                    <strong><i class="bi bi-phone me-2"></i>Celular:</strong>
                                    <a href="tel:3214567890" class="text-decoration-none ms-2">321 456 7890</a>
                                </li>
                                <li>
                                    <strong><i class="bi bi-whatsapp text-success me-2"></i>WhatsApp:</strong>
                                    <span class="ms-2">321 456 7890</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Correo Electrónico -->
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title mb-4">
                                <i class="bi bi-envelope-fill text-primary me-2"></i>Correo Electrónico
                            </h3>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3">
                                    <a href="mailto:contacto@guaupets.com" class="text-decoration-none">
                                        <i class="bi bi-envelope me-2"></i>contacto@guaupets.com
                                    </a>
                                </li>
                                <li>
                                    <a href="mailto:ventas@guaupets.com" class="text-decoration-none">
                                        <i class="bi bi-envelope me-2"></i>ventas@guaupets.com
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Horario -->
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title mb-4">
                                <i class="bi bi-clock-fill text-primary me-2"></i>Horario de Atención
                            </h3>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3">
                                    <strong><i class="bi bi-calendar-check me-2"></i>Lunes a Viernes:</strong><br>
                                    <span class="ms-4">8:00 AM - 6:00 PM</span>
                                </li>
                                <li class="mb-3">
                                    <strong><i class="bi bi-calendar-check me-2"></i>Sábados:</strong><br>
                                    <span class="ms-4">9:00 AM - 4:00 PM</span>
                                </li>
                                <li>
                                    <strong><i class="bi bi-calendar-check me-2"></i>Domingos y Festivos:</strong><br>
                                    <span class="ms-4">9:00 AM - 1:00 PM</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Redes Sociales -->
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title mb-4">
                                <i class="bi bi-globe text-primary me-2"></i>Redes Sociales
                            </h3>
                            <div class="d-flex gap-3">
                                <a href="#" class="btn btn-outline-primary" target="_blank" title="Facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-outline-danger" target="_blank" title="Instagram">
                                    <i class="bi bi-instagram"></i>
                                </a>
                                <button class="btn btn-outline-success" title="WhatsApp">
                                    <i class="bi bi-whatsapp"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-4 mt-5" style="background-color: var(--primary-color);">
        <div class="container text-center">
            <p class="mb-0 text-white">&copy; 2025 Guau. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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