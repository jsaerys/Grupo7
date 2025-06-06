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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styles.css">
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
            <a class="navbar-brand d-flex align-items-center" href="../index.php">
                <img src="recursos/logo.png" alt="Logo" class="logo-img" style="height: 40px;">
                <span class="h4 mb-0 ms-2">Guau</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../nosotros.php">Sobre Nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../contacto.php">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../controlador/usuariocontroller.php?action=logout">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <div class="row mb-4">
            <div class="col">
                <h2>Bienvenido, <?php echo htmlspecialchars($user['nombre']); ?></h2>
            </div>
        </div>

        <div class="nav nav-tabs mb-4">
            <button class="nav-link active" data-bs-toggle="tab" data-section="pets">Mis Mascotas</button>
            <button class="nav-link" data-bs-toggle="tab" data-section="appointments">Citas</button>
            <button class="nav-link" data-bs-toggle="tab" data-section="shop">Tienda</button>
        </div>

        <div class="tab-content">
            <section id="pets-section" class="content-section active">
                <div class="section-header">
                    <h3>Mis Mascotas</h3>
                    <button id="show-add-pet-form" class="add-button">
                        <i class="bi bi-plus-circle me-2"></i>Agregar Mascota
                    </button>
                </div>
                <form id="add-pet-form" class="hidden form-card">
                    <h4>Registrar Nueva Mascota</h4>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="pet-name" placeholder="Nombre de la mascota" required>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" id="pet-type" required>
                            <option value="" disabled selected>Tipo de mascota</option>
                            <option value="dog">Perro</option>
                            <option value="cat">Gato</option>
                            <option value="bird">Pájaro</option>
                            <option value="other">Otro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="pet-breed" placeholder="Raza (opcional)">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" id="cancel-add-pet" class="btn btn-secondary">Cancelar</button>
                    </div>
                </form>
                <div id="pet-list" class="card-container">
                    <p class="text-muted">Aún no tienes mascotas registradas. ¡Agrega una!</p>
                </div>
            </section>

            <section id="appointments-section" class="content-section hidden">
                <div class="section-header">
                    <h3>Citas Programadas</h3>
                    <button id="show-add-appointment-form" class="add-button">
                        <i class="bi bi-plus-circle me-2"></i>Agendar Cita
                    </button>
                </div>
                <form id="add-appointment-form" class="hidden form-card">
                    <h4>Agendar Nueva Cita</h4>
                    <div class="mb-3">
                        <select class="form-select" id="appointment-pet-select" required>
                            <option value="" disabled selected>Selecciona una mascota</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" id="appointment-service" required>
                            <option value="" disabled selected>Tipo de servicio</option>
                            <option value="Peluquería">Peluquería</option>
                            <option value="Consulta Veterinaria">Consulta Veterinaria</option>
                            <option value="Vacunación">Vacunación</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="datetime-local" class="form-control" id="appointment-date" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Agendar</button>
                        <button type="button" id="cancel-add-appointment" class="btn btn-secondary">Cancelar</button>
                    </div>
                </form>
                <div id="appointment-list" class="card-container">
                    <p class="text-muted">No tienes citas programadas.</p>
                </div>
            </section>

            <section id="shop-section" class="content-section hidden">
                <div class="section-header">
                    <h3>Tienda de Artículos</h3>
                </div>
                <div class="shop-layout">
                    <div id="product-grid" class="card-container"></div>
                    <aside id="cart">
                        <h4>Carrito de Compras</h4>
                        <div id="cart-items">
                            <p class="text-muted">Tu carrito está vacío.</p>
                        </div>
                        <p class="cart-total">Total: <span id="cart-total-amount">$0.00</span></p>
                        <button id="checkout-btn" class="btn btn-primary w-100" disabled>Pagar</button>
                    </aside>
                </div>
            </section>
        </div>
    </main>

    <footer class="py-4 mt-5" style="background-color: var(--primary-color);">
        <div class="container text-center">
            <p class="mb-0 text-white">&copy; 2025 Guau. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
