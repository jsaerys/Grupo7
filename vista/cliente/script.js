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
let products = [
    { id: 1, name: 'Cama para Mascotas', price: 49.99, image: 'recursos/pet-bed.png', description: 'Cómoda cama para tu mascota' },
    { id: 2, name: 'Comida para Perros', price: 29.99, image: 'recursos/dog-food.png', description: 'Alimento premium para perros' },
    { id: 3, name: 'Juguete para Gatos', price: 9.99, image: 'recursos/cat-toy.png', description: 'Juguete interactivo para gatos' },
    { id: 4, name: 'Correa para Paseos', price: 19.99, image: 'recursos/leash.png', description: 'Correa resistente y ajustable' }
];

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

document.getElementById('add-appointment-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const petId = document.getElementById('appointment-pet-select').value;
    const service = document.getElementById('appointment-service').value;
    const date = document.getElementById('appointment-date').value;

    try {
        const response = await fetch('../../controlador/citacontroller.php?action=create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `mascota_id=${encodeURIComponent(petId)}&servicio=${encodeURIComponent(service)}&fecha=${encodeURIComponent(date)}`
        });

        const data = await response.json();
        if (data.success) {
            sounds.success.play();
            document.getElementById('add-appointment-form').reset();
            document.getElementById('add-appointment-form').classList.add('hidden');
            loadAppointments();
        } else {
            alert(data.message || 'Error al agendar la cita');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al agendar la cita');
    }
});

// Cargar mascotas
async function loadPets() {
    try {
        const response = await fetch('../../controlador/mascotacontroller.php?action=list');
        const data = await response.json();
        pets = data;
        renderPets();
        updateAppointmentPetSelect();
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar las mascotas');
    }
}

// Cargar citas
async function loadAppointments() {
    try {
        const response = await fetch('../../controlador/citacontroller.php?action=list');
        const data = await response.json();
        appointments = data;
        renderAppointments();
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar las citas');
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
                        <h5 class="card-title mb-1">${appointment.servicio}</h5>
                        <p class="text-muted mb-0">${appointment.mascota_nombre}</p>
                    </div>
                </div>
                <div class="appointment-info mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-clock me-2"></i>
                        <span>${new Date(appointment.fecha).toLocaleString()}</span>
                    </div>
                </div>
                <div class="mt-auto text-end">
                    <button onclick="cancelAppointment(${appointment.id})" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-x-circle me-1"></i>Cancelar Cita
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Actualizar select de mascotas para citas
function updateAppointmentPetSelect() {
    const select = document.getElementById('appointment-pet-select');
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
async function cancelAppointment(id) {
    if (!confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
        return;
    }

    try {
        const response = await fetch(`../../controlador/citacontroller.php?action=delete&id=${id}`);
        const data = await response.json();
        if (data.success) {
            sounds.success.play();
            loadAppointments();
        } else {
            alert(data.message || 'Error al cancelar la cita');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cancelar la cita');
    }
}

// Renderizar productos
function renderProducts() {
    productGrid.innerHTML = products.map(product => `
        <div class="card product-card">
            <img src="${product.image}" class="card-img-top" alt="${product.name}">
            <div class="card-body">
                <h5 class="card-title">${product.name}</h5>
                <p class="card-text">${product.description}</p>
                <p class="card-text"><strong>$${product.price.toFixed(2)}</strong></p>
                <button onclick="addToCart(${product.id})" class="btn btn-primary">
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
    renderProducts();
    renderCart();
});
