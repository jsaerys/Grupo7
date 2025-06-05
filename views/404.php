<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada</title>
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
        .error-details {
            margin: 20px 0;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1 class="display-1">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                    </h1>
                    <h2 class="display-4">404</h2>
                    <h3>¡Oops! Página no encontrada</h3>
                    <div class="error-details">
                        Lo sentimos, la página que estás buscando no existe o ha sido movida.
                    </div>
                    <div class="error-actions">
                        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-home me-2"></i>Volver al inicio
                        </a>
                        <a href="<?php echo BASE_URL; ?>/index.php?page=contact" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-envelope me-2"></i>Contactar soporte
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 