<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit;
}

// Determinar qué sección mostrar
$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'mascotas';

// Incluir la conexión a la base de datos
require_once '../../modelo/conexion.php';
$conexion = new Conexion();
$conn = $conexion->getConexion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Guau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styleDashboard.css">
</head>
<body>
    <div id="dashboard-container">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><i class="bi bi-speedometer2 me-2"></i>Panel Admin</h3>
                <div class="mt-2">
                    <a href="../../controlador/usuariocontroller.php?action=logout" class="btn btn-danger btn-sm w-100"><i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión</a>
                </div>
            </div>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="?seccion=mascotas" class="nav-link <?php echo $seccion === 'mascotas' ? 'active' : 'text-white'; ?>">
                        <i class="bi bi-hearts me-2"></i> Mascotas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?seccion=clientes" class="nav-link <?php echo $seccion === 'clientes' ? 'active' : 'text-white'; ?>">
                        <i class="bi bi-people-fill me-2"></i> Clientes
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?seccion=ventas" class="nav-link <?php echo $seccion === 'ventas' ? 'active' : 'text-white'; ?>">
                        <i class="bi bi-cash-coin me-2"></i> Ventas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?seccion=citas" class="nav-link <?php echo $seccion === 'citas' ? 'active' : 'text-white'; ?>">
                        <i class="bi bi-calendar-check me-2"></i> Citas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?seccion=productos" class="nav-link <?php echo $seccion === 'productos' ? 'active' : 'text-white'; ?>">
                        <i class="bi bi-box-seam me-2"></i> Productos
                    </a>
                </li>
            </ul>
        </nav>

        <div id="content">
            <?php if ($seccion === 'mascotas'): ?>
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Gestión de Mascotas</h2>
                    <button class="btn btn-primary" id="add-new-btn"><i class="bi bi-plus-circle me-2"></i>Añadir Nueva Mascota</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Especie</th>
                                <th>Raza</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT id, nombre, especie, raza FROM mascotas";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['especie']); ?></td>
                                <td><?php echo htmlspecialchars($row['raza']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $row['id']; ?>"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="<?php echo $row['id']; ?>"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php elseif ($seccion === 'clientes'): ?>
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Gestión de Clientes</h2>
                    <button class="btn btn-primary" id="add-new-btn"><i class="bi bi-plus-circle me-2"></i>Añadir Nuevo Cliente</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once '../../modelo/usuario.php';
                            $usuarioModel = new Usuario();
                            $query = "SELECT id, nombre, email, telefono FROM usuarios WHERE rol = 'cliente' AND activo = 1";
                            
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $row['id']; ?>"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="<?php echo $row['id']; ?>"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php elseif ($seccion === 'productos'): ?>
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Gestión de Productos</h2>
                    <button class="btn btn-primary" id="add-new-btn"><i class="bi bi-plus-circle me-2"></i>Añadir Nuevo Producto</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT id, nombre, precio, stock FROM productos";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td>$<?php echo htmlspecialchars($row['precio']); ?></td>
                                <td><?php echo htmlspecialchars($row['stock']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $row['id']; ?>"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="<?php echo $row['id']; ?>"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
        </div>
    </div>

    <!-- Modal for Add/Edit -->
    <div class="modal fade" id="form-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal-title"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="modal-form">
                        <!-- Form fields will be dynamically added here -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" form="modal-form" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
