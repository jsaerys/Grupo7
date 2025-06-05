<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Veterinaria</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
    <?php require_once 'views/layouts/header.php'; ?>

    <div class="container">
        <div class="form-container">
            <h2>Crear Cuenta</h2>
            
            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <p><?= $error ?></p>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/index.php?page=auth&action=register" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" value="<?= isset($old['nombre']) ? htmlspecialchars($old['nombre']) : '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" value="<?= isset($old['telefono']) ? htmlspecialchars($old['telefono']) : '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="<?= isset($old['email']) ? htmlspecialchars($old['email']) : '' ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn btn-primary">Crear Cuenta</button>
            </form>

            <div class="form-footer">
                <p>¿Ya tienes una cuenta? <a href="<?= BASE_URL ?>/index.php?page=auth&action=login">Iniciar Sesión</a></p>
            </div>
        </div>
    </div>

    <?php require_once 'views/layouts/footer.php'; ?>
</body>
</html> 