<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros - Guau</title>
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
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .logo-img {
            height: 40px;
            width: auto;
            margin-right: 10px;
        }
        .team-member img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 1rem;
        }
        .mission-section {
            background-color: #f8f9fa;
            border-radius: 15px;
            padding: 3rem 0;
            margin: 2rem 0;
        }
        .value-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
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
                        <a class="nav-link active" href="nosotros.php">Sobre Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contacto.php">Contacto</a>
                    </li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/dashboard.php">Panel Admin</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../controlador/usuariocontroller.php?action=logout">Cerrar Sesión</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Iniciar Sesión</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <!-- Historia y Misión -->
        <section class="text-center mb-5">
            <h1 class="display-4 mb-4">Nuestra Historia</h1>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <p class="lead">Desde 2020, Guau ha sido más que una tienda de mascotas - somos una familia dedicada al bienestar de tus compañeros peludos.</p>
                </div>
            </div>
        </section>

        <!-- Misión y Visión -->
        <section class="mission-section">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100 p-4">
                            <h3 class="text-primary"><i class="bi bi-bullseye me-2"></i>Nuestra Misión</h3>
                            <p>Proporcionar productos y servicios de la más alta calidad para mascotas, asegurando su bienestar y felicidad, mientras construimos relaciones duraderas con nuestros clientes y sus compañeros peludos.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 p-4">
                            <h3 class="text-primary"><i class="bi bi-eye me-2"></i>Nuestra Visión</h3>
                            <p>Ser reconocidos como el referente número uno en cuidado y atención de mascotas, innovando constantemente en productos y servicios que mejoren la vida de las mascotas y sus familias.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Valores -->
        <section class="py-5">
            <h2 class="text-center mb-5">Nuestros Valores</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card text-center h-100 p-4">
                        <div class="value-icon">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                        <h4>Amor por los Animales</h4>
                        <p>Cada decisión que tomamos está guiada por nuestro amor incondicional hacia las mascotas.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center h-100 p-4">
                        <div class="value-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Calidad Garantizada</h4>
                        <p>Solo ofrecemos productos y servicios que cumplan con los más altos estándares de calidad.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center h-100 p-4">
                        <div class="value-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h4>Servicio Personalizado</h4>
                        <p>Cada mascota es única, y nuestro servicio se adapta a sus necesidades específicas.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Equipo -->
        <section class="py-5">
            <h2 class="text-center mb-5">Nuestro Equipo</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="card text-center p-4">
                        <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=150&h=150&q=80" alt="Ana Martínez" class="mx-auto">
                        <h4>Ana Martínez</h4>
                        <p class="text-muted">Fundadora & Veterinaria</p>
                        <p>Especialista en nutrición animal con más de 10 años de experiencia.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center p-4">
                        <img src="https://images.unsplash.com/photo-1607990281513-2c110a25bd8c?auto=format&fit=crop&w=150&h=150&q=80" alt="Carlos Ruiz" class="mx-auto">
                        <h4>Carlos Ruiz</h4>
                        <p class="text-muted">Entrenador Principal</p>
                        <p>Certificado en comportamiento animal y técnicas de entrenamiento positivo.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center p-4">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&h=150&q=80" alt="Laura Torres" class="mx-auto">
                        <h4>Laura Torres</h4>
                        <p class="text-muted">Coordinadora de Adopciones</p>
                        <p>Dedicada a encontrar hogares perfectos para nuestros amigos peludos.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="py-4 mt-5" style="background-color: var(--primary-color);">
        <div class="container text-center">
            <p class="mb-0 text-white">&copy; 2025 Guau. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
