<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Mis Citas</h1>
            <a href="/proyectos/colaborativo/Grupo7/index.php?page=appointments&action=create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nueva Cita
            </a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php
                $message = '';
                switch ($_GET['success']) {
                    case '1':
                        $message = 'Cita agendada exitosamente.';
                        break;
                    case '2':
                        $message = 'Cita actualizada exitosamente.';
                        break;
                }
                echo $message;
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($upcomingAppointments)): ?>
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Próximas Citas
                    </h5>
                </div>
                <div class="card-body">
                    <?php foreach ($upcomingAppointments as $appointment): ?>
                        <div class="alert alert-info mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">
                                        <?php echo htmlspecialchars($appointment['mascota_nombre']); ?> - 
                                        <?php echo htmlspecialchars($appointment['servicio']); ?>
                                    </h6>
                                    <p class="mb-0">
                                        <i class="far fa-calendar me-2"></i>
                                        <?php echo date('d/m/Y', strtotime($appointment['fecha'])); ?> a las 
                                        <?php echo date('H:i', strtotime($appointment['hora'])); ?>
                                    </p>
                                </div>
                                <div>
                                    <button onclick="cancelAppointment(<?php echo $appointment['id']; ?>)" 
                                            class="btn btn-danger btn-sm">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Historial de Citas</h5>
            </div>
            <div class="card-body">
                <?php if (empty($appointments)): ?>
                    <div class="alert alert-info">
                        No tienes citas registradas.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
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
                                    <tr>
                                        <td><?php echo htmlspecialchars($appointment['mascota_nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['servicio']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($appointment['fecha'])); ?></td>
                                        <td><?php echo date('H:i', strtotime($appointment['hora'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $appointment['estado'] === 'pendiente' ? 'warning' : 
                                                    ($appointment['estado'] === 'confirmada' ? 'success' : 'danger'); 
                                            ?>">
                                                <?php echo ucfirst($appointment['estado']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($appointment['estado'] === 'pendiente'): ?>
                                                <a href="/proyectos/colaborativo/Grupo7/index.php?page=appointments&action=edit&id=<?php echo $appointment['id']; ?>" 
                                                   class="btn btn-warning btn-sm me-2">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="cancelAppointment(<?php echo $appointment['id']; ?>)" 
                                                        class="btn btn-danger btn-sm">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
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

<script>
function cancelAppointment(appointmentId) {
    confirmAction(
        '¿Cancelar cita?',
        '¿Estás seguro de que deseas cancelar esta cita?',
        function() {
            $.ajax({
                url: '/proyectos/colaborativo/Grupo7/index.php?page=appointments&action=cancel',
                method: 'POST',
                data: { id: appointmentId },
                success: function(response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        showNotification('Error', 'No se pudo cancelar la cita', 'error');
                    }
                },
                error: function() {
                    showNotification('Error', 'No se pudo cancelar la cita', 'error');
                }
            });
        }
    );
}
</script> 