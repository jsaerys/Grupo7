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
require_once __DIR__ . '/../../modelo/conexion.php';
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        /* Estilos para los selects */
        .form-select {
            padding: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            font-size: 1rem;
            background-color: #fff;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            cursor: pointer;
        }

        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(73, 143, 157, 0.25);
            outline: 0;
        }

        .form-select option {
            padding: 10px;
        }

        .form-select option:first-child {
            color: #6c757d;
        }

        .form-select:required:invalid {
            color: #6c757d;
        }

        .form-select option[value=""][disabled] {
            display: none;
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

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Panel de Cliente</h2>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#citaModal" id="nuevaCitaBtn">
                        <i class="bi bi-calendar-plus me-2"></i>Agendar Cita
                    </button>
                </div>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función para cargar las mascotas del usuario
    async function cargarMascotas() {
        try {
            const response = await fetch('../../controlador/mascotacontroller.php?action=listarPorUsuario');
            if (!response.ok) {
                throw new Error('Error al cargar las mascotas');
            }
            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.message || 'Error al cargar las mascotas');
            }

            const selectMascota = document.getElementById('mascota-select');
            // Limpiar opciones existentes
            selectMascota.innerHTML = '<option value="" disabled selected>Seleccione una mascota</option>';
            
            // Agregar las mascotas al select
            data.mascotas.forEach(mascota => {
                const option = document.createElement('option');
                option.value = mascota.id;
                option.textContent = `${mascota.nombre} (${mascota.especie} - ${mascota.raza})`;
                selectMascota.appendChild(option);
            });

            // Si no hay mascotas, mostrar mensaje y deshabilitar el select
            if (data.mascotas.length === 0) {
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "No hay mascotas registradas";
                selectMascota.innerHTML = '';
                selectMascota.appendChild(option);
                selectMascota.disabled = true;
            } else {
                selectMascota.disabled = false;
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar las mascotas'
            });
        }
    }

    // Cargar mascotas cuando se abre el modal
    const citaModal = document.getElementById('citaModal');
    citaModal.addEventListener('show.bs.modal', function() {
        cargarMascotas();
    });

    // También cargar mascotas cuando se hace clic en el botón
    document.getElementById('nuevaCitaBtn').addEventListener('click', function() {
        cargarMascotas();
    });

    // Validación y envío del formulario
    document.getElementById('formCita').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!this.checkValidity()) {
            e.stopPropagation();
            this.classList.add('was-validated');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('mascota_id', document.getElementById('mascota-select').value);
            formData.append('servicio', document.getElementById('servicio-select').value);
            formData.append('fecha', document.getElementById('fecha').value);
            formData.append('hora', document.getElementById('hora').value);
            formData.append('notas', document.getElementById('notas').value);

            const response = await fetch('../../controlador/citacontroller.php?action=crear', {
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
                    // Recargar la lista de citas si existe
                    if (typeof cargarCitas === 'function') {
                        cargarCitas();
                    }
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

    // Validación de fecha y hora
    document.getElementById('fecha').addEventListener('change', function() {
        const fechaSeleccionada = new Date(this.value);
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        
        if (fechaSeleccionada < hoy) {
            this.value = '';
            Swal.fire({
                icon: 'error',
                title: 'Fecha inválida',
                text: 'No se pueden agendar citas en fechas pasadas'
            });
        }
    });

    document.getElementById('hora').addEventListener('change', function() {
        const hora = this.value;
        const [horas, minutos] = hora.split(':').map(Number);
        
        if (horas < 9 || (horas === 18 && minutos > 0) || horas > 18) {
            this.value = '';
            Swal.fire({
                icon: 'error',
                title: 'Hora inválida',
                text: 'El horario de atención es de 9:00 a 18:00'
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

// Detectar cuando la pestaña/navegador se cierra
window.addEventListener('beforeunload', function(e) {
    autoLogout();
});

// Detectar cuando el navegador se cierra
window.addEventListener('unload', function() {
    autoLogout();
});
</script>

<script>
// Función para cargar las citas
async function cargarCitas() {
    try {
        const response = await fetch('../../controlador/citacontroller.php?action=listar');
        const data = await response.json();
        
        const citasTableBody = document.getElementById('citasTableBody');
        citasTableBody.innerHTML = '';

        if (data.success && data.citas.length > 0) {
            data.citas.forEach(cita => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${cita.mascota_nombre}</td>
                    <td>${cita.servicio}</td>
                    <td>${formatearFecha(cita.fecha)}</td>
                    <td>${cita.hora}</td>
                    <td><span class="badge bg-${getEstadoColor(cita.estado)}">${cita.estado}</span></td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="eliminarCita(${cita.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;
                citasTableBody.appendChild(row);
            });
        } else {
            citasTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                        No hay citas programadas
                    </td>
                </tr>
            `;
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar las citas'
        });
    }
}

// Función para eliminar una cita
async function eliminarCita(id) {
    try {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);

            const response = await fetch('../../controlador/citacontroller.php?action=eliminar', {
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
                });
                cargarCitas(); // Recargar la lista de citas
            } else {
                throw new Error(data.message || 'Error al eliminar la cita');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'No se pudo eliminar la cita'
        });
    }
}

// Función auxiliar para formatear la fecha
function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

// Función auxiliar para obtener el color del estado
function getEstadoColor(estado) {
    switch (estado) {
        case 'pendiente':
            return 'warning';
        case 'confirmada':
            return 'success';
        case 'cancelada':
            return 'danger';
        default:
            return 'secondary';
    }
}

// Cargar citas cuando se cambia a la pestaña de citas
document.getElementById('citas-tab').addEventListener('click', cargarCitas);

// Cargar citas cuando se crea una nueva
document.getElementById('formCita').addEventListener('submit', function(e) {
    // ... código existente del evento submit ...
    if (data.success) {
        // ... código existente ...
        cargarCitas(); // Recargar la lista de citas después de crear una nueva
    }
});
</script>
</body>
</html>
