<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php?page=login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal - Veterinaria</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="login-container" id="loginContainer" style="display: none;"></div>

    <div id="mainPanel" class="main-panel">
        <header class="main-header">
            <h1>Portal de la Tienda de Mascotas</h1>
            <nav class="main-nav">
                <button data-target="inicioSection" class="nav-button active-nav-button">Inicio</button>
                <button data-target="agendarCitasSection" class="nav-button">Agendar Citas</button>
                <button data-target="veterinariaSection" class="nav-button">Veterinaria</button>
                <a href="../index.php?page=logout" class="nav-button logout-button">Cerrar Sesión</a>
            </nav>
        </header>

        <main>
            <section id="inicioSection" class="panel-section active-section">
                <h2>Promociones Especiales</h2>
                <div class="promotions-container">
                    <div class="promotion-item">
                        <img src="../public/images/promo_food.png" alt="Alimento en promoción">
                        <div>
                            <h4>¡20% Descuento en Alimento Seco!</h4>
                            <p>Este fin de semana, aprovecha un 20% de descuento en todas las marcas de alimento seco para perros y gatos.</p>
                        </div>
                    </div>
                    <div class="promotion-item">
                        <img src="../public/images/promo_grooming.png" alt="Grooming en promoción">
                        <div>
                            <h4>¡Baño y Corte a Mitad de Precio!</h4>
                            <p>Agenda un servicio de grooming completo y obtén el segundo a mitad de precio para tu otra mascota.</p>
                        </div>
                    </div>
                    <div class="promotion-item">
                        <img src="../public/images/promo_toys.png" alt="Juguetes en promoción">
                        <div>
                            <h4>¡Nuevos Juguetes Interactivos!</h4>
                            <p>Descubre nuestra nueva línea de juguetes interactivos para mantener a tu mascota entretenida y feliz.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="agendarCitasSection" class="panel-section">
                <h2>Agendar Citas</h2>
                <form id="appointmentForm" class="styled-form">
                    <h3>Registrar Nueva Cita</h3>
                    <div class="input-group">
                        <label for="petName">Nombre de la Mascota:</label>
                        <input type="text" id="petName" name="petName" required>
                    </div>
                    <div class="input-group">
                        <label for="serviceType">Tipo de Servicio:</label>
                        <select id="serviceType" name="serviceType" required>
                            <option value="">Selecciona un servicio...</option>
                            <option value="consulta">Consulta General</option>
                            <option value="vacunacion">Vacunación</option>
                            <option value="grooming">Peluquería</option>
                            <option value="cirugia">Cirugía Menor</option>
                            <option value="desparasitacion">Desparasitación</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="preferredDate">Fecha Preferida:</label>
                        <input type="date" id="preferredDate" name="preferredDate" required>
                    </div>
                    <div class="input-group">
                        <label for="contactInfo">Tu Email de Contacto:</label>
                        <input type="email" id="contactInfo" name="contactInfo" required>
                    </div>
                    <button type="submit" class="submit-button">Solicitar Cita</button>
                </form>
            </section>

            <section id="veterinariaSection" class="panel-section">
                <h2>Nuestra Veterinaria</h2>
                <div class="carousel-container">
                    <div class="carousel-slide active-slide">
                        <img src="../public/images/pet_image_1.png" alt="Perro feliz en la veterinaria">
                        <div class="carousel-caption">Max después de su chequeo anual</div>
                    </div>
                    <div class="carousel-slide">
                        <img src="../public/images/pet_image_2.png" alt="Gato curioso en consulta">
                        <div class="carousel-caption">Luna explorando la sala de consulta</div>
                    </div>
                    <div class="carousel-slide">
                        <img src="../public/images/pet_image_3.png" alt="Equipo veterinario con mascota">
                        <div class="carousel-caption">Nuestro equipo cuidando de Rocky</div>
                    </div>
                    <button class="carousel-button prev" id="carouselPrev">&#10094;</button>
                    <button class="carousel-button next" id="carouselNext">&#10095;</button>
                </div>
            </section>
        </main>
    </div>

    <script src="../public/script.js"></script>
</body>
</html>
