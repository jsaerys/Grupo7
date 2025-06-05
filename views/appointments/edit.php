<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title mb-4">Editar Cita</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="/proyectos/colaborativo/Grupo7/index.php?page=appointments&action=edit&id=<?php echo $appointment['id']; ?>" 
                      method="POST" 
                      class="needs-validation" 
                      novalidate>
                    
                    <div class="mb-3">
                        <label class="form-label">Mascota</label>
                        <input type="text" 
                               class="form-control" 
                               value="<?php 
                                    foreach ($pets as $pet) {
                                        if ($pet['id'] == $appointment['mascota_id']) {
                                            echo htmlspecialchars($pet['nombre'] . ' (' . $pet['especie'] . ' - ' . $pet['raza'] . ')');
                                            break;
                                        }
                                    }
                               ?>" 
                               disabled>
                    </div>

                    <div class="mb-3">
                        <label for="servicio" class="form-label">Servicio</label>
                        <select class="form-select" id="servicio" name="servicio" required>
                            <option value="">Selecciona un servicio</option>
                            <?php
                            $servicios = [
                                'Consulta General',
                                'Vacunación',
                                'Desparasitación',
                                'Control de Rutina',
                                'Emergencia',
                                'Cirugía',
                                'Limpieza Dental',
                                'Peluquería'
                            ];
                            foreach ($servicios as $servicio): ?>
                                <option value="<?php echo $servicio; ?>" 
                                        <?php echo $servicio === $appointment['servicio'] ? 'selected' : ''; ?>>
                                    <?php echo $servicio; ?>
                                </option>
                            <?php endforeach; ?>
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
                                   value="<?php echo $appointment['fecha']; ?>"
                                   min="<?php echo date('Y-m-d'); ?>" 
                                   required>
                            <div class="invalid-feedback">
                                Por favor selecciona una fecha válida.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="hora" class="form-label">Hora</label>
                            <select class="form-select" id="hora" name="hora" required>
                                <option value="">Selecciona una hora</option>
                                <?php 
                                // La hora actual se agregará mediante JavaScript
                                ?>
                            </select>
                            <div class="invalid-feedback">
                                Por favor selecciona una hora disponible.
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="pendiente" <?php echo $appointment['estado'] === 'pendiente' ? 'selected' : ''; ?>>
                                Pendiente
                            </option>
                            <option value="confirmada" <?php echo $appointment['estado'] === 'confirmada' ? 'selected' : ''; ?>>
                                Confirmada
                            </option>
                            <option value="cancelada" <?php echo $appointment['estado'] === 'cancelada' ? 'selected' : ''; ?>>
                                Cancelada
                            </option>
                        </select>
                        <div class="invalid-feedback">
                            Por favor selecciona un estado.
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
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
// Variable para almacenar la hora actual de la cita
const currentHour = '<?php echo date('H:i', strtotime($appointment['hora'])); ?>';

document.getElementById('fecha').addEventListener('change', loadAvailableHours);

// Cargar horas disponibles al cargar la página
window.addEventListener('load', loadAvailableHours);

function loadAvailableHours() {
    const fecha = document.getElementById('fecha').value;
    const horaSelect = document.getElementById('hora');
    
    if (fecha) {
        $.ajax({
            url: '/proyectos/colaborativo/Grupo7/index.php?page=appointments&action=getAvailableHours',
            method: 'GET',
            data: { date: fecha },
            success: function(response) {
                if (response.success) {
                    horaSelect.innerHTML = '<option value="">Selecciona una hora</option>';
                    
                    // Agregar todas las horas disponibles y la hora actual
                    const hours = [...response.hours];
                    if (!hours.includes(currentHour) && fecha === '<?php echo $appointment['fecha']; ?>') {
                        hours.push(currentHour);
                        hours.sort();
                    }
                    
                    hours.forEach(function(hora) {
                        const option = document.createElement('option');
                        option.value = hora;
                        option.textContent = hora;
                        if (hora === currentHour) {
                            option.selected = true;
                        }
                        horaSelect.appendChild(option);
                    });
                }
            },
            error: function() {
                showNotification('Error', 'No se pudieron cargar los horarios disponibles', 'error');
            }
        });
    } else {
        horaSelect.innerHTML = '<option value="">Selecciona una hora</option>';
    }
}
</script> 