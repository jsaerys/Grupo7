<?php
session_start();
$base_path = '/proyectos/colaborativo/Grupo7/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Guau - Tienda de Mascotas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo $base_path; ?>vista/styles/styles.css">
  <script type="importmap">
    {
      "imports": {
        "sweetalert2": "https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"
      }
    }
  </script>
</head>
<body>
  <nav class="navbar">
    <span class="logo">🐾 Guau</span>
    <ul>
      <li><a href="<?php echo $base_path; ?>vista/index.php" class="active">Inicio</a></li>
      <li><a href="<?php echo $base_path; ?>vista/productos.php">Productos</a></li>
      <li><a href="<?php echo $base_path; ?>vista/nosotros.php">Sobre Nosotros</a></li>
      <li><a href="<?php echo $base_path; ?>vista/contacto.php">Contacto</a></li>
      <?php if (isset($_SESSION['username'])): ?>
        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
          <li><a href="<?php echo $base_path; ?>vista/index.php">Admin</a></li>
        <?php endif; ?>
        <li><a href="<?php echo $base_path; ?>controlador/logout.php" class="login-btn">Cerrar Sesión</a></li>
      <?php else: ?>
        <li><a href="<?php echo $base_path; ?>vista/login.php" class="login-btn">Ingresar</a></li>
      <?php endif; ?>
    </ul>
  </nav>
  <header class="hero">
    <div class="hero-content">
      <h1>Bienvenido a <span>Guau</span></h1>
      <p>La tienda favorita de tu mejor amigo 🐶🐱</p>
      <a href="productos.php" class="cta-btn">Explora Productos</a>
    </div>
    <img src="https://images.unsplash.com/photo-1518717758536-85ae29035b6d?auto=format&fit=crop&w=500&q=80" alt="Perro feliz" class="hero-img">
  </header>
  <main class="inicio-main">
    <section class="features">
      <h2>¿Por qué elegirnos?</h2>
      <div class="features-list">
        <div class="feature">
          <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/72x72/1f436.png" alt="Perro" width="56" height="56">
          <h3>Cuidado Especializado</h3>
          <p>Productos seleccionados para la salud y felicidad de tus mascotas.</p>
        </div>
        <div class="feature">
          <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/72x72/1f431.png" alt="Gato" width="56" height="56">
          <h3>Atención personalizada</h3>
          <p>Asesoría profesional para elegir lo mejor para tu compañero peludo.</p>
        </div>
        <div class="feature">
          <img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/72x72/1f4e6.png" alt="Caja regalo" width="56" height="56">
          <h3>Envíos rápidos</h3>
          <p>Recibe tu pedido en la puerta de tu casa en tiempo récord.</p>
        </div>
      </div>
    </section>
    <section class="destacados">
      <h2>Productos Destacados</h2>
      <div class="producto-lista">
        <div class="producto">
          <img src="https://images.unsplash.com/photo-1518715308788-30057527ade5?auto=format&fit=crop&w=300&q=80" alt="Croquetas Premium">
          <div>
            <h4>Croquetas Premium</h4>
            <p>Nutrición completa para tu perro adulto.</p>
            <span class="precio">$350</span>
          </div>
        </div>
        <div class="producto">
          <img src="https://images.unsplash.com/photo-1508672019048-805c876b67e2?auto=format&fit=crop&w=300&q=80" alt="Juguete para Gato">
          <div>
            <h4>Juguete para Gato</h4>
            <p>Horas de diversión y ejercicio asegurado.</p>
            <span class="precio">$99</span>
          </div>
        </div>
        <div class="producto">
          <img src="https://images.unsplash.com/photo-1506332026074-c3eee0ffb9e3?auto=format&fit=crop&w=300&q=80" alt="Correa Color Pastel">
          <div>
            <h4>Correa Color Pastel</h4>
            <p>Estilo y seguridad en tus paseos.</p>
            <span class="precio">$120</span>
          </div>
        </div>
      </div>
    </section>
  </main>
  <footer class="footer">
    <span> 2024 Guau - Tienda de Mascotas. Todos los derechos reservados.</span>
  </footer>
</body>
</html>
