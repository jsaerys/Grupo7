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