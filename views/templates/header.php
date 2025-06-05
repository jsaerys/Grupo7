<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guau - Tienda de Mascotas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/styles.css">
</head>
<body>
    <nav class="navbar">
        <span class="logo">🐾 Guau</span>
        <ul>
            <li><a href="<?= BASE_URL ?>/index.php?page=home" class="<?= $page === 'home' ? 'active' : '' ?>">Inicio</a></li>
            <li><a href="<?= BASE_URL ?>/index.php?page=products" class="<?= $page === 'products' ? 'active' : '' ?>">Productos</a></li>
            <li><a href="<?= BASE_URL ?>/index.php?page=about" class="<?= $page === 'about' ? 'active' : '' ?>">Sobre Nosotros</a></li>
            <li><a href="<?= BASE_URL ?>/index.php?page=contact" class="<?= $page === 'contact' ? 'active' : '' ?>">Contacto</a></li>
            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                    <li><a href="<?= BASE_URL ?>/index.php?page=admin" class="<?= $page === 'admin' ? 'active' : '' ?>">Admin</a></li>
                <?php endif; ?>
                <li><a href="<?= BASE_URL ?>/index.php?page=auth&action=logout" class="login-btn">Cerrar Sesión</a></li>
            <?php else: ?>
                <li><a href="<?= BASE_URL ?>/index.php?page=auth&action=login" class="login-btn">Ingresar</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</body>
</html> 