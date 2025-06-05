<?php
session_start();
require_once '../modelo/producto.php';

// Inicializar el modelo de productos
$productoModelo = new Producto();

// Obtener productos destacados (limitado a 3)
$productos = array_slice($productoModelo->obtenerTodos(), 0, 3);

// Definir la página actual para el menú
$page = 'home';
?>
.
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guau - Tienda de Mascotas</title>
    <link rel="stylesheet" href="styles/main.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <nav class="navbar">
        <span class="logo">🐾 Guau</span>
        <ul>
            <li><a href="index.php" class="active">Inicio</a></li>
            <li><a href="productos.php">Productos</a></li>
            <li><a href="nosotros.php">Sobre Nosotros</a></li>
            <li><a href="contacto.php">Contacto</a></li>
            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['rol'] === 'admin'): ?>
                    <li><a href="admin/index.php">Admin</a></li>
                <?php endif; ?>
                <li>
                    <a href="#" onclick="cerrarSesion()" class="login-btn">Cerrar Sesión</a>
                </li>
            <?php else: ?>
                <li><a href="login.php" class="login-btn">Ingresar</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <header class="hero">
        <div class="hero-content">
            <h1>Bienvenido a <span>Guau</span></h1>
            <p>La tienda favorita de tu mejor amigo 🐶🐱</p>
            <a href="productos.php" class="cta-btn">Explora Productos</a>
        </div>
        <img src="https://images.unsplash.com/photo-1518717758536-85ae29035b6d?auto=format&fit=crop&w=500&q=80" 
             alt="Perro feliz" class="hero-img">
    </header>

    <main class="inicio-main">
        <section class="features">
            <h2>¿Por qué elegirnos?</h2>
            <div class="features-list">
                <div class="feature">
                    <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/72x72/1f436.png" 
                         alt="Perro" width="56" height="56">
                    <h3>Cuidado Especializado</h3>
                    <p>Productos seleccionados para la salud y felicidad de tus mascotas.</p>
                </div>
                <div class="feature">
                    <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/72x72/1f431.png" 
                         alt="Gato" width="56" height="56">
                    <h3>Atención personalizada</h3>
                    <p>Asesoría profesional para elegir lo mejor para tu compañero peludo.</p>
                </div>
                <div class="feature">
                    <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/72x72/1f4e6.png" 
                         alt="Caja regalo" width="56" height="56">
                    <h3>Envíos rápidos</h3>
                    <p>Recibe tu pedido en la puerta de tu casa en tiempo récord.</p>
                </div>
            </div>
        </section>

        <section class="destacados">
            <h2>Productos Destacados</h2>
            <div class="producto-lista">
                <?php foreach ($productos as $producto): ?>
                <div class="producto">
                    <?php if (!empty($producto['imagen'])): ?>
                        <img src="../assets/img/products/<?= htmlspecialchars($producto['imagen']) ?>" 
                             alt="<?= htmlspecialchars($producto['nombre']) ?>">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/300x200.png?text=No+Image" 
                             alt="Sin imagen">
                    <?php endif; ?>
                    <div>
                        <h4><?= htmlspecialchars($producto['nombre']) ?></h4>
                        <p><?= htmlspecialchars($producto['descripcion']) ?></p>
                        <span class="precio">$<?= number_format($producto['precio'], 2) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        <span>&copy; 2024 Guau - Tienda de Mascotas. Todos los derechos reservados.</span>
    </footer>

    <script>
    function cerrarSesion() {
        Swal.fire({
            title: '¿Cerrar sesión?',
            text: '¿Estás seguro que deseas cerrar sesión?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ff934f',
            cancelButtonColor: '#357186',
            confirmButtonText: 'Sí, cerrar sesión',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../controlador/procesar_usuario.php?action=logout';
            }
        });
    }

    // Mostrar mensaje de bienvenida si acaba de iniciar sesión
    <?php if (isset($_SESSION['login_success'])): ?>
    Swal.fire({
        icon: 'success',
        title: '¡Bienvenido!',
        text: 'Has iniciado sesión correctamente',
        confirmButtonColor: '#ff934f'
    });
    <?php unset($_SESSION['login_success']); endif; ?>
    </script>
</body>
</html> 