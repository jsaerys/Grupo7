document.addEventListener('DOMContentLoaded', function() {
    const sidebarLinks = document.querySelectorAll('#sidebar .nav-link');
    const contentArea = document.getElementById('content');

    function loadContent(target) {
        let contentHtml = '';
        if (target === 'mascotas') {
            contentHtml = `
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
                                ${data.map(mascota => `
                                    <tr>
                                        <td>${mascota.id}</td>
                                        <td>${mascota.nombre}</td>
                                        <td>${mascota.especie}</td>
                                        <td>${mascota.raza}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary edit-btn" data-id="${mascota.id}"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${mascota.id}"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        } else if (target === 'clientes') {
            contentHtml = `
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
                                ${data.map(cliente => `
                                    <tr>
                                        <td>${cliente.id}</td>
                                        <td>${cliente.nombre}</td>
                                        <td>${cliente.email}</td>
                                        <td>${cliente.telefono}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary edit-btn" data-id="${cliente.id}"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${cliente.id}"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        } else if (target === 'ventas') {
            contentHtml = `
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Gestión de Ventas</h2>
                        <button class="btn btn-primary" id="add-new-btn"><i class="bi bi-plus-circle me-2"></i>Registrar Nueva Venta</button>
                    </div>
                    <p>Contenido de la sección de Ventas.</p>
                </div>
            `;
        } else if (target === 'citas') {
            contentHtml = `
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Gestión de Citas</h2>
                        <button class="btn btn-primary" id="add-new-btn"><i class="bi bi-plus-circle me-2"></i>Agendar Nueva Cita</button>
                    </div>
                    <p>Contenido de la sección de Citas.</p>
                </div>
            `;
        } else if (target === 'productos') {
            contentHtml = `
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
                                ${data.map(producto => `
                                    <tr>
                                        <td>${producto.id}</td>
                                        <td>${producto.nombre}</td>
                                        <td>$${producto.precio}</td>
                                        <td>${producto.stock}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary edit-btn" data-id="${producto.id}"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${producto.id}"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }
        contentArea.innerHTML = contentHtml;

        // Add event listener for the 'Add New' button within the loaded content
        const addNewBtn = contentArea.querySelector('#add-new-btn');
        if (addNewBtn) {
            addNewBtn.addEventListener('click', function() {
                modalTitle.textContent = 'Añadir Nuevo Elemento';
                modalForm.innerHTML = `
                    <div class="mb-3">
                        <label for="item-name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="item-name" required>
                    </div>
                    <div class="mb-3">
                        <label for="item-description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="item-description" rows="3"></textarea>
                    </div>
                `;
                formModal.show();
            });
        }

        // Add event listeners for edit buttons (example, needs dynamic handling)
        contentArea.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                modalTitle.textContent = 'Editar Elemento';
                modalForm.innerHTML = `
                    <div class="mb-3">
                        <label for="item-id" class="form-label">ID</label>
                        <input type="text" class="form-control" id="item-id" value="1" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="item-name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="item-name" value="Ejemplo" required>
                    </div>
                `;
                formModal.show();
            });
        });
    }

    // Initial load of 'Mascotas' content
    loadContent('mascotas');

    // Event listeners for sidebar navigation
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            sidebarLinks.forEach(item => {
                item.classList.remove('active');
                item.classList.add('text-white');
            });
            this.classList.add('active');
            this.classList.remove('text-white');
            const target = this.getAttribute('data-target');
            loadContent(target);
        });
    });

    // Set 'Mascotas' link as active on initial load
    const mascotasLink = document.querySelector('#sidebar .nav-link[data-target="mascotas"]');
    if (mascotasLink) {
        mascotasLink.classList.add('active');
        mascotasLink.classList.remove('text-white');
    }
});

// Funciones para Mascotas
async function eliminarMascota(id) {
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
            formData.append('action', 'eliminar');

            const response = await fetch('../../controlador/mascotacontroller.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Eliminado!',
                    text: 'La mascota ha sido eliminada.',
                    timer: 1500
                });
                // Recargar la tabla
                cargarMascotas();
            } else {
                throw new Error(data.message || 'Error al eliminar la mascota');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message
        });
    }
}

