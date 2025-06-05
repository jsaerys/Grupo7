<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Veterinaria</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
    <?php require_once 'views/templates/header.php'; ?>

    <div class="login-container">
        <span class="logo">🐾 Guau</span>
        <form id="loginForm">
            <div>
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Iniciar Sesión</button>
        </form>
        <p class="info">¿No tienes una cuenta?<a href="<?= BASE_URL ?>/index.php?page=auth&action=register">Regístrate</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>const BASE_URL = '<?= BASE_URL ?>';</script>
    <script src="<?= BASE_URL ?>/assets/js/login.js"></script>

    <?php require_once 'views/templates/footer.php'; ?>
</body>
</html> 