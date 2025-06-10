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
                <p class="text-center text-muted mt-5">&copy; 2025 Guau - Panel Administrativo</p>
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
                                <th>Dueño</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT m.id, m.nombre, m.especie, m.raza, u.nombre as nombre_dueno 
                                      FROM mascotas m 
                                      INNER JOIN usuarios u ON m.usuario_id = u.id";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['especie']); ?></td>
                                <td><?php echo htmlspecialchars($row['raza']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_dueno']); ?></td>
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

            <?php elseif ($seccion === 'ventas'): ?>
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Gestión de Ventas</h2>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Productos</th>
                                <th>Total</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Método de Pago</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT v.id, u.nombre as cliente, v.productos, v.total, 
                                      v.fecha, v.estado, v.metodo_pago 
                                      FROM ventas v 
                                      INNER JOIN usuarios u ON v.usuario_id = u.id 
                                      ORDER BY v.fecha DESC";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['cliente']); ?></td>
                                <td><?php echo htmlspecialchars($row['productos']); ?></td>
                                <td>$<?php echo number_format($row['total'], 2); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['fecha'])); ?></td>
                                <td>
                                    <span class="badge <?php echo $row['estado'] === 'completada' ? 'bg-success' : 
                                        ($row['estado'] === 'pendiente' ? 'bg-warning' : 'bg-danger'); ?>">
                                        <?php echo ucfirst($row['estado']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($row['metodo_pago'] ?? '-'); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $row['id']; ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if ($stmt->rowCount() === 0): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No hay ventas registradas
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php elseif ($seccion === 'citas'): ?>
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Gestión de Citas</h2>
                    <button class="btn btn-primary" id="add-cita-btn">
                        <i class="bi bi-plus-circle me-2"></i>Agregar Nueva Cita
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Servicio</th>
                                <th>Mascota</th>
                                <th>Usuario</th>
                                <th>Notas</th>
                                <th>Fecha Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once __DIR__ . '/../../config/database.php';
                            $database = new Database();
                            $conn = $database->getConnection();

                            $query = "SELECT c.*, m.nombre as mascota_nombre, u.nombre as usuario_nombre 
                                      FROM citas c 
                                    INNER JOIN mascotas m ON c.mascota_id = m.id
                                    INNER JOIN usuarios u ON c.usuario_id = u.id
                                    ORDER BY c.fecha DESC, c.hora DESC";
                            
                            $stmt = $conn->prepare($query);
                            $stmt->execute();

                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['fecha'])); ?></td>
                                <td><?php echo date('H:i', strtotime($row['hora'])); ?></td>
                                <td><?php echo htmlspecialchars($row['servicio']); ?></td>
                                <td><?php echo htmlspecialchars($row['mascota_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['usuario_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['notas'] ?: '...'); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_registro'])); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-cita" data-id="<?php echo $row['id']; ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger delete-cita" data-id="<?php echo $row['id']; ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if ($stmt->rowCount() === 0): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                                    No hay citas registradas
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal para Citas -->
            <div class="modal fade" id="citaModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="citaModalLabel">Agregar Cita</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form id="citaForm">
                            <div class="modal-body">
                                <input type="hidden" id="cita_id" name="id">
                                
                                <div class="mb-3">
                                    <label for="mascota_id" class="form-label">Mascota</label>
                                    <select class="form-select" id="mascota_id" name="mascota_id" required>
                                        <option value="">Seleccione una mascota</option>
                                        <?php
                                        $query = "SELECT m.id, m.nombre, u.nombre as dueno 
                                                FROM mascotas m 
                                                INNER JOIN usuarios u ON m.usuario_id = u.id 
                                                WHERE m.activo = 1";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        while($mascota = $stmt->fetch(PDO::FETCH_ASSOC)):
                                        ?>
                                        <option value="<?php echo $mascota['id']; ?>">
                                            <?php echo htmlspecialchars($mascota['nombre'] . ' - ' . $mascota['dueno']); ?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="servicio" class="form-label">Servicio</label>
                                    <select class="form-select" id="servicio" name="servicio" required>
                                        <option value="">Seleccione un servicio</option>
                                        <option value="consulta">Consulta General</option>
                                        <option value="vacunacion">Vacunación</option>
                                        <option value="peluqueria">Peluquería</option>
                                        <option value="desparasitacion">Desparasitación</option>
                                        <option value="cirugia">Cirugía</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="fecha_hora" class="form-label">Fecha y Hora</label>
                                    <input type="datetime-local" class="form-control" id="fecha_hora" name="fecha_hora" required>
                                </div>

                                <div class="mb-3">
                                    <label for="usuario_id" class="form-label">Usuario Asignado</label>
                                    <select class="form-select" id="usuario_id" name="usuario_id" required>
                                        <option value="">Seleccione un usuario</option>
                                        <?php
                                        $query = "SELECT id, nombre FROM usuarios WHERE activo = 1";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        while($usuario = $stmt->fetch(PDO::FETCH_ASSOC)):
                                        ?>
                                        <option value="<?php echo $usuario['id']; ?>">
                                            <?php echo htmlspecialchars($usuario['nombre']); ?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="notas" class="form-label">Notas</label>
                                    <textarea class="form-control" id="notas" name="notas" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const citaModal = new bootstrap.Modal(document.getElementById('citaModal'));
                const citaForm = document.getElementById('citaForm');

                // Agregar nueva cita
                document.getElementById('add-cita-btn')?.addEventListener('click', function() {
                    citaForm.reset();
                    document.getElementById('citaModalLabel').textContent = 'Agregar Cita';
                    citaModal.show();
                });

                // Editar cita
                document.querySelectorAll('.edit-cita').forEach(button => {
                    button.addEventListener('click', async function() {
                        const id = this.dataset.id;
                        try {
                            const response = await fetch(`../../controlador/citacontroller.php?action=get&id=${id}`);
                            const data = await response.json();
                            
                            if (data.success) {
                                document.getElementById('cita_id').value = data.cita.id;
                                document.getElementById('mascota_id').value = data.cita.mascota_id;
                                document.getElementById('servicio').value = data.cita.servicio;
                                document.getElementById('usuario_id').value = data.cita.usuario_id;
                                document.getElementById('notas').value = data.cita.notas || '';
                                
                                // Combinar fecha y hora para el input datetime-local
                                const fecha = data.cita.fecha;
                                const hora = data.cita.hora;
                                document.getElementById('fecha_hora').value = `${fecha}T${hora}`;
                                
                                document.getElementById('citaModalLabel').textContent = 'Editar Cita';
                                citaModal.show();
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Error al cargar los datos de la cita');
                        }
                    });
                });

                // Eliminar cita
                document.querySelectorAll('.delete-cita').forEach(button => {
                    button.addEventListener('click', async function() {
                        if (!confirm('¿Está seguro de eliminar esta cita?')) return;
                        
                        const id = this.dataset.id;
                        try {
                            const response = await fetch('../../controlador/citacontroller.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `action=delete&id=${id}`
                            });
                            
                            const data = await response.json();
                            
                            if (data.success) {
                                location.reload();
                            } else {
                                throw new Error(data.message);
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Error al eliminar la cita');
                        }
                    });
                });

                // Enviar formulario
                citaForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    try {
                        const formData = new FormData(this);
                        const isEdit = formData.get('id');
                        formData.append('action', isEdit ? 'update' : 'create');
                        
                        const response = await fetch('../../controlador/citacontroller.php', {
                            method: 'POST',
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            citaModal.hide();
                            location.reload();
                        } else {
                            throw new Error(data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error al guardar la cita');
                    }
                });
            });
            </script>
            <?php elseif ($seccion === 'productos'): ?>
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Gestión de Productos</h2>
                    <button class="btn btn-primary" id="add-product-btn">
                        <i class="bi bi-plus-circle me-2"></i>Agregar Nuevo Producto
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Imagen</th>
                                <th>Stock</th>
                                <th>Categoría</th>
                                <th>Estado</th>
                                <th>Fecha Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once __DIR__ . '/../../config/database.php';
                            require_once __DIR__ . '/../../modelo/Producto.php';
                            
                            $database = new Database();
                            $conn = $database->getConnection();
                            $producto = new Producto($conn);
                            
                            $productos = $producto->getAll();
                            foreach($productos as $row):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['descripcion'], 0, 50)) . '...'; ?></td>
                                <td>$<?php echo number_format($row['precio'], 2, '.', ','); ?></td>
                                <td>
                                    <?php if($row['imagen']): ?>
                                    <img src="../../assets/productos/<?php echo htmlspecialchars($row['imagen']); ?>" 
                                         alt="<?php echo htmlspecialchars($row['nombre']); ?>"
                                         class="img-thumbnail"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                    <?php else: ?>
                                    <span class="text-muted">Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $row['stock'] > 10 ? 'success' : ($row['stock'] > 0 ? 'warning' : 'danger'); ?>">
                                        <?php echo $row['stock']; ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($row['categoria']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $row['activo'] ? 'success' : 'danger'; ?>">
                                        <?php echo $row['activo'] ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_registro'])); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-product" data-id="<?php echo $row['id']; ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-<?php echo $row['activo'] ? 'danger' : 'success'; ?> toggle-status" 
                                            data-id="<?php echo $row['id']; ?>"
                                            data-status="<?php echo $row['activo']; ?>">
                                        <i class="bi bi-<?php echo $row['activo'] ? 'toggle-off' : 'toggle-on'; ?>"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($productos)): ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="bi bi-box-seam fs-2 d-block mb-2"></i>
                                    No hay productos registrados
                                </td>
                            </tr>
                            <?php endif; ?>
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

    <!-- Modal para Productos -->
    <div class="modal fade" id="productoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productoModalLabel">Agregar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="productoForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="producto_id" name="id">
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoría</label>
                            <select class="form-select" id="categoria" name="categoria" required>
                                <option value="">Seleccione una categoría</option>
                                <option value="Alimento">Alimento</option>
                                <option value="Juguete">Juguete</option>
                                <option value="Accesorio">Accesorio</option>
                                <option value="Higiene">Higiene</option>
                                <option value="Medicamento">Medicamento</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                            <div id="imagenPreview" class="mt-2"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        const formModal = new bootstrap.Modal(document.getElementById('form-modal'));
        const modalTitle = document.getElementById('modal-title');
        const modalForm = document.getElementById('modal-form');
        let currentSection = '<?php echo $seccion; ?>';

        // Configuración de formularios según sección
        const formConfigs = {
            mascotas: {
                fields: [
                    { name: 'nombre', label: 'Nombre', type: 'text', required: true },
                    { name: 'especie', label: 'Especie', type: 'text', required: true },
                    { name: 'raza', label: 'Raza', type: 'text', required: true },
                    { name: 'usuario_id', label: 'Dueño', type: 'select', required: true, options: [] }
                ],
                addTitle: 'Agregar Nueva Mascota',
                editTitle: 'Editar Mascota',
                endpoint: '../../controlador/mascotacontroller.php'
            },
            clientes: {
                fields: [
                    { name: 'nombre', label: 'Nombre', type: 'text', required: true },
                    { name: 'email', label: 'Email', type: 'email', required: true },
                    { name: 'telefono', label: 'Teléfono', type: 'tel', required: true },
                    { name: 'password', label: 'Contraseña', type: 'password', required: false }
                ],
                addTitle: 'Agregar Nuevo Cliente',
                editTitle: 'Editar Cliente',
                endpoint: '../../controlador/usuariocontroller.php'
            }
        };

        // Cargar usuarios para select de mascotas
        if (currentSection === 'mascotas') {
            $.get('../../controlador/usuariocontroller.php?action=getClientes', function(response) {
                const users = JSON.parse(response);
                formConfigs.mascotas.fields.find(f => f.name === 'usuario_id').options = 
                    users.map(u => ({ value: u.id, text: u.nombre }));
            });
        }

        // Agregar nuevo elemento
        $('#add-new-btn').click(function() {
            const config = formConfigs[currentSection];
            modalTitle.textContent = config.addTitle;
            createForm(config.fields);
            formModal.show();
        });

        // Editar elemento
        $(document).on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            const config = formConfigs[currentSection];
            modalTitle.textContent = config.editTitle;
            
            $.get(`${config.endpoint}?action=get&id=${id}`, function(response) {
                const data = JSON.parse(response);
                createForm(config.fields, data);
                formModal.show();
            });
        });

        // Eliminar elemento
        $(document).on('click', '.delete-btn', function() {
            if (!confirm('¿Está seguro de eliminar este elemento?')) return;
            
            const id = $(this).data('id');
            const config = formConfigs[currentSection];
            
            $.post(config.endpoint, {
                action: 'delete',
                id: id
            }, function(response) {
                location.reload();
            });
        });

        // Enviar formulario
        modalForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const config = formConfigs[currentSection];
            const formData = new FormData(this);
            formData.append('action', formData.get('id') ? 'update' : 'create');

            $.ajax({
                url: config.endpoint,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    formModal.hide();
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error al guardar los datos');
                }
            });
        });

        // Función para crear formulario dinámicamente
        function createForm(fields, data = null) {
            modalForm.innerHTML = '';
            if (data) {
                modalForm.innerHTML += `<input type="hidden" name="id" value="${data.id}">`;
            }

            fields.forEach(field => {
                const div = document.createElement('div');
                div.className = 'mb-3';
                
                if (field.type === 'select') {
                    div.innerHTML = `
                        <label class="form-label">${field.label}</label>
                        <select name="${field.name}" class="form-select" ${field.required ? 'required' : ''}>
                            <option value="">Seleccione...</option>
                            ${field.options.map(opt => `
                                <option value="${opt.value}" ${data && data[field.name] == opt.value ? 'selected' : ''}>
                                    ${opt.text}
                                </option>
                            `).join('')}
                        </select>
                    `;
                } else {
                    div.innerHTML = `
                        <label class="form-label">${field.label}</label>
                        <input type="${field.type}" name="${field.name}" class="form-control"
                            ${field.required ? 'required' : ''}
                            value="${data ? (data[field.name] || '') : ''}">
                    `;
                }
                
                modalForm.appendChild(div);
            });
        }
    });

    // Script para gestión de productos
    document.addEventListener('DOMContentLoaded', function() {
        const productoModal = new bootstrap.Modal(document.getElementById('productoModal'));
        const productoForm = document.getElementById('productoForm');
        const imagenInput = document.getElementById('imagen');
        const imagenPreview = document.getElementById('imagenPreview');

        // Mostrar preview de imagen
        imagenInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagenPreview.innerHTML = `
                        <img src="${e.target.result}" class="img-thumbnail" style="max-height: 150px">
                    `;
                }
                reader.readAsDataURL(file);
            }
        });

        // Agregar nuevo producto
        document.getElementById('add-product-btn')?.addEventListener('click', function() {
            productoForm.reset();
            document.getElementById('productoModalLabel').textContent = 'Agregar Producto';
            imagenPreview.innerHTML = '';
            productoModal.show();
        });

        // Editar producto
        document.querySelectorAll('.edit-product').forEach(button => {
            button.addEventListener('click', async function() {
                const id = this.dataset.id;
                try {
                    const response = await fetch(`../../controlador/productocontroller.php?action=get&id=${id}`);
                    const data = await response.json();
                    
                    if (data.success) {
                        document.getElementById('producto_id').value = data.producto.id;
                        document.getElementById('nombre').value = data.producto.nombre;
                        document.getElementById('descripcion').value = data.producto.descripcion;
                        document.getElementById('precio').value = data.producto.precio;
                        document.getElementById('stock').value = data.producto.stock;
                        document.getElementById('categoria').value = data.producto.categoria;
                        
                        if (data.producto.imagen) {
                            imagenPreview.innerHTML = `
                                <img src="../../assets/productos/${data.producto.imagen}" 
                                     class="img-thumbnail" 
                                     style="max-height: 150px">
                            `;
                        }
                        
                        document.getElementById('productoModalLabel').textContent = 'Editar Producto';
                        productoModal.show();
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al cargar los datos del producto');
                }
            });
        });

        // Cambiar estado del producto
        document.querySelectorAll('.toggle-status').forEach(button => {
            button.addEventListener('click', async function() {
                if (!confirm('¿Está seguro de cambiar el estado de este producto?')) return;
                
                const id = this.dataset.id;
                const currentStatus = this.dataset.status === '1';
                
                try {
                    const formData = new FormData();
                    formData.append('id', id);
                    formData.append('activo', !currentStatus);
                    
                    const response = await fetch('../../controlador/productocontroller.php?action=toggleStatus', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        location.reload();
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error al cambiar el estado del producto');
                }
            });
        });

        // Enviar formulario
        productoForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(this);
                const isEdit = formData.get('id');
                formData.append('action', isEdit ? 'update' : 'create');
                
                const response = await fetch('../../controlador/productocontroller.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    productoModal.hide();
                    location.reload();
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al guardar el producto');
            }
        });
    });
    </script>
</body>
</html>
