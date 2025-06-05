<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gestión de Citas</h1>
            <div class="d-flex gap-2">
                <select id="statusFilter" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="confirmada">Confirmadas</option>
                    <option value="cancelada">Canceladas</option>
                </select>
                <input type="date" 
                       id="dateFilter" 
                       class="form-control" 
                       value="<?php echo date('Y-m-d'); ?>">
                <div class="input-group">
                    <input type="text" 
                           id="searchInput" 
                           class="form-control" 
                           placeholder="Buscar...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if (empty($appointments)): ?>
                    <p class="text-muted">No hay citas registradas.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover" id="appointmentsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Mascota</th>
                                    <th>Servicio</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointments as $appointment): ?>
                                    <tr data-status="<?php echo $appointment['estado']; ?>" 
                                        data-date="<?php echo $appointment['fecha']; ?>">
                                        <td><?php echo $appointment['id']; ?></td>
                                        <td><?php echo htmlspecialchars($appointment['cliente_nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['mascota_nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['servicio']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($appointment['fecha'])); ?></td>
                                        <td><?php echo date('H:i', strtotime($appointment['hora'])); ?></td>
                                        <td>
                                            <select class="form-select form-select-sm status-select" 
                                                    onchange="updateAppointmentStatus(<?php echo $appointment['id']; ?>, this.value)">
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
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" 
                                                        onclick="viewAppointmentDetails(<?php echo $appointment['id']; ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" 
                                                        onclick="editAppointment(<?php echo $appointment['id']; ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" 
                                                        onclick="deleteAppointment(<?php echo $appointment['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles -->
<div class="modal fade" id="appointmentDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles de la Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="appointmentDetails"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Filtrado combinado
function filterAppointments() {
    const statusFilter = document.getElementById('statusFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.getElementById('appointmentsTable').getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const status = row.getAttribute('data-status');
        const date = row.getAttribute('data-date');
        const cells = row.getElementsByTagName('td');
        let textMatch = false;

        // Búsqueda de texto
        for (let j = 0; j < cells.length - 1; j++) {
            if (cells[j].textContent.toLowerCase().indexOf(searchText) > -1) {
                textMatch = true;
                break;
            }
        }

        // Aplicar filtros combinados
        const statusMatch = !statusFilter || status === statusFilter;
        const dateMatch = !dateFilter || date === dateFilter;

        row.style.display = (statusMatch && dateMatch && textMatch) ? '' : 'none';
    }
}

// Event listeners para filtros
document.getElementById('statusFilter').addEventListener('change', filterAppointments);
document.getElementById('dateFilter').addEventListener('change', filterAppointments);
document.getElementById('searchInput').addEventListener('keyup', filterAppointments);

function updateAppointmentStatus(appointmentId, newStatus) {
    $.ajax({
        url: '/proyectos/colaborativo/Grupo7/index.php?page=admin&action=updateAppointmentStatus',
        method: 'POST',
        data: {
            appointment_id: appointmentId,
            status: newStatus
        },
        success: function(response) {
            if (response.success) {
                showNotification('¡Éxito!', 'Estado de la cita actualizado');
                // Actualizar el atributo data-status de la fila
                const row = document.querySelector(`tr[data-appointment-id="${appointmentId}"]`);
                if (row) {
                    row.setAttribute('data-status', newStatus);
                }
            } else {
                showNotification('Error', 'No se pudo actualizar el estado de la cita', 'error');
            }
        },
        error: function() {
            showNotification('Error', 'No se pudo actualizar el estado de la cita', 'error');
        }
    });
}

function viewAppointmentDetails(appointmentId) {
    $.ajax({
        url: '/proyectos/colaborativo/Grupo7/index.php?page=admin&action=getAppointmentDetails',
        method: 'GET',
        data: { appointment_id: appointmentId },
        success: function(response) {
            if (response.success) {
                const appointment = response.appointment;
                let html = `
                    <dl class="row">
                        <dt class="col-sm-4">Cliente:</dt>
                        <dd class="col-sm-8">${appointment.cliente_nombre}</dd>
                        
                        <dt class="col-sm-4">Mascota:</dt>
                        <dd class="col-sm-8">${appointment.mascota_nombre}</dd>
                        
                        <dt class="col-sm-4">Servicio:</dt>
                        <dd class="col-sm-8">${appointment.servicio}</dd>
                        
                        <dt class="col-sm-4">Fecha:</dt>
                        <dd class="col-sm-8">${appointment.fecha}</dd>
                        
                        <dt class="col-sm-4">Hora:</dt>
                        <dd class="col-sm-8">${appointment.hora}</dd>
                        
                        <dt class="col-sm-4">Estado:</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-${
                                appointment.estado === 'pendiente' ? 'warning' : 
                                (appointment.estado === 'confirmada' ? 'success' : 'danger')
                            }">${appointment.estado}</span>
                        </dd>
                        
                        <dt class="col-sm-4">Notas:</dt>
                        <dd class="col-sm-8">${appointment.notas || 'Sin notas'}</dd>
                    </dl>
                `;
                $('#appointmentDetails').html(html);
                $('#appointmentDetailsModal').modal('show');
            }
        }
    });
}

function editAppointment(appointmentId) {
    window.location.href = `/proyectos/colaborativo/Grupo7/index.php?page=appointments&action=edit&id=${appointmentId}`;
}

function deleteAppointment(appointmentId) {
    confirmAction(
        '¿Eliminar cita?',
        '¿Estás seguro de que deseas eliminar esta cita? Esta acción no se puede deshacer.',
        function() {
            $.ajax({
                url: '/proyectos/colaborativo/Grupo7/index.php?page=admin&action=deleteAppointment',
                method: 'POST',
                data: { appointment_id: appointmentId },
                success: function(response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        showNotification('Error', 'No se pudo eliminar la cita', 'error');
                    }
                },
                error: function() {
                    showNotification('Error', 'No se pudo eliminar la cita', 'error');
                }
            });
        }
    );
}
</script> 