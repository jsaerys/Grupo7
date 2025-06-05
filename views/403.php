<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Acceso Denegado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .error-template {
            padding: 40px 15px;
            text-align: center;
        }
        .error-actions {
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .error-actions .btn {
            margin-right: 10px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1 class="display-1">
                        <i class="fas fa-lock text-danger"></i>
                    </h1>
                    <h2 class="display-4">403</h2>
                    <h3>¡Acceso Denegado!</h3>
                    <div class="error-details mb-4">
                        Lo sentimos, no tienes permiso para acceder a esta página.
                    </div>
                    <div class="error-actions">
                        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-home me-2"></i>Volver al inicio
                        </a>
                        <a href="<?php echo BASE_URL; ?>/index.php?page=auth&action=login" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 