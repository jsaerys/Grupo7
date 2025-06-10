<?php
session_start();
$base_path = '/proyectos/colaborativo/Grupo7/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guau - Tienda de Mascotas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #498f9d;
            --secondary-color: #e67e22;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: var(--primary-color);
        }

        .hero {
            background: linear-gradient(135deg, var(--primary-color), #2c3e50);
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
        }

        .services {
            padding: 80px 0;
            background-color: #f8f9fa;
        }

        .service-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            height: 100%;
            transition: transform 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-10px);
        }

        .service-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .service-title {
            color: #2c3e50;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .service-description {
            color: #7f8c8d;
            font-size: 1rem;
            line-height: 1.6;
        }

        .features {
            padding: 80px 0;
            background-color: white;
        }

        .feature-card {
            text-align: center;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .feature-title {
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .feature-description {
            color: #7f8c8d;
        }

        .footer {
            background-color: var(--primary-color);
            color: white;
            padding: 2rem 0;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin: 1rem 0;
        }

        .social-icons a {
            color: white;
            font-size: 1.5rem;
            transition: transform 0.3s ease, color 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .social-icons a:hover {
            transform: translateY(-3px);
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="recursos/logo.png" alt="Logo" style="height: 40px;">
                <span class="ms-2">Guau</span>
        </a>
            <div class="d-flex">
                <a href="login.php" class="btn btn-outline-light me-2">
                    <i class="bi bi-person-fill me-1"></i>Iniciar Sesión
                </a>
                <a href="registro.php" class="btn btn-light">
                    <i class="bi bi-person-plus-fill me-1"></i>Registrarse
                </a>
        </div>
    </div>
  </nav>

  <header class="hero">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h1 class="display-4 fw-bold mb-4">Bienvenido a <span class="text-warning">Guau</span></h1>
          <p class="lead mb-4">La tienda favorita de tu mejor amigo 🐶🐱</p>
                    <a href="registro.php" class="btn btn-light btn-lg">
                        <i class="bi bi-paw-fill me-2"></i>Únete Ahora
                    </a>
        </div>
        <div class="col-md-6 text-center">
          <img src="https://images.unsplash.com/photo-1518717758536-85ae29035b6d?auto=format&fit=crop&w=500&q=80" alt="Perro feliz" class="img-fluid rounded-circle shadow-lg">
        </div>
      </div>
    </div>
  </header>

    <section class="services">
      <div class="container">
        <h2 class="text-center mb-5">Nuestros Servicios</h2>
        <div class="row g-4">
          <div class="col-md-4">
            <div class="service-card">
                        <div class="service-icon">
              <i class="bi bi-heart-pulse"></i>
                        </div>
                        <h3 class="service-title">Atención Veterinaria</h3>
                        <p class="service-description">
                            Cuidado profesional para tu mascota con los mejores especialistas.
                        </p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="service-card">
                        <div class="service-icon">
              <i class="bi bi-scissors"></i>
                        </div>
                        <h3 class="service-title">Peluquería</h3>
                        <p class="service-description">
                            Estética y limpieza para tu amigo peludo con los últimos tratamientos.
                        </p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="service-card">
                        <div class="service-icon">
              <i class="bi bi-shop"></i>
                        </div>
                        <h3 class="service-title">Tienda</h3>
                        <p class="service-description">
                            Todo lo que tu mascota necesita para una vida feliz y saludable.
                        </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="features">
        <div class="container">
            <h2 class="text-center mb-5">¿Por qué elegirnos?</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="feature-title">Cuidado Especializado</h3>
                        <p class="feature-description">
                            Productos seleccionados para la salud y felicidad de tus mascotas.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-person-heart"></i>
                        </div>
                        <h3 class="feature-title">Atención personalizada</h3>
                        <p class="feature-description">
                            Asesoría profesional para elegir lo mejor para tu compañero peludo.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h3 class="feature-title">Envíos rápidos</h3>
                        <p class="feature-description">
                            Recibe tu pedido en la puerta de tu casa en tiempo récord.
                        </p>
                    </div>
        </div>
        </div>
      </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Guau - Tienda de Mascotas</h5>
                    <p>Cuidando a tus mascotas con amor y profesionalismo</p>
                </div>
                <div class="col-md-4">
                    <h5>Horario de Atención</h5>
                    <p>Lunes a Sábado: 9:00 AM - 7:00 PM<br>Domingo: 10:00 AM - 4:00 PM</p>
                </div>
                <div class="col-md-4">
                    <h5>Contacto</h5>
                    <p>
                        <i class="bi bi-telephone-fill me-2"></i>+51 123 456 789<br>
                        <i class="bi bi-envelope-fill me-2"></i>contacto@guau.com
                    </p>
                </div>
            </div>
            <div class="social-icons">
                <a href="#" aria-label="WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                </a>
                <a href="#" aria-label="Facebook">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="#" aria-label="Instagram">
                    <i class="bi bi-instagram"></i>
                </a>
            </div>
            <hr class="my-4">
            <p class="text-center mb-0">&copy; 2025 Guau. Todos los derechos reservados.</p>
    </div>
  </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
