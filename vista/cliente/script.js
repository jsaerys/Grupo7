// Sonidos para feedback
const sounds = {
    success: new Audio('recursos/action-success.mp3'),
    addToCart: new Audio('recursos/add-to-cart.mp3'),
    cancel: new Audio('recursos/cancel.mp3')
};

// Estado global
let pets = [];
let appointments = [];
let cart = [];
let products = [];

// Variables globales para el carrito
let carrito = [];
let carritoModal = null;

// Elementos DOM
const petsList = document.getElementById('pet-list');
const appointmentsList = document.getElementById('appointment-list');
const productosContainer = document.getElementById('productosContainer');
const cartItems = document.getElementById('cart-items');
const cartTotalAmount = document.getElementById('cart-total-amount');
const checkoutBtn = document.getElementById('checkout-btn');

// Navegación por pestañas
document.querySelectorAll('[data-section]').forEach(button => {
    button.addEventListener('click', (e) => {
        // Remover clase active de todos los botones y secciones
        document.querySelectorAll('[data-section]').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.content-section').forEach(section => section.classList.add('hidden'));
        
        // Activar el botón y sección seleccionados
        button.classList.add('active');
        document.getElementById(`${e.target.dataset.section}-section`).classList.remove('hidden');
    });
});

// Formulario de mascotas
document.getElementById('show-add-pet-form').addEventListener('click', () => {
    document.getElementById('add-pet-form').classList.remove('hidden');
});

document.getElementById('cancel-add-pet').addEventListener('click', () => {
    document.getElementById('add-pet-form').classList.add('hidden');
    sounds.cancel.play();
});

document.getElementById('add-pet-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const name = document.getElementById('pet-name').value;
    const type = document.getElementById('pet-type').value;
    const breed = document.getElementById('pet-breed').value;

    try {
        const response = await fetch('../../controlador/mascotacontroller.php?action=create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `nombre=${encodeURIComponent(name)}&tipo=${encodeURIComponent(type)}&raza=${encodeURIComponent(breed)}`
        });

        const data = await response.json();
        if (data.success) {
            sounds.success.play();
            document.getElementById('add-pet-form').reset();
            document.getElementById('add-pet-form').classList.add('hidden');
            loadPets();
        } else {
            alert(data.message || 'Error al crear la mascota');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al crear la mascota');
    }
});

// Formulario de citas
document.getElementById('show-add-appointment-form').addEventListener('click', () => {
    document.getElementById('add-appointment-form').classList.remove('hidden');
});

document.getElementById('cancel-add-appointment').addEventListener('click', () => {
    document.getElementById('add-appointment-form').classList.add('hidden');
    sounds.cancel.play();
});

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
        selectMascota.innerHTML = '<option value="" disabled selected>Seleccione una mascota</option>';
        
        data.mascotas.forEach(mascota => {
            const option = document.createElement('option');
            option.value = mascota.id;
            option.textContent = `${mascota.nombre} (${mascota.especie} - ${mascota.raza})`;
            selectMascota.appendChild(option);
        });
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar las mascotas'
        });
    }
}

// Evento para cuando se abre el modal de citas
document.getElementById('nuevaCitaBtn').addEventListener('click', function() {
    cargarMascotas(); // Cargar las mascotas cuando se abre el modal
});

// Función para agendar cita
async function agendarCita() {
    const form = document.getElementById('citaForm');
    const mascotaSelect = document.getElementById('mascota-select');
    const servicioSelect = document.getElementById('servicio-select');
    const fechaInput = document.getElementById('fecha-input');
    const notasInput = document.getElementById('notas-input');

    if (!mascotaSelect.value || !servicioSelect.value || !fechaInput.value) {
        alert('Por favor complete todos los campos requeridos');
        return;
    }

    // Validar que la fecha no sea en el pasado
    const selectedDate = new Date(fechaInput.value);
    const now = new Date();
    if (selectedDate < now) {
        alert('La fecha de la cita no puede ser en el pasado');
        return;
    }

    try {
    const formData = new FormData();
    formData.append('action', 'crear');
    formData.append('id_mascota', mascotaSelect.value);
    formData.append('motivo', servicioSelect.value);
    formData.append('fecha', fechaInput.value);
        formData.append('notas', notasInput.value);

        const response = await fetch('../../controlador/citacontroller.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            sounds.success.play();
            // Cerrar el modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('citaModal'));
            modal.hide();
            
            // Limpiar formulario
            form.reset();
            
            // Recargar lista de citas
            loadAppointments();
            
            // Mostrar mensaje de éxito
            alert('Cita agendada con éxito');
        } else {
            throw new Error(data.message || 'Error al agendar la cita');
        }
    } catch (error) {
        console.error('Error:', error);
        sounds.cancel.play();
        alert(error.message || 'Error al agendar la cita');
    }
}

