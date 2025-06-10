<?php
// Configurar el tiempo de vida de la sesión (8 horas)
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

// Incluir la conexión a la base de datos
require_once '../../config/database.php';
$database = new Database();
$conn = $database->getConnection();

// Obtener información del usuario
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Cliente - Veterinaria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/css/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-heart-pulse-fill me-2"></i>
                Veterinaria
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" id="carritoBtn">
                            <i class="bi bi-cart3"></i>
                            <span class="badge bg-danger rounded-pill" id="carritoCount">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="bi bi-person-circle me-1"></i>
                            <?php echo htmlspecialchars($user['nombre']); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../controlador/usuariocontroller.php?action=logout">
                            <i class="bi bi-box-arrow-right me-1"></i>
                            Cerrar sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <h1 class="mb-4">Bienvenido, <?php echo htmlspecialchars($user['nombre']); ?></h1>

        <div class="row">
            <div class="col-12">
                <h2>Panel de Cliente</h2>

                <ul class="nav nav-tabs mb-4" id="myTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" 
                                id="mascotas-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#mascotas" 
                                type="button" 
                                role="tab" 
                                aria-controls="mascotas" 
                                aria-selected="true">
                            <i class="bi bi-heart-fill me-2"></i>Mis Mascotas
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" 
                                id="citas-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#citas" 
                                type="button" 
                                role="tab" 
                                aria-controls="citas" 
                                aria-selected="false">
                            <i class="bi bi-calendar-check me-2"></i>Citas
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" 
                                id="tienda-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#tienda" 
                                type="button" 
                                role="tab" 
                                aria-controls="tienda" 
                                aria-selected="false">
                            <i class="bi bi-shop me-2"></i>Tienda
                        </button>
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
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#citaModal">
                                <i class="bi bi-calendar-plus me-2"></i>Agendar Cita
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mascota</th>
                                        <th>Servicio</th>
                                        <th>Fecha</th>
                                        <th>Hora</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="citasTableBody">
                                    <!-- Las citas se cargarán aquí dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" 
                         id="tienda" 
                         role="tabpanel" 
                         aria-labelledby="tienda-tab">
                        <div class="container-fluid px-4">
                            <h3 class="mb-4">Productos Disponibles</h3>
                            <div class="row g-4" id="productosContainer">
                                <!-- Los productos se cargarán aquí dinámicamente -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal para agendar cita -->
    <div class="modal fade" id="citaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agendar Nueva Cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formCita" class="needs-validation" novalidate>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="mascota-select" class="form-label">Mascota</label>
                            <select class="form-select" id="mascota-select" name="mascota_id" required>
                                <option value="" disabled selected>Seleccione una mascota</option>
                            </select>
                            <div class="invalid-feedback">
                                Por favor seleccione una mascota
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="servicio-select" class="form-label">Servicio</label>
                            <select class="form-select" id="servicio-select" required>
                                <option value="" disabled selected>Seleccione un servicio</option>
                                <option value="consulta">Consulta veterinaria</option>
                                <option value="vacunacion">Vacunación</option>
                                <option value="peluqueria">Peluquería</option>
                                <option value="desparasitacion">Desparasitación</option>
                            </select>
                            <div class="invalid-feedback">
                                Por favor seleccione un servicio
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" required min="<?php echo date('Y-m-d'); ?>">
                            <div class="invalid-feedback">
                                Por favor seleccione una fecha válida
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="hora" class="form-label">Hora</label>
                            <input type="time" class="form-control" id="hora" required min="09:00" max="18:00">
                            <div class="invalid-feedback">
                                Por favor seleccione una hora entre las 9:00 y las 18:00
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas adicionales</label>
                            <textarea class="form-control" id="notas" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Agendar Cita</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal del Carrito -->
    <div class="modal fade" id="carritoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-cart3 me-2"></i>
                        Carrito de Compras
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="carritoItems">
                        <!-- Los items del carrito se cargarán aquí -->
                    </div>
                    <div class="text-end mt-3">
                        <h5>Total: <span id="carritoTotal">$0</span></h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-danger" id="vaciarCarrito">
                        <i class="bi bi-trash me-2"></i>Vaciar Carrito
                    </button>
                    <button type="button" class="btn btn-primary" id="procesarCompra">
                        <i class="bi bi-check-circle me-2"></i>Procesar Compra
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="script.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM cargado, iniciando aplicación...');
        
        // Inicializar los tabs de Bootstrap
        const triggerTabList = [].slice.call(document.querySelectorAll('#myTabs button'));
        triggerTabList.forEach(function(triggerEl) {
            const tabTrigger = new bootstrap.Tab(triggerEl);
            
            triggerEl.addEventListener('click', function(event) {
                event.preventDefault();
                tabTrigger.show();
            });
        });

        // Eventos de pestañas
        document.getElementById('citas-tab').addEventListener('shown.bs.tab', () => {
            console.log('Tab de citas activado, recargando citas...');
            cargarCitas();
        });

        // Evento para cuando se abre el modal de citas
        document.getElementById('nuevaCitaBtn').addEventListener('click', function() {
            cargarMascotas();
        });

        // Validación y envío del formulario de citas
        document.getElementById('formCita').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            try {
                const formData = new FormData();
                formData.append('action', 'create');
                formData.append('mascota_id', document.getElementById('mascota-select').value);
                formData.append('servicio', document.getElementById('servicio-select').value);
                formData.append('fecha_hora', document.getElementById('fecha').value + 'T' + document.getElementById('hora').value);
                formData.append('notas', document.getElementById('notas').value);
                formData.append('usuario_id', <?php echo $user['id']; ?>);

                const response = await fetch('../../controlador/citacontroller.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        bootstrap.Modal.getInstance(document.getElementById('citaModal')).hide();
                        cargarCitas(); // Recargar la lista de citas
                    });
                } else {
                    throw new Error(data.message || 'Error al agendar la cita');
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'No se pudo agendar la cita'
                });
            }
        });
    });
    </script>

    <script>
    // Función para cerrar sesión
    function autoLogout() {
        fetch('../../controlador/autologout.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Sesión cerrada automáticamente');
                }
            })
            .catch(error => console.error('Error en auto logout:', error));
    }

    window.addEventListener('beforeunload', autoLogout);
    window.addEventListener('unload', autoLogout);
    </script>
</body>
</html>
