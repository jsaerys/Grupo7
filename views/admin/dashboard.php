<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Dashboard Administrativo</h1>

        <!-- Estadísticas Generales -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Clientes</h6>
                                <h2 class="mt-2 mb-0"><?php echo $stats['total_users']; ?></h2>
                            </div>
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Productos</h6>
                                <h2 class="mt-2 mb-0"><?php echo $stats['total_products']; ?></h2>
                            </div>
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Citas Pendientes</h6>
                                <h2 class="mt-2 mb-0"><?php echo $stats['total_appointments']; ?></h2>
                            </div>
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">Ventas Totales</h6>
                                <h2 class="mt-2 mb-0">$<?php echo number_format($stats['total_sales'], 2); ?></h2>
                            </div>
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Productos con Bajo Stock -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>Productos con Bajo Stock
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($stats['low_stock_products'])): ?>
                            <p class="text-success mb-0">No hay productos con bajo stock.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Stock Actual</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($stats['low_stock_products'] as $product): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($product['nombre']); ?></td>
                                                <td>
                                                    <span class="badge bg-danger">
                                                        <?php echo $product['stock']; ?> unidades
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="/proyectos/colaborativo/Grupo7/index.php?page=products&action=edit&id=<?php echo $product['id']; ?>" 
                                                       class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
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

            <!-- Próximas Citas -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-calendar-check me-2"></i>Próximas Citas
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($stats['upcoming_appointments'])): ?>
                            <p class="text-muted mb-0">No hay citas próximas.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Mascota</th>
                                            <th>Fecha</th>
                                            <th>Hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($stats['upcoming_appointments'] as $appointment): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($appointment['cliente_nombre']); ?></td>
                                                <td><?php echo htmlspecialchars($appointment['mascota_nombre']); ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($appointment['fecha'])); ?></td>
                                                <td><?php echo date('H:i', strtotime($appointment['hora'])); ?></td>
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

        <!-- Accesos Rápidos -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Accesos Rápidos</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-3">
                                <a href="/proyectos/colaborativo/Grupo7/index.php?page=admin&action=users" 
                                   class="btn btn-outline-primary w-100 p-4">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <br>Gestionar Usuarios
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="/proyectos/colaborativo/Grupo7/index.php?page=admin&action=products" 
                                   class="btn btn-outline-success w-100 p-4">
                                    <i class="fas fa-box fa-2x mb-2"></i>
                                    <br>Gestionar Productos
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="/proyectos/colaborativo/Grupo7/index.php?page=admin&action=appointments" 
                                   class="btn btn-outline-warning w-100 p-4">
                                    <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                    <br>Gestionar Citas
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="/proyectos/colaborativo/Grupo7/index.php?page=admin&action=reports" 
                                   class="btn btn-outline-info w-100 p-4">
                                    <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                    <br>Ver Reportes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 