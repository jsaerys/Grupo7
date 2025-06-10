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

// Elementos DOM
const petsList = document.getElementById('pet-list');
const appointmentsList = document.getElementById('appointment-list');
const productGrid = document.getElementById('product-grid');
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

// Cargar productos desde el servidor
async function loadProducts() {
    try {
        const response = await fetch('../../controlador/productocontroller.php?action=listar');
        const data = await response.json();
        products = data.map(product => ({
            id: product.id,
            name: product.nombre,
            price: parseFloat(product.precio),
            image: `recursos/${product.imagen}`,
            description: product.descripcion,
            stock: product.stock
        }));
        renderProducts();
    } catch (error) {
        console.error('Error al cargar productos:', error);
        productGrid.innerHTML = '<div class="alert alert-danger">Error al cargar los productos. Por favor, intente más tarde.</div>';
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
    if (products.length === 0) {
        productGrid.innerHTML = '<p class="text-muted">No hay productos disponibles en este momento.</p>';
        return;
    }

    productGrid.innerHTML = products.map(product => `
        <div class="card product-card">
            <img src="${product.image}" class="card-img-top" alt="${product.name}">
            <div class="card-body">
                <h5 class="card-title">${product.name}</h5>
                <p class="card-text">${product.description}</p>
                <p class="card-text">
                    <strong>$${product.price.toFixed(2)}</strong>
                    ${product.stock > 0 ? 
                        `<span class="badge bg-success ms-2">Stock: ${product.stock}</span>` : 
                        '<span class="badge bg-danger ms-2">Sin stock</span>'}
                </p>
                <button onclick="addToCart(${product.id})" class="btn btn-primary" ${product.stock <= 0 ? 'disabled' : ''}>
                    <i class="bi bi-cart-plus"></i> Agregar al Carrito
                </button>
            </div>
        </div>
    `).join('');
}

// Agregar al carrito
function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    const cartItem = cart.find(item => item.id === productId);

    if (cartItem) {
        cartItem.quantity++;
    } else {
        cart.push({ ...product, quantity: 1 });
    }

    sounds.addToCart.play();
    renderCart();
}

// Renderizar carrito
function renderCart() {
    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="text-muted">Tu carrito está vacío.</p>';
        cartTotalAmount.textContent = '$0.00';
        checkoutBtn.disabled = true;
        return;
    }

    cartItems.innerHTML = cart.map(item => `
        <div class="cart-item">
            <div>
                <strong>${item.name}</strong><br>
                <small>$${item.price.toFixed(2)} x ${item.quantity}</small>
            </div>
            <div>
                <button onclick="removeFromCart(${item.id})" class="btn btn-sm btn-danger">
                    <i class="bi bi-dash"></i>
                </button>
            </div>
        </div>
    `).join('');

    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    cartTotalAmount.textContent = `$${total.toFixed(2)}`;
    checkoutBtn.disabled = false;
}

// Quitar del carrito
function removeFromCart(productId) {
    const index = cart.findIndex(item => item.id === productId);
    if (index !== -1) {
        if (cart[index].quantity > 1) {
            cart[index].quantity--;
        } else {
            cart.splice(index, 1);
        }
        sounds.cancel.play();
        renderCart();
    }
}

// Checkout
checkoutBtn.addEventListener('click', async () => {
    try {
        const response = await fetch('../../controlador/ventacontroller.php?action=create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ items: cart })
        });

        const data = await response.json();
        if (data.success) {
            sounds.success.play();
            cart = [];
            renderCart();
            alert('¡Compra realizada con éxito!');
        } else {
            alert(data.message || 'Error al procesar la compra');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al procesar la compra');
    }
});

// Inicialización
document.addEventListener('DOMContentLoaded', () => {
    loadPets();
    loadAppointments();
    loadPetSelect();
    renderProducts();
    renderCart();

    // Configurar fecha mínima en el input de fecha
    const fechaInput = document.getElementById('fecha-input');
    if (fechaInput) {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        fechaInput.min = now.toISOString().slice(0, 16);
    }
});
