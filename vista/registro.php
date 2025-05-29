<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="public/css/style.css" />
</head>
<body>
    <div class="login-container">
        <h2>Registro de Usuario</h2>

        <?php if (isset($error)) : ?>
            <p style="color: red; text-align:center;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="index.php?page=registro">
            <div class="input-group">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" required />
            </div>
            <div class="input-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required />
            </div>
            <div class="input-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required />
            </div>
            <div class="input-group">
                <label for="confirmar_password">Confirmar Contraseña:</label>
                <input type="password" id="confirmar_password" name="confirmar_password" required />
            </div>
            <button type="submit" class="login-button">Registrar</button>
            <a href="index.php?page=login" class="register-button">¿Ya tienes cuenta? Inicia sesión</a>
        </form>
    </div>
</body>
</html>
