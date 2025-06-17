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
                    <div class="mt-3">
                        <label for="metodoPago" class="form-label">Método de pago:</label>
                        <select class="form-select" id="metodoPago" required>
                            <option value="">Seleccionar método de pago</option>
                            <option value="tarjeta">Tarjeta de crédito/débito</option>
                            <option value="efectivo">Efectivo (pago al recibir)</option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor seleccione un método de pago
                        </div>
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
        // Cargar citas cuando se cambia a la pestaña de citas
        document.getElementById('citas-tab').addEventListener('shown.bs.tab', function() {
            cargarCitas();
        });
        
        // Cargar citas si estamos en la pestaña de citas al iniciar
        if (document.querySelector('#citas-tab.active')) {
            cargarCitas();
        }
        
        // Cargar productos cuando se cambia a la pestaña de tienda
        document.getElementById('tienda-tab').addEventListener('shown.bs.tab', function() {
            cargarProductos();
        });
        
        // Cargar productos si estamos en la pestaña de tienda al iniciar
        if (document.querySelector('#tienda-tab.active')) {
            cargarProductos();
        }
        
        // Inicializar el contador del carrito
        actualizarContadorCarrito();
        
        // Configurar el evento para abrir el modal del carrito
        document.getElementById('carritoBtn').addEventListener('click', function(e) {
            e.preventDefault();
            mostrarCarrito();
        });
        
        // Configurar el evento para el botón de vaciar carrito
        document.getElementById('vaciarCarrito').addEventListener('click', function() {
            vaciarCarrito();
        });
        
        // Configurar el evento para el botón de procesar compra
        document.getElementById('procesarCompra').addEventListener('click', function() {
            // Validar que se haya seleccionado un método de pago
            const metodoPago = document.getElementById('metodoPago').value;
            if (!metodoPago) {
                document.getElementById('metodoPago').classList.add('is-invalid');
                return;
            }
            
            // Llamar a la función procesarCompra() que ya existe en script.js
            procesarCompra();
        });
        
        // Quitar la clase is-invalid cuando se selecciona un método de pago
        document.getElementById('metodoPago').addEventListener('change', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
            }
        });
        
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
        
        // Función para cargar las mascotas del usuario en el selector
        function cargarMascotas() {
            console.log('Cargando mascotas del usuario...');
            const mascotaSelect = document.getElementById('mascota-select');
            const userId = <?php echo $user['id']; ?>;
            
            // Limpiar opciones anteriores excepto la primera
            while (mascotaSelect.options.length > 1) {
                mascotaSelect.remove(1);
            }
            
            // Obtener las mascotas del usuario usando una consulta directa a PHP
            <?php 
            require_once '../../modelo/mascota.php';
            $mascotaModel = new Mascota();
            $resultado = $mascotaModel->listarPorUsuario($user['id']);
            $mascotasData = [];
            while ($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
                $mascotasData[] = $row;
            }
            ?>
            
            // Usar los datos de mascotas obtenidos directamente de PHP
            const mascotas = <?php echo json_encode($mascotasData); ?>;
            
            if (mascotas && mascotas.length > 0) {
                // Si hay mascotas, agregarlas al selector
                mascotas.forEach(mascota => {
                    const option = document.createElement('option');
                    option.value = mascota.id;
                    option.textContent = mascota.nombre + ' (' + mascota.especie + ')';
                    mascotaSelect.appendChild(option);
                });
                console.log('Mascotas cargadas:', mascotas.length);
            } else {
                // Si no hay mascotas, mostrar mensaje
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No tienes mascotas registradas';
                option.disabled = true;
                mascotaSelect.appendChild(option);
                
                // Mostrar alerta
                Swal.fire({
                    icon: 'info',
                    title: 'No tienes mascotas',
                    text: 'Debes registrar al menos una mascota antes de agendar una cita',
                    confirmButtonText: 'Entendido'
                });
            }
        }

        // Función para cargar las citas del usuario
        function cargarCitas() {
            console.log('Cargando citas del usuario...');
            const citasTableBody = document.getElementById('citasTableBody');
            if (!citasTableBody) {
                console.error('No se encontró el contenedor de citas');
                return;
            }
            
            // Mostrar indicador de carga
            citasTableBody.innerHTML = '<tr><td colspan="6" class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div></td></tr>';
            
            // Obtener las citas del usuario
            fetch('../../controlador/citacontroller.php?action=listarPorUsuario')
                .then(response => response.json())
                .then(data => {
                    if (data.success && Array.isArray(data.citas) && data.citas.length > 0) {
                        // Limpiar el contenedor
                        citasTableBody.innerHTML = '';
                        
                        // Agregar cada cita a la tabla
                        data.citas.forEach(cita => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${cita.mascota_nombre}</td>
                                <td>${formatServicio(cita.servicio)}</td>
                                <td>${formatDate(cita.fecha)}</td>
                                <td>${cita.hora}</td>
                                <td><span class="badge bg-success">Confirmada</span></td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="cancelarCita(${cita.id})">
                                        <i class="bi bi-x-circle"></i> Cancelar
                                    </button>
                                </td>
                            `;
                            citasTableBody.appendChild(tr);
                        });
                    } else {
                        // Mostrar mensaje si no hay citas
                        citasTableBody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle me-2"></i>
                                        No tienes citas agendadas. Puedes agendar una nueva cita haciendo clic en el botón "Agendar Cita".
                                    </div>
                                </td>
                            </tr>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error al cargar citas:', error);
                    citasTableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-danger mb-0">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Error al cargar tus citas. Por favor, intenta de nuevo más tarde.
                                </div>
                            </td>
                        </tr>
                    `;
                });
        }
        
        // Función para formatear la fecha
        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('es-ES');
        }
        
        // Función para formatear el servicio
        function formatServicio(servicio) {
            const servicios = {
                'consulta': 'Consulta veterinaria',
                'vacunacion': 'Vacunación',
                'peluqueria': 'Peluquería',
                'desparasitacion': 'Desparasitación'
            };
            return servicios[servicio] || servicio;
        }
        
        // Función para cancelar una cita
        function cancelarCita(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cancelar cita',
                cancelButtonText: 'No, mantener cita'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('action', 'delete');
                    formData.append('id', id);
                    
                    fetch('../../controlador/citacontroller.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                '¡Cancelada!',
                                'Tu cita ha sido cancelada.',
                                'success'
                            );
                            cargarCitas(); // Recargar la lista de citas
                        } else {
                            throw new Error(data.message || 'Error al cancelar la cita');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error',
                            error.message || 'No se pudo cancelar la cita',
                            'error'
                        );
                    });
                }
            });
        }
        
        // Función para cargar los productos en la tienda
        function cargarProductos() {
            console.log('Cargando productos...');
            const productosContainer = document.getElementById('productosContainer');
            if (!productosContainer) {
                console.error('No se encontró el contenedor de productos');
                return;
            }
            
            // Mostrar indicador de carga
            productosContainer.innerHTML = '<div class="col-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
            
            // Obtener los productos
            fetch('../../controlador/productocontroller.php?action=getAll')
                .then(response => response.json())
                .then(data => {
                    if (data.success && Array.isArray(data.productos) && data.productos.length > 0) {
                        // Limpiar el contenedor
                        productosContainer.innerHTML = '';
                        
                        // Agregar cada producto como una tarjeta
                        data.productos.forEach(producto => {
                            const card = document.createElement('div');
                            card.className = 'col-md-4 col-lg-3';
                            
                            // Ruta de la imagen o imagen por defecto
                            const imagenUrl = producto.imagen 
                                ? `../../assets/productos/${producto.imagen}` 
                                : '../../assets/img/producto-default.jpg';
                            
                            card.innerHTML = `
                                <div class="card h-100 shadow-sm">
                                    <img src="${imagenUrl}" class="card-img-top" alt="${producto.nombre}" style="height: 200px; object-fit: cover;">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">${producto.nombre}</h5>
                                        <p class="card-text text-muted small">${producto.categoria}</p>
                                        <p class="card-text flex-grow-1">${producto.descripcion.substring(0, 100)}${producto.descripcion.length > 100 ? '...' : ''}</p>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <h5 class="m-0 text-primary">$${parseFloat(producto.precio).toFixed(2)}</h5>
                                            <button class="btn btn-primary" onclick="agregarAlCarrito(${producto.id}, '${producto.nombre}', ${producto.precio})">
                                                <i class="bi bi-cart-plus"></i> Agregar
                                            </button>
                                        </div>
                                        ${parseInt(producto.stock) <= 5 ? `<div class="mt-2 text-danger small"><i class="bi bi-exclamation-triangle"></i> Solo quedan ${producto.stock} unidades</div>` : ''}
                                    </div>
                                </div>
                            `;
                            productosContainer.appendChild(card);
                        });
                    } else {
                        // Mostrar mensaje si no hay productos
                        productosContainer.innerHTML = `
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    No hay productos disponibles en este momento.
                                </div>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error al cargar productos:', error);
                    productosContainer.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Error al cargar los productos. Por favor, intenta de nuevo más tarde.
                            </div>
                        </div>
                    `;
                });
        }
        
        // Función para agregar un producto al carrito
        function agregarAlCarrito(id, nombre, precio) {
            // Obtener el carrito actual del localStorage o crear uno nuevo
            let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            
            // Verificar si el producto ya está en el carrito
            const productoExistente = carrito.find(item => item.id === id);
            
            if (productoExistente) {
                // Si ya existe, aumentar la cantidad
                productoExistente.cantidad += 1;
            } else {
                // Si no existe, agregar al carrito
                carrito.push({
                    id: id,
                    nombre: nombre,
                    precio: precio,
                    cantidad: 1
                });
            }
            
            // Guardar el carrito actualizado
            localStorage.setItem('carrito', JSON.stringify(carrito));
            
            // Mostrar notificación
            Swal.fire({
                icon: 'success',
                title: 'Agregado al carrito',
                text: `${nombre} se ha agregado a tu carrito`,
                timer: 1500,
                showConfirmButton: false
            });
            
            // Actualizar contador del carrito
            actualizarContadorCarrito();
        }
        
        // Función para actualizar el contador del carrito
        function actualizarContadorCarrito() {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const cantidadTotal = carrito.reduce((total, item) => total + item.cantidad, 0);
            
            // Actualizar el contador en la interfaz
            const contadorCarrito = document.getElementById('carritoCount');
            if (contadorCarrito) {
                contadorCarrito.textContent = cantidadTotal;
                contadorCarrito.style.display = cantidadTotal > 0 ? 'inline-block' : 'none';
            }
        }
        
        // Evento para cuando se abre el modal de citas
        document.getElementById('citaModal').addEventListener('show.bs.modal', function() {
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
<!-- Modal del Carrito -->
<div class="modal fade" id="carritoModal" tabindex="-1" aria-labelledby="carritoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="carritoModalLabel"><i class="bi bi-cart3 me-2"></i>Mi Carrito</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="carritoVacio" class="text-center py-5">
                    <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                    <p class="mt-3">Tu carrito está vacío</p>
                    <button class="btn btn-outline-primary mt-2" data-bs-dismiss="modal" data-bs-toggle="tab" data-bs-target="#tienda-tab">Ir a la tienda</button>
                </div>
                <div id="carritoContenido" class="d-none">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="carritoItems">
                                <!-- Aquí se cargarán los items del carrito -->
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        <div class="card border-0 bg-light p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total:</span>
                                <span class="fw-bold" id="carritoTotal">$0.00</span>
                            </div>
                            <div class="mb-3">
                                <label for="metodoPago" class="form-label">Método de pago:</label>
                                <select class="form-select" id="metodoPago" required>
                                    <option value="">Seleccionar método de pago</option>
                                    <option value="tarjeta">Tarjeta de crédito/débito</option>
                                    <option value="efectivo">Efectivo (pago al recibir)</option>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor seleccione un método de pago
                                </div>
                            </div>
                            <button id="btnFinalizarCompra" class="btn btn-success"><i class="bi bi-check-circle me-2"></i>Finalizar compra</button>
                            <button id="btnVaciarCarrito" class="btn btn-outline-danger mt-2"><i class="bi bi-trash me-2"></i>Vaciar carrito</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para mostrar el carrito
    function mostrarCarrito() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const carritoVacio = document.getElementById('carritoVacio');
        const carritoContenido = document.getElementById('carritoContenido');
        const carritoItems = document.getElementById('carritoItems');
        const carritoTotal = document.getElementById('carritoTotal');
        
        // Mostrar u ocultar secciones según si hay items
        if (carrito.length === 0) {
            carritoVacio.classList.remove('d-none');
            carritoContenido.classList.add('d-none');
        } else {
            carritoVacio.classList.add('d-none');
            carritoContenido.classList.remove('d-none');
            
            // Limpiar contenido anterior
            carritoItems.innerHTML = '';
            
            // Calcular total
            let total = 0;
            
            // Agregar cada item al carrito
            carrito.forEach(item => {
                const subtotal = item.precio * item.cantidad;
                total += subtotal;
                
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${item.nombre}</td>
                    <td>$${parseFloat(item.precio).toFixed(2)}</td>
                    <td>
                        <div class="input-group input-group-sm" style="width: 120px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="actualizarCantidad(${item.id}, ${item.cantidad - 1})">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="text" class="form-control text-center" value="${item.cantidad}" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="actualizarCantidad(${item.id}, ${item.cantidad + 1})">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td>$${subtotal.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger" onclick="eliminarDelCarrito(${item.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;
                carritoItems.appendChild(tr);
            });
            
            // Actualizar total
            carritoTotal.textContent = `$${total.toFixed(2)}`;
        }
        
        // Mostrar el modal
        const carritoModal = new bootstrap.Modal(document.getElementById('carritoModal'));
        carritoModal.show();
    }
    
    // Función para actualizar la cantidad de un producto en el carrito
    function actualizarCantidad(id, nuevaCantidad) {
        if (nuevaCantidad < 1) {
            eliminarDelCarrito(id);
            return;
        }
        
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const index = carrito.findIndex(item => item.id === id);
        
        if (index !== -1) {
            carrito[index].cantidad = nuevaCantidad;
            localStorage.setItem('carrito', JSON.stringify(carrito));
            actualizarContadorCarrito();
            mostrarCarrito(); // Actualizar la vista del carrito
        }
    }
    
    // Función para eliminar un producto del carrito
    function eliminarDelCarrito(id) {
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        carrito = carrito.filter(item => item.id !== id);
        localStorage.setItem('carrito', JSON.stringify(carrito));
        actualizarContadorCarrito();
        mostrarCarrito(); // Actualizar la vista del carrito
    }
    
    // Configurar botón para vaciar el carrito
    document.getElementById('btnVaciarCarrito').addEventListener('click', function() {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se eliminarán todos los productos del carrito",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, vaciar carrito',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.removeItem('carrito');
                actualizarContadorCarrito();
                mostrarCarrito();
                Swal.fire(
                    '¡Carrito vacío!',
                    'Tu carrito ha sido vaciado correctamente.',
                    'success'
                );
            }
        });
    });
    
    // Configurar botón para finalizar compra
    document.getElementById('btnFinalizarCompra').addEventListener('click', function() {
        const metodoPago = document.getElementById('metodoPago').value;
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        
        // Validar que haya productos en el carrito
        if (carrito.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Carrito vacío',
                text: 'Agrega productos al carrito antes de finalizar la compra'
            });
            return;
        }
        
        // Validar que se haya seleccionado un método de pago
        if (!metodoPago) {
            document.getElementById('metodoPago').classList.add('is-invalid');
            return;
        }
        
        // Calcular el total
        const total = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
        
        // Preparar datos para enviar al servidor
        const formData = new FormData();
        formData.append('productos', JSON.stringify(carrito));
        formData.append('total', total);
        formData.append('metodo_pago', metodoPago);
        
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Procesando compra...',
            text: 'Por favor espera un momento',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Enviar datos al servidor
        fetch('../../controlador/ventacontroller.php?action=crear', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                // Si el servidor devuelve un error HTTP (400, 500, etc.)
                if (response.status === 401) {
                    throw new Error('Sesión expirada. Por favor, inicia sesión nuevamente.');
                } else {
                    throw new Error(`Error del servidor: ${response.status}`);
                }
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Compra realizada!',
                    text: 'Tu pedido ha sido procesado correctamente.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    // Limpiar carrito y cerrar modal
                    localStorage.removeItem('carrito');
                    actualizarContadorCarrito();
                    const carritoModal = bootstrap.Modal.getInstance(document.getElementById('carritoModal'));
                    carritoModal.hide();
                });
            } else {
                // Manejar errores específicos del servidor
                let errorMsg = data.message || 'Error al procesar la compra';
                throw new Error(errorMsg);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error en la compra',
                text: error.message || 'No se pudo procesar la compra. Por favor, intenta de nuevo más tarde.'
            });
        });
    });
</script>
</body>
</html>
