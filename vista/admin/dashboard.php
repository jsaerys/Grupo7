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
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT v.id, u.nombre as cliente, p.nombre as producto, v.cantidad, 
                                      (v.cantidad * p.precio) as total, v.fecha, v.estado 
                                      FROM ventas v 
                                      INNER JOIN usuarios u ON v.id_cliente = u.id 
                                      INNER JOIN productos p ON v.id_producto = p.id 
                                      ORDER BY v.fecha DESC";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['cliente']); ?></td>
                                <td><?php echo htmlspecialchars($row['producto']); ?></td>
                                <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                                <td>$<?php echo number_format($row['total'], 2); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['fecha'])); ?></td>
                                <td>
                                    <span class="badge <?php echo $row['estado'] === 'completada' ? 'bg-success' : 'bg-warning'; ?>">
                                        <?php echo ucfirst($row['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $row['id']; ?>"><i class="bi bi-pencil"></i></button>
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
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Mascota</th>
                                <th>Dueño</th>
                                <th>Servicio</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT c.id, m.nombre as mascota, u.nombre as dueno, c.motivo as servicio, 
                                      c.fecha, c.estado 
                                      FROM citas c 
                                      INNER JOIN mascotas m ON c.id_mascota = m.id 
                                      INNER JOIN usuarios u ON m.usuario_id = u.id 
                                      ORDER BY c.fecha DESC";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['mascota']); ?></td>
                                <td><?php echo htmlspecialchars($row['dueno']); ?></td>
                                <td><?php echo htmlspecialchars($row['servicio']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['fecha'])); ?></td>
                                <td>
                                    <span class="badge <?php echo $row['estado'] === 'completada' ? 'bg-success' : 
                                        ($row['estado'] === 'pendiente' ? 'bg-warning' : 'bg-danger'); ?>">
                                        <?php echo ucfirst($row['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?php echo $row['id']; ?>"><i class="bi bi-pencil"></i></button>
                                    <?php if ($row['estado'] === 'pendiente'): ?>
                                    <button class="btn btn-sm btn-outline-danger cancel-btn" data-id="<?php echo $row['id']; ?>"><i class="bi bi-x-circle"></i></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php if ($stmt->rowCount() === 0): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                                    No hay citas programadas
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
    </script>
</body>
</html>
