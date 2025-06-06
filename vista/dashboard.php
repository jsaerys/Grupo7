<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Shop Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark border-right" id="sidebar-wrapper">
            <div class="sidebar-heading"><i class="bi bi-house-door-fill me-2"></i>Pet Shop Admin</div>
            <div class="list-group list-group-flush" id="sidebar">
                <a href="#" class="list-group-item list-group-item-action bg-dark text-white active" data-target="mascotas">
                    <i class="bi bi-hearts me-2"></i> Mascotas
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-dark text-white" data-target="clientes">
                    <i class="bi bi-people-fill me-2"></i> Clientes
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-dark text-white" data-target="ventas">
                    <i class="bi bi-cash-coin me-2"></i> Ventas
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-dark text-white" data-target="citas">
                    <i class="bi bi-calendar-check me-2"></i> Citas
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-dark text-white" data-target="productos">
                    <i class="bi bi-box-seam me-2"></i> Productos
                </a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div id="content">
                    <!-- Content for each section will be dynamically generated here -->
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
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