// Cargar mascotas
async function loadPets() {
    try {
        const response = await fetch(`../../controlador/mascotacontroller.php?action=list`);
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'Error al cargar las mascotas');
        }
        
        pets = data.data || [];
        renderPets();
        updateAppointmentPetSelect();
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('pet-list').innerHTML = 
            `<div class="alert alert-danger">${error.message}</div>`;
    }
}

// Cargar citas
async function loadAppointments() {
    try {
        const response = await fetch(`../../controlador/citacontroller.php?action=listarPorUsuario&usuario_id=${userId}`);
        const data = await response.json();
        appointments = data || [];
        renderAppointments();
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('appointment-list').innerHTML = 
            '<div class="alert alert-danger">Error al cargar las citas. Por favor, inicia sesión nuevamente.</div>';
    }
}

// Función para inicializar los eventos de las pestañas
function initializeTabs() {
    const triggerTabList = [].slice.call(document.querySelectorAll('#myTabs button'));
    triggerTabList.forEach(function(triggerEl) {
        const tabTrigger = new bootstrap.Tab(triggerEl);
        
        triggerEl.addEventListener('click', function(event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, iniciando aplicación...');
    
    // Inicializar los tabs
    initializeTabs();

    // Configurar eventos para las citas
    const citasTab = document.querySelector('#citas-tab');
    const citasPane = document.querySelector('#citas');
    const tiendaTab = document.querySelector('#tienda-tab');
    const tiendaPane = document.querySelector('#tienda');
    
    if (citasTab && citasPane) {
        console.log('Configurando eventos de citas...');
        
        // Cargar citas si la pestaña está activa inicialmente
        if (citasPane.classList.contains('show active')) {
            console.log('Pestaña de citas activa inicialmente, cargando citas...');
            setTimeout(() => cargarCitas(), 100);
        }

        // Evento para cuando se muestra la pestaña de citas
        citasTab.addEventListener('shown.bs.tab', function (e) {
            console.log('Pestaña de citas activada');
            cargarCitas();
        });
    }

    // Configurar eventos para la tienda
    if (tiendaTab && tiendaPane) {
        console.log('Configurando eventos de tienda...');
        
        // Cargar productos si la pestaña está activa inicialmente
        if (tiendaPane.classList.contains('show active')) {
            console.log('Pestaña de tienda activa inicialmente, cargando productos...');
            setTimeout(() => loadProducts(), 100);
        }

        // Evento para cuando se muestra la pestaña de tienda
        tiendaTab.addEventListener('shown.bs.tab', function (e) {
            console.log('Pestaña de tienda activada, cargando productos...');
            loadProducts();
        });
    } else {
        console.error('No se encontraron elementos de la pestaña de tienda');
    }

    // Inicializar el modal del carrito
    const modalElement = document.getElementById('carritoModal');
    if (modalElement) {
        carritoModal = new bootstrap.Modal(modalElement);
    }

    // Evento para mostrar el carrito
    const carritoBtn = document.getElementById('carritoBtn');
    if (carritoBtn) {
        carritoBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (carritoModal) {
                carritoModal.show();
            }
        });
    }

    // Evento para vaciar el carrito
    const vaciarCarritoBtn = document.getElementById('vaciarCarrito');
    if (vaciarCarritoBtn) {
        vaciarCarritoBtn.addEventListener('click', function() {
            Swal.fire({
                title: '¿Vaciar carrito?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, vaciar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    vaciarCarrito();
                }
            });
        });
    }

    // Evento para procesar la compra
    const procesarCompraBtn = document.getElementById('procesarCompra');
    if (procesarCompraBtn) {
        procesarCompraBtn.addEventListener('click', procesarCompra);
    }
});

// Función para formatear precio con separador de miles y decimales
function formatPrice(price) {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(price);
}

