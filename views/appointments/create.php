<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title mb-4">Agendar Nueva Cita</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="/proyectos/colaborativo/Grupo7/index.php?page=appointments&action=create" 
                      method="POST" 
                      class="needs-validation" 
                      novalidate>
                    
                    <div class="mb-3">
                        <label for="mascota_id" class="form-label">Mascota</label>
                        <select class="form-select" id="mascota_id" name="mascota_id" required>
                            <option value="">Selecciona una mascota</option>
                            <?php foreach ($pets as $pet): ?>
                                <option value="<?php echo $pet['id']; ?>">
                                    <?php echo htmlspecialchars($pet['nombre']); ?> 
                                    (<?php echo htmlspecialchars($pet['especie']); ?> - 
                                    <?php echo htmlspecialchars($pet['raza']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            Por favor selecciona una mascota.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="servicio" class="form-label">Servicio</label>
                        <select class="form-select" id="servicio" name="servicio" required>
                            <option value="">Selecciona un servicio</option>
                            <option value="Consulta General">Consulta General</option>
                            <option value="Vacunación">Vacunación</option>
                            <option value="Desparasitación">Desparasitación</option>
                            <option value="Control de Rutina">Control de Rutina</option>
                            <option value="Emergencia">Emergencia</option>
                            <option value="Cirugía">Cirugía</option>
                            <option value="Limpieza Dental">Limpieza Dental</option>
                            <option value="Peluquería">Peluquería</option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor selecciona un servicio.
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="fecha" 
                                   name="fecha" 
                                   min="<?php echo date('Y-m-d'); ?>" 
                                   required>
                            <div class="invalid-feedback">
                                Por favor selecciona una fecha válida.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="hora" class="form-label">Hora</label>
                            <select class="form-select" id="hora" name="hora" required disabled>
                                <option value="">Selecciona una hora</option>
                            </select>
                            <div class="invalid-feedback">
                                Por favor selecciona una hora disponible.
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-calendar-check me-2"></i>Agendar Cita
                        </button>
                        <a href="/proyectos/colaborativo/Grupo7/index.php?page=appointments" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver a Citas
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('fecha').addEventListener('change', function() {
    const fecha = this.value;
    const horaSelect = document.getElementById('hora');
    
    if (fecha) {
        // Obtener horas disponibles
        $.ajax({
            url: '/proyectos/colaborativo/Grupo7/index.php?page=appointments&action=getAvailableHours',
            method: 'GET',
            data: { date: fecha },
            success: function(response) {
                if (response.success) {
                    horaSelect.innerHTML = '<option value="">Selecciona una hora</option>';
                    response.hours.forEach(function(hora) {
                        const option = document.createElement('option');
                        option.value = hora;
                        option.textContent = hora;
                        horaSelect.appendChild(option);
                    });
                    horaSelect.disabled = false;
                }
            },
            error: function() {
                showNotification('Error', 'No se pudieron cargar los horarios disponibles', 'error');
            }
        });
    } else {
        horaSelect.innerHTML = '<option value="">Selecciona una hora</option>';
        horaSelect.disabled = true;
    }
});

// Establecer fecha mínima
document.getElementById('fecha').min = new Date().toISOString().split('T')[0];
</script> 