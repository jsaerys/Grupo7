<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title mb-4">Editar Usuario</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="/proyectos/colaborativo/Grupo7/index.php?page=admin&action=editUser&id=<?php echo $user['id']; ?>" 
                      method="POST" 
                      class="needs-validation" 
                      novalidate>
                    
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo</label>
                        <input type="text" 
                               class="form-control" 
                               id="nombre" 
                               name="nombre" 
                               value="<?php echo htmlspecialchars($user['nombre']); ?>" 
                               required>
                        <div class="invalid-feedback">
                            Por favor ingresa el nombre completo.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               value="<?php echo htmlspecialchars($user['email']); ?>" 
                               required>
                        <div class="invalid-feedback">
                            Por favor ingresa un correo electrónico válido.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" 
                               class="form-control" 
                               id="telefono" 
                               name="telefono" 
                               value="<?php echo htmlspecialchars($user['telefono']); ?>" 
                               required>
                        <div class="invalid-feedback">
                            Por favor ingresa un número de teléfono válido.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <textarea class="form-control" 
                                  id="direccion" 
                                  name="direccion" 
                                  rows="3"><?php echo htmlspecialchars($user['direccion']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol</label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="cliente" <?php echo $user['rol'] === 'cliente' ? 'selected' : ''; ?>>
                                Cliente
                            </option>
                            <option value="admin" <?php echo $user['rol'] === 'admin' ? 'selected' : ''; ?>>
                                Administrador
                            </option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor selecciona un rol.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="activo" class="form-label">Estado de la Cuenta</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="activo" 
                                   name="activo" 
                                   value="1" 
                                   <?php echo $user['activo'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="activo">
                                Cuenta Activa
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Nueva Contraseña (dejar en blanco para mantener la actual)</label>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               minlength="6">
                        <div class="invalid-feedback">
                            La contraseña debe tener al menos 6 caracteres.
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                        <a href="/proyectos/colaborativo/Grupo7/index.php?page=admin&action=users" 
                           class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver a Usuarios
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Validación del formulario
(function() {
    'use strict';
    
    const form = document.querySelector('.needs-validation');
    
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    });
})();

// Validación de teléfono
document.getElementById('telefono').addEventListener('input', function(e) {
    const value = e.target.value;
    // Permitir solo números y algunos caracteres especiales
    e.target.value = value.replace(/[^\d\s\-\+\(\)]/g, '');
});

// Confirmación antes de cambiar el rol a admin
document.getElementById('rol').addEventListener('change', function(e) {
    if (e.target.value === 'admin' && !confirm('¿Estás seguro de que deseas dar permisos de administrador a este usuario?')) {
        e.target.value = 'cliente';
    }
});
</script> 