async function editarMascota(id) {
    try {
        const response = await fetch(`../../controlador/mascotacontroller.php?action=get&id=${id}`);
        const data = await response.json();

        if (data.success) {
            const { value: formValues } = await Swal.fire({
                title: 'Editar Mascota',
                html:
                    `<input id="nombre" class="swal2-input" value="${data.mascota.nombre}" placeholder="Nombre">
                     <input id="especie" class="swal2-input" value="${data.mascota.especie}" placeholder="Especie">
                     <input id="raza" class="swal2-input" value="${data.mascota.raza}" placeholder="Raza">`,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    return {
                        nombre: document.getElementById('nombre').value,
                        especie: document.getElementById('especie').value,
                        raza: document.getElementById('raza').value
                    }
                }
            });

            if (formValues) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('action', 'actualizar');
                Object.entries(formValues).forEach(([key, value]) => {
                    formData.append(key, value);
                });

                const updateResponse = await fetch('../../controlador/mascotacontroller.php', {
                    method: 'POST',
                    body: formData
                });

                const updateData = await updateResponse.json();

                if (updateData.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado!',
                        text: 'La mascota ha sido actualizada.',
                        timer: 1500
                    });
                    cargarMascotas();
                } else {
                    throw new Error(updateData.message);
                }
            }
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message
        });
    }
}

// Funciones para Citas
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
            formData.append('action', 'delete');

            const response = await fetch('../../controlador/citacontroller.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Eliminado!',
                    text: 'La cita ha sido eliminada.',
                    timer: 1500
                });
                cargarCitas();
            } else {
                throw new Error(data.message || 'Error al eliminar la cita');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message
        });
    }
}

async function editarCita(id) {
    try {
        const response = await fetch(`../../controlador/citacontroller.php?action=get&id=${id}`);
        const data = await response.json();

        if (data.success) {
            const { value: formValues } = await Swal.fire({
                title: 'Editar Cita',
                html:
                    `<input type="date" id="fecha" class="swal2-input" value="${data.cita.fecha}">
                     <input type="time" id="hora" class="swal2-input" value="${data.cita.hora}">
                     <select id="servicio" class="swal2-input">
                         <option value="consulta" ${data.cita.servicio === 'consulta' ? 'selected' : ''}>Consulta</option>
                         <option value="vacunacion" ${data.cita.servicio === 'vacunacion' ? 'selected' : ''}>Vacunación</option>
                         <option value="peluqueria" ${data.cita.servicio === 'peluqueria' ? 'selected' : ''}>Peluquería</option>
                         <option value="desparasitacion" ${data.cita.servicio === 'desparasitacion' ? 'selected' : ''}>Desparasitación</option>
                     </select>
                     <textarea id="notas" class="swal2-textarea" placeholder="Notas">${data.cita.notas || ''}</textarea>`,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    return {
                        fecha: document.getElementById('fecha').value,
                        hora: document.getElementById('hora').value,
                        servicio: document.getElementById('servicio').value,
                        notas: document.getElementById('notas').value
                    }
                }
            });

            if (formValues) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('action', 'update');
                Object.entries(formValues).forEach(([key, value]) => {
                    formData.append(key, value);
                });

                const updateResponse = await fetch('../../controlador/citacontroller.php', {
                    method: 'POST',
                    body: formData
                });

                const updateData = await updateResponse.json();

                if (updateData.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado!',
                        text: 'La cita ha sido actualizada.',
                        timer: 1500
                    });
                    cargarCitas();
                } else {
                    throw new Error(updateData.message);
                }
            }
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message
        });
    }
}

// Funciones para Clientes
async function eliminarCliente(id) {
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
            formData.append('action', 'eliminar');

            const response = await fetch('../../controlador/usuariocontroller.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Eliminado!',
                    text: 'El cliente ha sido eliminado.',
                    timer: 1500
                });
                cargarClientes();
            } else {
                throw new Error(data.message || 'Error al eliminar el cliente');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message
        });
    }
}

