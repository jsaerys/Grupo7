<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gestión de Usuarios</h1>
            <div class="d-flex gap-2">
                <div class="input-group">
                    <input type="text" 
                           id="searchInput" 
                           class="form-control" 
                           placeholder="Buscar usuarios...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if (empty($users)): ?>
                    <p class="text-muted">No hay usuarios registrados.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover" id="usersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Rol</th>
                                    <th>Mascotas</th>
                                    <th>Citas</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['telefono']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $user['rol'] === 'admin' ? 'danger' : 'primary'; ?>">
                                                <?php echo ucfirst($user['rol']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    onclick="viewUserPets(<?php echo $user['id']; ?>)">
                                                Ver Mascotas
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" 
                                                    onclick="viewUserAppointments(<?php echo $user['id']; ?>)">
                                                Ver Citas
                                            </button>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       id="userStatus_<?php echo $user['id']; ?>"
                                                       <?php echo $user['activo'] ? 'checked' : ''; ?>
                                                       onchange="toggleUserStatus(<?php echo $user['id']; ?>)">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-warning" 
                                                        onclick="editUser(<?php echo $user['id']; ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                                    <button class="btn btn-sm btn-danger" 
                                                            onclick="deleteUser(<?php echo $user['id']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
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

<!-- Modal para Mascotas -->
<div class="modal fade" id="petsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mascotas del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="petsList"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Citas -->
<div class="modal fade" id="appointmentsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Citas del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="appointmentsList"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Búsqueda en tiempo real
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchText = this.value.toLowerCase();
    const table = document.getElementById('usersTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;

        for (let j = 0; j < cells.length - 1; j++) {
            const cell = cells[j];
            if (cell.textContent.toLowerCase().indexOf(searchText) > -1) {
                found = true;
                break;
            }
        }

        row.style.display = found ? '' : 'none';
    }
});

function viewUserPets(userId) {
    $.ajax({
        url: '/proyectos/colaborativo/Grupo7/index.php?page=admin&action=getUserPets',
        method: 'GET',
        data: { user_id: userId },
        success: function(response) {
            if (response.success) {
                let html = '';
                if (response.pets.length > 0) {
                    html = '<div class="table-responsive"><table class="table">';
                    html += '<thead><tr><th>Nombre</th><th>Especie</th><th>Raza</th><th>Edad</th></tr></thead>';
                    html += '<tbody>';
                    response.pets.forEach(function(pet) {
                        html += `<tr>
                            <td>${pet.nombre}</td>
                            <td>${pet.especie}</td>
                            <td>${pet.raza}</td>
                            <td>${pet.edad} años</td>
                        </tr>`;
                    });
                    html += '</tbody></table></div>';
                } else {
                    html = '<p class="text-muted">Este usuario no tiene mascotas registradas.</p>';
                }
                $('#petsList').html(html);
                $('#petsModal').modal('show');
            }
        }
    });
}

function viewUserAppointments(userId) {
    $.ajax({
        url: '/proyectos/colaborativo/Grupo7/index.php?page=admin&action=getUserAppointments',
        method: 'GET',
        data: { user_id: userId },
        success: function(response) {
            if (response.success) {
                let html = '';
                if (response.appointments.length > 0) {
                    html = '<div class="table-responsive"><table class="table">';
                    html += '<thead><tr><th>Mascota</th><th>Servicio</th><th>Fecha</th><th>Estado</th></tr></thead>';
                    html += '<tbody>';
                    response.appointments.forEach(function(appointment) {
                        html += `<tr>
                            <td>${appointment.mascota_nombre}</td>
                            <td>${appointment.servicio}</td>
                            <td>${appointment.fecha} ${appointment.hora}</td>
                            <td><span class="badge bg-${
                                appointment.estado === 'pendiente' ? 'warning' : 
                                (appointment.estado === 'confirmada' ? 'success' : 'danger')
                            }">${appointment.estado}</span></td>
                        </tr>`;
                    });
                    html += '</tbody></table></div>';
                } else {
                    html = '<p class="text-muted">Este usuario no tiene citas registradas.</p>';
                }
                $('#appointmentsList').html(html);
                $('#appointmentsModal').modal('show');
            }
        }
    });
}

function toggleUserStatus(userId) {
    const status = document.getElementById(`userStatus_${userId}`).checked;
    $.ajax({
        url: '/proyectos/colaborativo/Grupo7/index.php?page=admin&action=toggleUserStatus',
        method: 'POST',
        data: { 
            user_id: userId,
            status: status
        },
        success: function(response) {
            if (response.success) {
                showNotification('¡Éxito!', 'Estado del usuario actualizado');
            } else {
                showNotification('Error', 'No se pudo actualizar el estado del usuario', 'error');
                document.getElementById(`userStatus_${userId}`).checked = !status;
            }
        },
        error: function() {
            showNotification('Error', 'No se pudo actualizar el estado del usuario', 'error');
            document.getElementById(`userStatus_${userId}`).checked = !status;
        }
    });
}

function deleteUser(userId) {
    confirmAction(
        '¿Eliminar usuario?',
        '¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.',
        function() {
            $.ajax({
                url: '/proyectos/colaborativo/Grupo7/index.php?page=admin&action=deleteUser',
                method: 'POST',
                data: { user_id: userId },
                success: function(response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        showNotification('Error', 'No se pudo eliminar el usuario', 'error');
                    }
                },
                error: function() {
                    showNotification('Error', 'No se pudo eliminar el usuario', 'error');
                }
            });
        }
    );
}

function editUser(userId) {
    window.location.href = `/proyectos/colaborativo/Grupo7/index.php?page=admin&action=editUser&id=${userId}`;
}
</script> 