// Función para cargar productos
async function loadProducts() {
    console.log('Iniciando carga de productos...');
    const productosContainer = document.getElementById('productosContainer');
    
    if (!productosContainer) {
        console.error('No se encontró el contenedor de productos');
        return;
    }

    try {
        // Mostrar spinner mientras carga
        productosContainer.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Cargando productos...</p>
            </div>`;

        console.log('Realizando petición al servidor...');
        const response = await fetch('../../controlador/productocontroller.php?action=getAll');
        console.log('Respuesta del servidor:', response.status, response.statusText);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Datos de productos recibidos:', data);

        if (data.success && data.productos && Array.isArray(data.productos)) {
            console.log(`Se encontraron ${data.productos.length} productos`);
            if (data.productos.length > 0) {
                productosContainer.innerHTML = data.productos.map(producto => {
                    console.log('Procesando producto:', producto);
                    return `
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">${producto.nombre}</h5>
                                    <p class="card-text text-muted small">${producto.descripcion || 'Sin descripción'}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 mb-0">$${Number(producto.precio).toLocaleString('es-CO')}</span>
                                        <button class="btn btn-primary btn-sm" onclick="agregarAlCarrito({
                                            id: ${producto.id},
                                            nombre: '${producto.nombre.replace(/'/g, "\\'")}',
                                            precio: ${producto.precio}
                                        })">
                                            <i class="bi bi-cart-plus"></i> Agregar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
                console.log('Productos renderizados correctamente');
            } else {
                productosContainer.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-shop-window fs-1 text-muted"></i>
                        <p class="mt-3 text-muted">No hay productos disponibles</p>
                    </div>`;
                console.log('No se encontraron productos para mostrar');
            }
        } else {
            console.error('Respuesta del servidor inválida:', data);
            throw new Error(data.message || 'Error al cargar los productos');
        }
    } catch (error) {
        console.error('Error al cargar productos:', error);
        productosContainer.innerHTML = `
            <div class="col-12 text-center py-5 text-danger">
                <i class="bi bi-exclamation-triangle fs-1"></i>
                <p class="mt-3">Error al cargar los productos: ${error.message}</p>
            </div>`;
    }
}

// Función para actualizar el contador del carrito
function actualizarContadorCarrito() {
    const contador = document.getElementById('carritoCount');
    if (contador) {
        contador.textContent = carrito.length;
    }
}

// Función para calcular el total del carrito
function calcularTotalCarrito() {
    return carrito.reduce((total, item) => total + (item.precio * item.cantidad), 0);
}

// Función para actualizar la vista del carrito
function actualizarVistaCarrito() {
    const carritoItems = document.getElementById('carritoItems');
    const carritoTotal = document.getElementById('carritoTotal');
    
    if (carritoItems && carritoTotal) {
        if (carrito.length === 0) {
            carritoItems.innerHTML = `
                <div class="text-center py-4">
                    <i class="bi bi-cart-x fs-1 text-muted"></i>
                    <p class="text-muted mt-3">Tu carrito está vacío</p>
                </div>`;
        } else {
            carritoItems.innerHTML = carrito.map(item => `
                <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                    <div>
                        <h6 class="mb-0">${item.nombre}</h6>
                        <small class="text-muted">$${Number(item.precio).toLocaleString('es-CO')} x ${item.cantidad}</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-outline-primary me-2" onclick="cambiarCantidad(${item.id}, ${item.cantidad - 1})">
                            <i class="bi bi-dash"></i>
                        </button>
                        <span class="mx-2">${item.cantidad}</span>
                        <button class="btn btn-sm btn-outline-primary me-2" onclick="cambiarCantidad(${item.id}, ${item.cantidad + 1})">
                            <i class="bi bi-plus"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="eliminarDelCarrito(${item.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `).join('');
        }
        
        carritoTotal.textContent = `$${calcularTotalCarrito().toLocaleString('es-CO')}`;
    }
}

// Función para agregar al carrito
async function agregarAlCarrito(producto) {
    const itemExistente = carrito.find(item => item.id === producto.id);
    
    if (itemExistente) {
        itemExistente.cantidad++;
    } else {
        carrito.push({
            id: producto.id,
            nombre: producto.nombre,
            precio: producto.precio,
            cantidad: 1
        });
    }
    
    actualizarContadorCarrito();
    actualizarVistaCarrito();
    
    // Mostrar notificación
    Swal.fire({
        icon: 'success',
        title: '¡Agregado!',
        text: 'Producto agregado al carrito',
        timer: 1500,
        showConfirmButton: false
    });
}

