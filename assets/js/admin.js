// Función para mostrar notificaciones
function showNotification(title, message, type = 'success') {
    Swal.fire({
        title: title,
        text: message,
        icon: type,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
}

// Función para confirmar acciones
function confirmAction(title, text, callback) {
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

// Función para formatear fechas
function formatDate(date) {
    return new Date(date).toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

// Función para formatear moneda
function formatCurrency(amount) {
    return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// Función para validar formularios
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;

    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// Función para manejar la subida de imágenes
function handleImageUpload(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    
    if (!input || !preview) return;

    input.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
}

// Función para inicializar DataTables
function initDataTable(tableId, options = {}) {
    const defaultOptions = {
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        responsive: true,
        pageLength: 10,
        order: [[0, 'desc']]
    };

    return new DataTable(`#${tableId}`, { ...defaultOptions, ...options });
}

// Función para inicializar gráficos
function initChart(canvasId, type, data, options = {}) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false
    };

    return new Chart(ctx, {
        type: type,
        data: data,
        options: { ...defaultOptions, ...options }
    });
}

// Función para exportar datos a Excel
function exportToExcel(tableId, fileName) {
    const table = document.getElementById(tableId);
    if (!table) return;

    const wb = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
    XLSX.writeFile(wb, `${fileName}.xlsx`);
}

// Función para exportar datos a PDF
function exportToPDF(tableId, fileName) {
    const element = document.getElementById(tableId);
    if (!element) return;

    html2pdf()
        .set({
            margin: 1,
            filename: `${fileName}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        })
        .from(element)
        .save();
}

// Función para manejar errores de AJAX
function handleAjaxError(xhr, status, error) {
    let message = 'Ha ocurrido un error al procesar la solicitud.';
    
    if (xhr.responseJSON && xhr.responseJSON.message) {
        message = xhr.responseJSON.message;
    } else if (error) {
        message = error;
    }

    showNotification('Error', message, 'error');
}

// Función para actualizar el estado de una entidad
function updateEntityStatus(url, id, status, successCallback) {
    $.ajax({
        url: url,
        method: 'POST',
        data: { id: id, status: status },
        success: function(response) {
            if (response.success) {
                showNotification('¡Éxito!', 'Estado actualizado correctamente');
                if (typeof successCallback === 'function') {
                    successCallback(response);
                }
            } else {
                showNotification('Error', 'No se pudo actualizar el estado', 'error');
            }
        },
        error: function(xhr, status, error) {
            handleAjaxError(xhr, status, error);
        }
    });
}

// Función para eliminar una entidad
function deleteEntity(url, id, successCallback) {
    confirmAction(
        '¿Eliminar registro?',
        '¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.',
        function() {
            $.ajax({
                url: url,
                method: 'POST',
                data: { id: id },
                success: function(response) {
                    if (response.success) {
                        showNotification('¡Éxito!', 'Registro eliminado correctamente');
                        if (typeof successCallback === 'function') {
                            successCallback(response);
                        }
                    } else {
                        showNotification('Error', 'No se pudo eliminar el registro', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    handleAjaxError(xhr, status, error);
                }
            });
        }
    );
}

// Función para cargar datos mediante AJAX
function loadData(url, data = {}, successCallback) {
    $.ajax({
        url: url,
        method: 'GET',
        data: data,
        success: function(response) {
            if (response.success && typeof successCallback === 'function') {
                successCallback(response);
            } else {
                showNotification('Error', 'No se pudieron cargar los datos', 'error');
            }
        },
        error: function(xhr, status, error) {
            handleAjaxError(xhr, status, error);
        }
    });
}

// Función para inicializar selectores de fecha y hora
function initDateTimePickers() {
    // Inicializar selectores de fecha
    const datePickers = document.querySelectorAll('.datepicker');
    datePickers.forEach(input => {
        new Datepicker(input, {
            format: 'dd/mm/yyyy',
            language: 'es',
            autohide: true
        });
    });

    // Inicializar selectores de hora
    const timePickers = document.querySelectorAll('.timepicker');
    timePickers.forEach(input => {
        new Timepicker(input, {
            format: 'HH:mm',
            interval: 30
        });
    });
}

// Función para inicializar editores de texto enriquecido
function initRichTextEditors() {
    const editors = document.querySelectorAll('.rich-text-editor');
    editors.forEach(editor => {
        ClassicEditor
            .create(editor)
            .catch(error => {
                console.error(error);
            });
    });
}

// Función para inicializar tooltips y popovers
function initTooltipsAndPopovers() {
    // Inicializar tooltips
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });

    // Inicializar popovers
    const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
    popovers.forEach(popover => {
        new bootstrap.Popover(popover);
    });
}

// Inicializar componentes cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips y popovers
    initTooltipsAndPopovers();

    // Inicializar selectores de fecha y hora si existen
    if (document.querySelector('.datepicker') || document.querySelector('.timepicker')) {
        initDateTimePickers();
    }

    // Inicializar editores de texto enriquecido si existen
    if (document.querySelector('.rich-text-editor')) {
        initRichTextEditors();
    }

    // Inicializar validación de formularios
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
}); 