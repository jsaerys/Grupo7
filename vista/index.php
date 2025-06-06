<?php
session_start();
$base_path = '/proyectos/colaborativo/Grupo7/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Guau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_path; ?>vista/styles/styles.css">
    <style>
        :root {
            --primary-color: #498f9d;
            --secondary-color: #e67e22;
        }
        .navbar {
            background-color: var(--primary-color) !important;
        }
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        .text-primary {
            color: var(--primary-color) !important;
        }
        .card {
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .logo-img {
            height: 40px;
            width: auto;
            margin-right: 10px;
        }
        .service-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            transition: transform 0.2s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .service-card:hover {
            transform: translateY(-5px);
        }
        .service-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        .hero {
            background: linear-gradient(135deg, var(--primary-color), #2c3e50);
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
        }
        .cta-btn {
            background-color: var(--secondary-color);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
            margin-top: 1rem;
        }
        .cta-btn:hover {
            background-color: #d35400;
            color: white;
            transform: translateY(-2px);
        }
    </style>
    <script type="importmap">
        {
          "imports": {
            "sweetalert2": "https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"
          }
        }
    </script>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo $base_path; ?>vista/index.php">
            <img src="<?php echo $base_path; ?>img/logo.png" alt="Logo" class="logo-img">
            <span class="h4 mb-0" style="color: white;">Guau</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li><a href="<?php echo $base_path; ?>vista/index.php" class="active nav-link">Inicio</a></li>
            <li><a href="<?php echo $base_path; ?>vista/nosotros.php" class="nav-link">Sobre Nosotros</a></li>
            <li><a href="<?php echo $base_path; ?>vista/contacto.php" class="nav-link">Contacto</a></li>
            <?php if (isset($_SESSION['user'])): ?>
              <?php if (isset($_SESSION['user']['rol']) && $_SESSION['user']['rol'] === 'admin'): ?>
                <li><a href="admin/dashboard.php" class="nav-link">Panel Admin</a></li>
              <?php endif; ?>
              <li><a href="../controlador/usuariocontroller.php?action=logout" class="nav-link login-btn">Cerrar Sesión</a></li>
            <?php else: ?>
              <li><a href="login.php" class="nav-link login-btn">Ingresar</a></li>
            <?php endif; ?>
          </ul>
        </div>
    </div>
  </nav>
  <header class="hero">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h1 class="display-4 fw-bold mb-4">Bienvenido a <span class="text-warning">Guau</span></h1>
          <p class="lead mb-4">La tienda favorita de tu mejor amigo 🐶🐱</p>
          <a href="<?php echo $base_path; ?>vista/nosotros.php" class="cta-btn">Conócenos</a>
        </div>
        <div class="col-md-6 text-center">
          <img src="https://images.unsplash.com/photo-1518717758536-85ae29035b6d?auto=format&fit=crop&w=500&q=80" alt="Perro feliz" class="img-fluid rounded-circle shadow-lg">
        </div>
      </div>
    </div>
  </header>
  <main class="inicio-main">
    <section class="servicios py-5">
      <div class="container">
        <h2 class="text-center mb-5">Nuestros Servicios</h2>
        <div class="row g-4">
          <div class="col-md-4">
            <div class="service-card">
              <i class="bi bi-heart-pulse"></i>
              <h4>Atención Veterinaria</h4>
              <p class="mb-0">Cuidado profesional para tu mascota con los mejores especialistas.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="service-card">
              <i class="bi bi-scissors"></i>
              <h4>Peluquería</h4>
              <p class="mb-0">Estética y limpieza para tu amigo peludo con los últimos tratamientos.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="service-card">
              <i class="bi bi-shop"></i>
              <h4>Tienda</h4>
              <p class="mb-0">Todo lo que tu mascota necesita para una vida feliz y saludable.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
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

  </main>
  <footer class="py-4 mt-5" style="background-color: var(--primary-color);">
    <div class="container text-center">
      <p class="mb-0 text-white">&copy; 2025 Guau - Tienda de Mascotas. Todos los derechos reservados.</p>
    </div>
  </footer>
</body>
</html>