// Función para cambiar la cantidad de un item
function cambiarCantidad(id, nuevaCantidad) {
    if (nuevaCantidad <= 0) {
        eliminarDelCarrito(id);
        return;
    }
    
    const item = carrito.find(item => item.id === id);
    if (item) {
        item.cantidad = nuevaCantidad;
        actualizarVistaCarrito();
    }
}

// Función para eliminar del carrito
function eliminarDelCarrito(id) {
    carrito = carrito.filter(item => item.id !== id);
    actualizarContadorCarrito();
    actualizarVistaCarrito();
}

// Función para vaciar el carrito
function vaciarCarrito() {
    carrito = [];
    actualizarContadorCarrito();
    actualizarVistaCarrito();
}

// Función para procesar la compra
async function procesarCompra() {
    try {
        if (carrito.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Carrito vacío',
                text: 'Agrega productos antes de procesar la compra'
            });
            return;
        }

        const result = await Swal.fire({
            title: '¿Procesar compra?',
            text: `Total a pagar: $${calcularTotalCarrito().toLocaleString('es-CO')}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, procesar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            // Aquí iría la lógica para procesar la compra
            // Por ahora solo simulamos el proceso
            Swal.fire({
                icon: 'success',
                title: '¡Compra exitosa!',
                text: 'Gracias por tu compra'
            });
            vaciarCarrito();
            if (carritoModal) {
                carritoModal.hide();
            }
        }
    } catch (error) {
        console.error('Error al procesar la compra:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo procesar la compra'
        });
    }
}

// Renderizar mascotas
function renderPets() {
    if (pets.length === 0) {
        petsList.innerHTML = '<p class="text-muted">Aún no tienes mascotas registradas. ¡Agrega una!</p>';
        return;
    }

    petsList.innerHTML = pets.map(pet => `
        <div class="card pet-card h-100">
            <div class="text-center pt-3">
                <img src="recursos/${pet.tipo}-avatar.png" class="card-img-top rounded-circle" alt="${pet.nombre}" style="width: 120px; height: 120px; object-fit: cover;">
            </div>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title text-center mb-3">${pet.nombre}</h5>
                <div class="pet-info mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tipo:</span>
                        <span class="fw-bold text-capitalize">${pet.tipo}</span>
                    </div>
                    ${pet.raza ? `
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Raza:</span>
                        <span class="fw-bold">${pet.raza}</span>
                    </div>` : ''}
                </div>
                <div class="mt-auto text-center">
                    <button onclick="deletePet(${pet.id})" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash me-1"></i>Eliminar Mascota
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Renderizar citas
function renderAppointments() {
    if (appointments.length === 0) {
        appointmentsList.innerHTML = '<p class="text-muted">No tienes citas programadas.</p>';
        return;
    }

    appointmentsList.innerHTML = appointments.map(appointment => `
        <div class="card appointment-card h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center mb-3">
                    <div class="appointment-icon me-3">
                        <i class="bi bi-calendar-check fs-3 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">${appointment.motivo}</h5>
                        <p class="text-muted mb-0">${appointment.mascota_nombre}</p>
                    </div>
                </div>
                <div class="appointment-info mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-clock me-2"></i>
                        <span>${new Date(appointment.fecha).toLocaleString()}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-bookmark me-2"></i>
                        <span class="badge ${appointment.estado === 'pendiente' ? 'bg-warning' : 
                                              (appointment.estado === 'completada' ? 'bg-success' : 'bg-danger')}">
                            ${appointment.estado.charAt(0).toUpperCase() + appointment.estado.slice(1)}
                        </span>
                    </div>
                </div>
                ${appointment.estado === 'pendiente' ? `
                    <div class="mt-auto text-end">
                        <button onclick="cancelarCita(${appointment.id})" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-x-circle me-1"></i>Cancelar Cita
                        </button>
                    </div>
                ` : ''}
            </div>
        </div>
    `).join('');
}

// Actualizar select de mascotas para citas
function updateAppointmentPetSelect() {
    const select = document.getElementById('mascota-select');
    select.innerHTML = '<option value="" disabled selected>Selecciona una mascota</option>' +
        pets.map(pet => `<option value="${pet.id}">${pet.nombre}</option>`).join('');
}

// Eliminar mascota
async function deletePet(id) {
    if (!confirm('¿Estás seguro de que deseas eliminar esta mascota?')) {
        return;
    }

    try {
        const response = await fetch(`../../controlador/mascotacontroller.php?action=delete&id=${id}`);
        const data = await response.json();
        if (data.success) {
            sounds.success.play();
            loadPets();
        } else {
            alert(data.message || 'Error al eliminar la mascota');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al eliminar la mascota');
    }
}

// Cancelar cita
async function cancelarCita(id) {
    if (!confirm('¿Está seguro de cancelar esta cita?')) return;

    try {
        const formData = new FormData();
        formData.append('action', 'cancelar');
        formData.append('id', id);

        const response = await fetch('../../controlador/citacontroller.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (response.ok) {
            sounds.cancel.play();
            alert('Cita cancelada con éxito');
            renderAppointments();
        } else {
            throw new Error(data.error || 'Error al cancelar la cita');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cancelar la cita');
    }
}

// Renderizar productos
function renderProducts() {
    console.log('Iniciando renderizado de productos...');
    const container = document.getElementById('productosContainer');
    console.log('Contenedor:', container);
    console.log('Productos a renderizar:', products);
    
    if (!container) {
        console.error('No se encontró el contenedor de productos');
        return;
    }
    
    if (!Array.isArray(products) || products.length === 0) {
        console.log('No hay productos para mostrar');
        container.innerHTML = `
            <div class="col-12">
                <p class="text-muted text-center">
                    <i class="bi bi-box-seam fs-2 d-block mb-2"></i>
                    No hay productos disponibles en este momento.
                </p>
            </div>`;
        return;
    }

    try {
        const productsHTML = products.map(product => `
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="${product.image}" 
                         class="card-img-top" 
                         alt="${product.name}"
                         onerror="this.src='../../assets/img/no-image.jpg'" 
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">${product.name}</h5>
                        <p class="card-text flex-grow-1">${product.description}</p>
                        <div class="mt-auto">
                            <p class="card-text mb-2">
                                <strong class="fs-5">${formatPrice(product.price)}</strong>
                                ${product.stock > 0 ? 
                                    `<span class="badge bg-success ms-2">Stock: ${product.stock}</span>` : 
                                    '<span class="badge bg-danger ms-2">Sin stock</span>'}
                            </p>
                            <button onclick="addToCart(${product.id})" 
                                    class="btn btn-primary w-100" 
                                    ${product.stock <= 0 ? 'disabled' : ''}>
                                <i class="bi bi-cart-plus me-2"></i>
                                Agregar al Carrito
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
        
        console.log('HTML generado:', productsHTML.substring(0, 100) + '...');
        container.innerHTML = productsHTML;
        console.log('Renderizado completado');
    } catch (error) {
        console.error('Error al renderizar productos:', error);
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Error al mostrar los productos: ${error.message}
                </div>
            </div>`;
    }
}

// Cargar citas
async function cargarCitas() {
    console.log('Iniciando carga de citas...');
    const citasTableBody = document.getElementById('citasTableBody');
    
    if (!citasTableBody) {
        console.error('No se encontró el contenedor de citas');
        return;
    }

    try {
        citasTableBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2">Cargando citas...</p>
                </td>
            </tr>`;

        const response = await fetch('../../controlador/citacontroller.php?action=listarPorUsuario');
        console.log('Respuesta del servidor:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Datos de citas recibidos:', data);

        if (data.success && data.citas && Array.isArray(data.citas)) {
            if (data.citas.length > 0) {
                citasTableBody.innerHTML = data.citas.map(cita => `
                    <tr>
                        <td>${cita.mascota_nombre || 'N/A'}</td>
                        <td>${cita.servicio || 'N/A'}</td>
                        <td>${formatearFecha(cita.fecha)}</td>
                        <td>${cita.hora || 'N/A'}</td>
                        <td>
                            <span class="badge ${cita.estado === 'Pendiente' ? 'bg-primary' : 
                                              cita.estado === 'Completada' ? 'bg-success' : 
                                              'bg-secondary'}">
                                ${cita.estado || 'Pendiente'}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger" onclick="cancelarCita(${cita.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            } else {
                citasTableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                            No hay citas programadas
                        </td>
                    </tr>`;
            }
        } else {
            throw new Error(data.message || 'Error al cargar las citas');
        }
    } catch (error) {
        console.error('Error al cargar citas:', error);
        citasTableBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-danger py-4">
                    <i class="bi bi-exclamation-triangle fs-2 d-block mb-2"></i>
                    Error al cargar las citas: ${error.message}
                </td>
            </tr>`;
    }
}

// Función para formatear fecha
function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}
