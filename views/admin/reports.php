<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Reportes</h1>
            <form class="d-flex gap-2">
                <input type="hidden" name="page" value="admin">
                <input type="hidden" name="action" value="reports">
                <input type="date" 
                       name="start_date" 
                       class="form-control" 
                       value="<?php echo $start_date; ?>" 
                       required>
                <input type="date" 
                       name="end_date" 
                       class="form-control" 
                       value="<?php echo $end_date; ?>" 
                       required>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-2"></i>Filtrar
                </button>
            </form>
        </div>

        <!-- Reporte de Ventas -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Reporte de Ventas</h5>
            </div>
            <div class="card-body">
                <?php if (empty($reports['sales'])): ?>
                    <p class="text-muted">No hay ventas registradas en el período seleccionado.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad Vendida</th>
                                    <th>Total Ingresos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reports['sales'] as $sale): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($sale['nombre']); ?></td>
                                        <td><?php echo $sale['total_vendido']; ?></td>
                                        <td>$<?php echo number_format($sale['total_ingresos'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Estadísticas de Citas -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Estadísticas de Citas</h5>
            </div>
            <div class="card-body">
                <?php if (empty($reports['appointments'])): ?>
                    <p class="text-muted">No hay citas registradas en el período seleccionado.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Total Citas</th>
                                    <th>Confirmadas</th>
                                    <th>Canceladas</th>
                                    <th>Tasa de Confirmación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reports['appointments'] as $stat): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($stat['servicio']); ?></td>
                                        <td><?php echo $stat['total']; ?></td>
                                        <td>
                                            <span class="badge bg-success">
                                                <?php echo $stat['confirmadas']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">
                                                <?php echo $stat['canceladas']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                            $rate = ($stat['total'] > 0) 
                                                ? ($stat['confirmadas'] / $stat['total']) * 100 
                                                : 0;
                                            echo number_format($rate, 1) . '%';
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <!-- Productos Más Vendidos -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Productos Más Vendidos</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($reports['popular_products'])): ?>
                            <p class="text-muted">No hay datos disponibles.</p>
                        <?php else: ?>
                            <canvas id="popularProductsChart"></canvas>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Ingresos por Día -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Ingresos por Día</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($reports['revenue'])): ?>
                            <p class="text-muted">No hay datos disponibles.</p>
                        <?php else: ?>
                            <canvas id="revenueChart"></canvas>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($reports['popular_products']) || !empty($reports['revenue'])): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($reports['popular_products'])): ?>
// Gráfico de Productos Más Vendidos
new Chart(document.getElementById('popularProductsChart'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_column($reports['popular_products'], 'nombre')); ?>,
        datasets: [{
            label: 'Unidades Vendidas',
            data: <?php echo json_encode(array_column($reports['popular_products'], 'total_vendido')); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.8)'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
<?php endif; ?>

<?php if (!empty($reports['revenue'])): ?>
// Gráfico de Ingresos por Día
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_map(function($date) {
            return date('d/m', strtotime($date['fecha']));
        }, $reports['revenue'])); ?>,
        datasets: [{
            label: 'Ingresos ($)',
            data: <?php echo json_encode(array_column($reports['revenue'], 'ingresos')); ?>,
            borderColor: 'rgba(75, 192, 192, 1)',
            tension: 0.1,
            fill: false
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value;
                    }
                }
            }
        }
    }
});
<?php endif; ?>
</script>
<?php endif; ?> 