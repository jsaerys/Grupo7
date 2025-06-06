document.addEventListener('DOMContentLoaded', () => {
    const BASE_URL = '../controlador/dashboardController.php';
    let currentSection = 'mascotas';
    let editingItemId = null;
    let currentData = {}; // Para almacenar los datos actuales de la sección

    const sectionConfig = {
        mascotas: {
            title: 'Gestión de Mascotas',
            headers: ['ID', 'Nombre', 'Especie', 'Raza', 'Edad', 'ID Dueño', 'Acciones'],
            fields: {
                nombre: { label: 'Nombre', type: 'text' },
                especie: { label: 'Especie', type: 'text' },
                raza: { label: 'Raza', type: 'text' },
                edad: { label: 'Edad', type: 'number' },
                id_cliente: { label: 'ID Dueño', type: 'number' },
            }
        },
        clientes: {
            title: 'Gestión de Clientes',
            headers: ['ID', 'Nombre', 'Email', 'Teléfono', 'Acciones'],
            fields: {
                nombre: { label: 'Nombre', type: 'text' },
                email: { label: 'Email', type: 'email' },
                telefono: { label: 'Teléfono', type: 'tel' },
            }
        },
        ventas: {
            title: 'Registro de Ventas',
            headers: ['ID', 'ID Producto', 'ID Cliente', 'Cantidad', 'Fecha', 'Acciones'],
            fields: {
                id_producto: { label: 'ID Producto', type: 'number' },
                id_cliente: { label: 'ID Cliente', type: 'number' },
                cantidad: { label: 'Cantidad', type: 'number' },
                fecha: { label: 'Fecha', type: 'date' },
            }
        },
        citas: {
            title: 'Gestión de Citas',
            headers: ['ID', 'ID Mascota', 'Fecha', 'Hora', 'Motivo', 'Acciones'],
            fields: {
                id_mascota: { label: 'ID Mascota', type: 'number' },
                fecha: { label: 'Fecha', type: 'date' },
                hora: { label: 'Hora', type: 'time' },
                motivo: { label: 'Motivo', type: 'text' },
            }
        },
        productos: {
            title: 'Gestión de Productos',
            headers: ['ID', 'Nombre', 'Precio (€)', 'Stock', 'Acciones'],
            fields: {
                nombre: { label: 'Nombre', type: 'text' },
                precio: { label: 'Precio', type: 'number', step: '0.01' },
                stock: { label: 'Stock', type: 'number' },
            }
        }
    };

    const contentEl = document.getElementById('content');
    const sidebarLinks = document.querySelectorAll('#sidebar .nav-link');
    const modalEl = document.getElementById('form-modal');
    const formModal = new bootstrap.Modal(modalEl);
    const modalTitle = document.getElementById('modal-title');
    const modalForm = document.getElementById('modal-form');

    // --- FETCH DATA FROM BACKEND ---
    async function fetchData(entity) {
        try {
            const response = await fetch(`${BASE_URL}?entity=${entity}&action=read`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            currentData[entity] = data; // Almacenar los datos obtenidos
            return data;
        } catch (error) {
            console.error(`Error fetching ${entity}:`, error);
            return [];
        }
    }

    // --- CRUD OPERATIONS ---
    async function sendData(entity, action, itemData) {
        try {
            const response = await fetch(BASE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ entity, action, data: itemData }),
            });
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const result = await response.json();
            if (result.message) {
                Swal.fire('Éxito', result.message, 'success');
            } else if (result.error) {
                Swal.fire('Error', result.error, 'error');
            }
            return result;
        } catch (error) {
            console.error(`Error ${action}ing ${entity}:`, error);
            Swal.fire('Error', `No se pudo ${action} ${entity}.`, 'error');
            return { error: `No se pudo ${action} ${entity}.` };
        }
    }

    // --- RENDER FUNCTIONS ---
    async function render() {
        const config = sectionConfig[currentSection];
        const items = await fetchData(currentSection); // Obtener datos del backend
        const itemFields = Object.keys(config.fields);

        const tableRows = items.map(item => {
            const rowData = itemFields.map(field => `<td>${item[field] !== undefined ? item[field] : ''}</td>`).join('');
            return `
                <tr>
                    <td class="fw-bold">${item.id}</td>
                    ${rowData}
                    <td>
                        <button class="btn btn-sm btn-outline-primary action-btn edit-btn" data-id="${item.id}" title="Editar"><i class="bi bi-pencil-square"></i></button>
                        <button class="btn btn-sm btn-outline-danger action-btn delete-btn" data-id="${item.id}" title="Eliminar"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            `;
        }).join('');

        contentEl.innerHTML = `
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <h2 class="h4 mb-0">${config.title}</h2>
                        <button class="btn btn-primary add-btn">
                            <i class="bi bi-plus-circle me-2"></i>Añadir Nuevo
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    ${config.headers.map(h => `<th>${h}</th>`).join('')}
                                </tr>
                            </thead>
                            <tbody>
                                ${tableRows}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
        addEventListenersToButtons();
    }

    // --- MODAL & FORM HANDLING ---
    function openModal(title, item = {}) {
        modalTitle.textContent = title;
        const config = sectionConfig[currentSection];
        const fieldsHtml = Object.keys(config.fields).map(key => {
            const field = config.fields[key];
            const value = item[key] !== undefined ? item[key] : '';
            const stepAttr = field.step ? `step="${field.step}"` : '';
            const minAttr = field.type === 'number' ? 'min="0"' : '';
            return `
                <div class="mb-3">
                    <label for="field-${key}" class="form-label">${field.label}</label>
                    <input type="${field.type}" id="field-${key}" name="${key}" value="${value}" ${stepAttr} ${minAttr} class="form-control" required>
                </div>
            `;
        }).join('');
        
        modalForm.innerHTML = fieldsHtml;
        formModal.show();
    }

    function closeModal() {
        formModal.hide();
    }
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        const formData = new FormData(modalForm);
        const itemData = {};
        const config = sectionConfig[currentSection];

        for (let [key, value] of formData.entries()) {
             if (config.fields[key] && config.fields[key].type === 'number') {
                itemData[key] = parseFloat(value);
            } else {
                itemData[key] = value;
            }
        }

        let result;
        if (editingItemId) {
            itemData.id = editingItemId;
            result = await sendData(currentSection, 'update', itemData);
        } else {
            result = await sendData(currentSection, 'create', itemData);
        }
        
        if (result && !result.error) {
            closeModal();
            render();
        }
    }

    // --- EVENT LISTENERS ---
    sidebarLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            sidebarLinks.forEach(l => l.classList.remove('active', 'text-white'));
            link.classList.add('active');
            currentSection = e.currentTarget.dataset.target;
            render();
        });
    });

    // Cargar la sección inicial al cargar la página
    render();

    function addEventListenersToButtons() {
        document.querySelector('.add-btn').addEventListener('click', () => {
            editingItemId = null;
            openModal(`Añadir a ${currentSection}`);
        });

        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                editingItemId = parseInt(e.currentTarget.dataset.id, 10);
                    const item = currentData[currentSection].find(i => i.id === editingItemId);
                if (item) {
                    openModal(`Editar en ${currentSection}`, item);
                }
            });
        });

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const idToDelete = parseInt(e.currentTarget.dataset.id, 10);
                if (confirm('¿Estás seguro de que quieres eliminar este elemento?')) {
                    data[currentSection] = data[currentSection].filter(item => item.id !== idToDelete);
                    render();
                }
            });
        });
    }

    sidebarLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetSection = link.dataset.target;
            if (currentSection !== targetSection) {
                sidebarLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                currentSection = targetSection;
                render();
            }
        });
    });

    modalEl.addEventListener('hidden.bs.modal', () => {
        editingItemId = null;
        modalForm.reset();
        modalForm.innerHTML = '';
    });

    modalForm.addEventListener('submit', handleFormSubmit);

    // --- INITIAL RENDER ---
    render();
});