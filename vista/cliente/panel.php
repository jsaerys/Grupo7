<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

// Incluir la conexión a la base de datos
require_once '../../modelo/conexion.php';
$conexion = new Conexion();
$conn = $conexion->getConexion();

// Obtener información del usuario
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Guau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary-color: #498f9d;
            --secondary-color: #e67e22;
        }
        .navbar {
            background-color: var(--primary-color) !important;
        }
        .content-section {
            padding: 2rem;
        }
        .content-section.hidden {
            display: none;
        }
        .card {
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .pet-card, .appointment-card, .product-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
        }
        .form-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .form-card.hidden {
            display: none;
        }
        .add-button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .add-button:hover {
            background-color: #3a7a86;
            transform: translateY(-2px);
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .shop-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 2rem;
        }
        #cart {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 2rem;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        .cart-total {
            font-weight: bold;
            margin-top: 1rem;
            text-align: right;
        }
        @media (max-width: 768px) {
            .shop-layout {
                grid-template-columns: 1fr;
            }
            #cart {
                position: static;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center">
                <img src="recursos/logo.png" alt="Logo" class="logo-img" style="height: 40px;">
                <span class="h4 mb-0 ms-2">Guau</span>
            </a>
            <div class="d-flex">
                <a href="../../controlador/logout.php" class="btn btn-danger">
                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <div class="row mb-4">
            <div class="container mt-4">
                <h1 class="mb-4">Bienvenido, <?php echo htmlspecialchars($_SESSION['user']['nombre']); ?></h1>

                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php 
                        echo htmlspecialchars($_SESSION['mensaje']); 
                        unset($_SESSION['mensaje']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php 
                        echo htmlspecialchars($_SESSION['error']); 
                        unset($_SESSION['error']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <ul class="nav nav-tabs mb-4" id="myTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" 
                                id="mascotas-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#mascotas" 
                                type="button" 
                                role="tab" 
                                aria-controls="mascotas" 
                                aria-selected="true">Mis Mascotas</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" 
                                id="citas-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#citas" 
                                type="button" 
                                role="tab" 
                                aria-controls="citas" 
                                aria-selected="false">Citas</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" 
                                id="tienda-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#tienda" 
                                type="button" 
                                role="tab" 
                                aria-controls="tienda" 
                                aria-selected="false">Tienda</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabsContent">
                    <div class="tab-pane fade show active" 
                         id="mascotas" 
                         role="tabpanel" 
                         aria-labelledby="mascotas-tab">
                        <?php include 'mascotas.php'; ?>
                    </div>
                    <div class="tab-pane fade" 
                         id="citas" 
                         role="tabpanel" 
                         aria-labelledby="citas-tab">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3>Mis Citas</h3>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#citaModal">
                                <i class="bi bi-calendar-plus me-2"></i>Agendar Cita
                            </button>
                        </div>
                        <div id="appointment-list"></div>
                    </div>
                    <div class="tab-pane fade" 
                         id="tienda" 
                         role="tabpanel" 
                         aria-labelledby="tienda-tab">
                        <div id="product-list"></div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </main>

    <footer class="py-4 mt-5" style="background-color: var(--primary-color);">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="mb-3">Guau - Tienda de Mascotas</h5>
                    <p>Cuidando a tus mascotas con amor y profesionalismo</p>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Horario de Atención</h5>
                    <p>Lunes a Sábado: 9:00 AM - 7:00 PM<br>Domingo: 10:00 AM - 4:00 PM</p>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Contacto</h5>
                    <p><i class="bi bi-telephone-fill me-2"></i>+51 123 456 789<br>
                    <i class="bi bi-envelope-fill me-2"></i>contacto@guau.com</p>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <p class="mb-0">&copy; 2025 Guau. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Pasar el ID del usuario a JavaScript
        const userId = <?php echo json_encode($_SESSION['user']['id']); ?>;
        $(document).ready(function() {
            // Activar la pestaña según la URL
            let hash = window.location.hash;
            if (hash) {
                $('.nav-tabs a[href="' + hash + '"]').tab('show');
            }

            // Actualizar URL al cambiar de pestaña
            $('.nav-tabs a').on('click', function (e) {
                $(this).tab('show');
                let scrollmem = $('body').scrollTop();
                window.location.hash = this.hash;
                $('html,body').scrollTop(scrollmem);
            });
        });
    </script>
    <script src="script.js"></script>

<!-- Modal para agendar cita -->
<div class="modal fade" id="citaModal" tabindex="-1" aria-labelledby="citaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="citaModalLabel">Agendar Nueva Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="citaForm">
                    <div class="mb-3">
                        <label for="mascota-select" class="form-label">Mascota</label>
                        <select class="form-select" id="mascota-select" required>
                            <option value="">Seleccione una mascota</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="servicio-select" class="form-label">Servicio</label>
                        <select class="form-select" id="servicio-select" required>
                            <option value="">Seleccione un servicio</option>
                            <option value="lavado">Lavado de mascota</option>
                            <option value="guarderia">Guardería</option>
                            <option value="valoracion">Valoración</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha-input" class="form-label">Fecha y Hora</label>
                        <input type="datetime-local" class="form-control" id="fecha-input" required>
                    </div>
                    <div class="mb-3">
                        <label for="notas-input" class="form-label">Notas Adicionales</label>
                        <textarea class="form-control" id="notas-input" rows="3" 
                                placeholder="Ej: La mascota debe bañarse con cuidado, tiene piel sensible..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="agendarCita()">Agendar Cita</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