async function editarCliente(id) {
    try {
        const response = await fetch(`../../controlador/usuariocontroller.php?action=get&id=${id}`);
        const data = await response.json();

        if (data.success) {
            const { value: formValues } = await Swal.fire({
                title: 'Editar Cliente',
                html:
                    `<input id="nombre" class="swal2-input" value="${data.usuario.nombre}" placeholder="Nombre">
                     <input id="email" class="swal2-input" value="${data.usuario.email}" placeholder="Email">
                     <input id="telefono" class="swal2-input" value="${data.usuario.telefono}" placeholder="Teléfono">`,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    return {
                        nombre: document.getElementById('nombre').value,
                        email: document.getElementById('email').value,
                        telefono: document.getElementById('telefono').value
                    }
                }
            });

            if (formValues) {
                const formData = new FormData();
                formData.append('id', id);
                formData.append('action', 'actualizar');
                Object.entries(formValues).forEach(([key, value]) => {
                    formData.append(key, value);
                });

                const updateResponse = await fetch('../../controlador/usuariocontroller.php', {
                    method: 'POST',
                    body: formData
                });

                const updateData = await updateResponse.json();

                if (updateData.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado!',
                        text: 'El cliente ha sido actualizado.',
                        timer: 1500
                    });
                    cargarClientes();
                } else {
                    throw new Error(updateData.message);
                }
            }
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message
        });
    }
}

// Funciones de carga
async function cargarMascotas() {
    try {
        const response = await fetch('../../controlador/mascotacontroller.php?action=listar');
        const data = await response.json();
        
        if (data.success) {
            const tabla = document.querySelector('#mascotasTable tbody');
            if (tabla) {
                tabla.innerHTML = data.mascotas.map(mascota => `
                    <tr>
                        <td>${mascota.id}</td>
                        <td>${mascota.nombre}</td>
                        <td>${mascota.especie}</td>
                        <td>${mascota.raza}</td>
                        <td>${mascota.dueno}</td>
                        <td>
                            <button onclick="editarMascota(${mascota.id})" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button onclick="eliminarMascota(${mascota.id})" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            }
        }
    } catch (error) {
        console.error('Error al cargar mascotas:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar las mascotas'
        });
    }
}

async function cargarCitas() {
    try {
        const response = await fetch('../../controlador/citacontroller.php?action=listar');
        const data = await response.json();
        
        if (data.success) {
            const tabla = document.querySelector('#citasTable tbody');
            if (tabla) {
                tabla.innerHTML = data.citas.map(cita => `
                    <tr>
                        <td>${cita.id}</td>
                        <td>${cita.fecha}</td>
                        <td>${cita.hora}</td>
                        <td>${cita.servicio}</td>
                        <td>${cita.mascota}</td>
                        <td>${cita.usuario}</td>
                        <td>${cita.notas || '...'}</td>
                        <td>${cita.fecha_registro}</td>
                        <td>
                            <button onclick="editarCita(${cita.id})" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button onclick="eliminarCita(${cita.id})" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            }
        }
    } catch (error) {
        console.error('Error al cargar citas:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar las citas'
        });
    }
}

async function cargarClientes() {
    try {
        const response = await fetch('../../controlador/usuariocontroller.php?action=listar');
        const data = await response.json();
        
        if (data.success) {
            const tabla = document.querySelector('#clientesTable tbody');
            if (tabla) {
                tabla.innerHTML = data.usuarios.map(usuario => `
                    <tr>
                        <td>${usuario.id}</td>
                        <td>${usuario.nombre}</td>
                        <td>${usuario.email}</td>
                        <td>${usuario.telefono}</td>
                        <td>
                            <button onclick="editarCliente(${usuario.id})" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button onclick="eliminarCliente(${usuario.id})" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            }
        }
    } catch (error) {
        console.error('Error al cargar clientes:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar los clientes'
        });
    }
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    // Cargar datos iniciales según la página activa
    const currentPath = window.location.pathname;
    if (currentPath.includes('mascotas.php')) {
        cargarMascotas();
    } else if (currentPath.includes('citas.php')) {
        cargarCitas();
    } else if (currentPath.includes('clientes.php')) {
        cargarClientes();
    }
});