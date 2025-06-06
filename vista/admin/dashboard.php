<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Shop Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styleDashboard.css">
</head>
<body>
    <div id="dashboard-container">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><i class="bi bi-speedometer2 me-2"></i>Panel Admin</h3>
                <a href="../index.php" class="btn btn-outline-light btn-sm mt-2">Volver al inicio</a>
            </div>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link active" data-target="mascotas">
                        <i class="bi bi-hearts me-2"></i> Mascotas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white" data-target="clientes">
                        <i class="bi bi-people-fill me-2"></i> Clientes
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white" data-target="ventas">
                        <i class="bi bi-cash-coin me-2"></i> Ventas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white" data-target="citas">
                        <i class="bi bi-calendar-check me-2"></i> Citas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white" data-target="productos">
                        <i class="bi bi-box-seam me-2"></i> Productos
                    </a>
                </li>
            </ul>
        </nav>

        <div id="content">
            <!-- Content for each section will be dynamically generated here -->
        </div>
    </div>

    <!-- Modal for Add/Edit -->
    <div class="modal fade" id="form-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal-title"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="modal-form">
                        <!-- Form fields will be dynamically added here -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" form="modal-form" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>
