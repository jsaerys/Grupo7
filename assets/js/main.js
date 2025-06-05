// Función para mostrar notificaciones con SweetAlert2
function showNotification(title, text, icon = 'success') {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
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

// Función para agregar productos al carrito
function addToCart(productId, quantity = 1) {
    $.ajax({
        url: '/proyectos/colaborativo/Grupo7/index.php?page=cart&action=add',
        method: 'POST',
        data: {
            product_id: productId,
            quantity: quantity
        },
        success: function(response) {
            if (response.success) {
                showNotification('¡Éxito!', 'Producto agregado al carrito');
                updateCartBadge(response.cartCount);
            } else {
                showNotification('Error', response.message, 'error');
            }
        },
        error: function() {
            showNotification('Error', 'No se pudo agregar el producto al carrito', 'error');
        }
    });
}

// Función para actualizar el contador del carrito
function updateCartBadge(count) {
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'block' : 'none';
    }
}

// Función para actualizar cantidad en el carrito
function updateCartQuantity(productId, quantity) {
    $.ajax({
        url: '/proyectos/colaborativo/Grupo7/index.php?page=cart&action=update',
        method: 'POST',
        data: {
            product_id: productId,
            quantity: quantity
        },
        success: function(response) {
            if (response.success) {
                updateCartTotal(response.total);
                showNotification('¡Éxito!', 'Carrito actualizado');
            } else {
                showNotification('Error', response.message, 'error');
            }
        },
        error: function() {
            showNotification('Error', 'No se pudo actualizar el carrito', 'error');
        }
    });
}

// Función para eliminar producto del carrito
function removeFromCart(productId) {
    confirmAction('¿Eliminar producto?', '¿Estás seguro de que deseas eliminar este producto del carrito?', function() {
        $.ajax({
            url: '/proyectos/colaborativo/Grupo7/index.php?page=cart&action=remove',
            method: 'POST',
            data: { product_id: productId },
            success: function(response) {
                if (response.success) {
                    $(`#cart-item-${productId}`).remove();
                    updateCartTotal(response.total);
                    updateCartBadge(response.cartCount);
                    showNotification('¡Éxito!', 'Producto eliminado del carrito');
                } else {
                    showNotification('Error', response.message, 'error');
                }
            },
            error: function() {
                showNotification('Error', 'No se pudo eliminar el producto', 'error');
            }
        });
    });
}

// Función para actualizar el total del carrito
function updateCartTotal(total) {
    $('#cart-total').text(`$${total.toFixed(2)}`);
}

// Inicializar tooltips de Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Inicializar validación de formularios
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Cerrar alertas automáticamente después de 5 segundos
    var alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            var closeButton = alert.querySelector('.btn-close');
            if (closeButton) {
                closeButton.click();
            }
        }, 5000);
    });
});

// Función para confirmar acciones
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Función para formatear números como moneda
function formatCurrency(number) {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP'
    }).format(number);
}

// Función para validar contraseñas coincidentes
function validatePasswords() {
    var password = document.getElementById('password');
    var confirmPassword = document.getElementById('confirm_password');
    
    if (password && confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Las contraseñas no coinciden');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });
    }
}

// Función para previsualizar imágenes antes de subirlas
function previewImage(input, previewElement) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            var preview = document.getElementById(previewElement);
            if (preview) {
                preview.src = e.target.result;
            }
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Inicializar funcionalidades cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    validatePasswords();
}); 