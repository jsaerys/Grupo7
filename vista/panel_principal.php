<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php?page=login');
    exit;
}

// Conexión a la base de datos
require_once '../configuracion/db.php';

// Obtener mascotas del usuario para el formulario de citas
$usuario_id = $_SESSION['usuario_id'] ?? null;
$mascotas = [];
if ($usuario_id) {
    $stmt = $mysqli->prepare("SELECT id, nombre FROM mascotas WHERE usuario_id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $mascotas = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Panel Principal - Veterinaria</title>
    <link rel="stylesheet" href="../style.css" />
</head>
<body>
    <div class="main-panel">
        <header class="main-header">
            <h1>Portal de la Tienda de Mascotas</h1>
            <nav class="main-nav">
                <button onclick="mostrarSeccion('inicioSection')" class="nav-button active-nav-button">Inicio</button>
                <button onclick="mostrarSeccion('agendarCitasSection')" class="nav-button">Agendar Citas</button>
                <button onclick="mostrarSeccion('registrarMascotaSection')" class="nav-button">Registrar Mascota</button>
                <button onclick="mostrarSeccion('veterinariaSection')" class="nav-button">Veterinaria</button>
                <a href="../index.php?page=logout" class="nav-button logout-button">Cerrar Sesión</a>
            </nav>
        </header>

        <main>
            <!-- Sección Inicio -->
            <section id="inicioSection" class="panel-section active-section">
                <h2>Promociones Especiales</h2>
                <div class="promotions-container">
                    <div class="promotion-item">
                        <img src="../public/images/promo_food.png" alt="Alimento en promoción" />
                        <div>
                            <h4>¡20% Descuento en Alimento Seco!</h4>
                            <p>Este fin de semana, aprovecha un 20% de descuento en todas las marcas de alimento seco para perros y gatos.</p>
                        </div>
                    </div>
                    <div class="promotion-item">
                        <img src="../public/images/promo_grooming.png" alt="Grooming en promoción" />
                        <div>
                            <h4>¡Baño y Corte a Mitad de Precio!</h4>
                            <p>Agenda un servicio de grooming completo y obtén el segundo a mitad de precio para tu otra mascota.</p>
                        </div>
                    </div>
                    <div class="promotion-item">
                        <img src="../public/images/promo_toys.png" alt="Juguetes en promoción" />
                        <div>
                            <h4>¡Nuevos Juguetes Interactivos!</h4>
                            <p>Descubre nuestra nueva línea de juguetes interactivos para mantener a tu mascota entretenida y feliz.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sección Agendar Citas -->
            <section id="agendarCitasSection" class="panel-section" style="display:none;">
                <h2>Agendar Citas</h2>
                <div class="intro-box">
                    <p>Agenda fácilmente una cita para tu mascota. Servicios disponibles: Consulta, vacunación, grooming, cirugía, etc.</p>
                </div>
                <form id="appointmentForm" class="styled-form" action="../controlador/guardar_cita.php" method="POST">
                    <div class="input-group">
                        <label for="mascota">Nombre de la Mascota:</label>
                        <select id="mascota" name="mascota_id" required>
                            <option value="">Selecciona tu mascota...</option>
                            <?php foreach ($mascotas as $mascota): ?>
                                <option value="<?= $mascota['id'] ?>"><?= htmlspecialchars($mascota['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="servicio">Tipo de Servicio:</label>
                        <select id="servicio" name="servicio" required>
                            <option value="">Selecciona un servicio...</option>
                            <option value="consulta">Consulta General</option>
                            <option value="vacunacion">Vacunación</option>
                            <option value="grooming">Peluquería</option>
                            <option value="cirugia">Cirugía Menor</option>
                            <option value="desparasitacion">Desparasitación</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="fecha">Fecha Preferida:</label>
                        <input type="date" id="fecha" name="fecha" required />
                    </div>

                    <div class="input-group">
                        <label for="hora">Hora Preferida:</label>
                        <input type="time" id="hora" name="hora" required />
                    </div>

                    <button type="submit" class="submit-button">Agendar Cita</button>
                </form>
            </section>

            <!-- Sección Registrar Mascota -->
            <section id="registrarMascotaSection" class="panel-section" style="display:none;">
                <h2>Registrar Mascota</h2>
                <form action="../controlador/guardar_mascota.php" method="POST" class="styled-form">
                    <div class="input-group">
                        <label for="nombre">Nombre de la Mascota:</label>
                        <input type="text" id="nombre" name="nombre" required />
                    </div>
                    <div class="input-group">
                        <label for="especie">Especie:</label>
                        <select id="especie" name="especie" required>
                            <option value="">Selecciona una especie...</option>
                            <option value="Perro">Perro</option>
                            <option value="Gato">Gato</option>
                            <option value="Ave">Ave</option>
                            <option value="Roedor">Roedor</option>
                            <option value="Reptil">Reptil</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="raza">Raza:</label>
                        <input type="text" id="raza" name="raza" />
                    </div>
                    <div class="input-group">
                        <label for="edad">Edad (años):</label>
                        <input type="number" id="edad" name="edad" min="0" />
                    </div>
                    <button type="submit" class="submit-button">Registrar Mascota</button>
                </form>
            </section>

            <!-- Sección Veterinaria -->
            <section id="veterinariaSection" class="panel-section" style="display:none;">
                <h2>Nuestra Veterinaria</h2>
                <div class="carousel-container">
                    <div class="carousel-slide active-slide">
                        <img src="../public/images/pet_image_1.png" alt="Perro feliz en la veterinaria" />
                        <div class="carousel-caption">Max después de su chequeo anual</div>
                    </div>
                    <div class="carousel-slide">
                        <img src="../public/images/pet_image_2.png" alt="Gato curioso en consulta" />
                        <div class="carousel-caption">Luna explorando la sala de consulta</div>
                    </div>
                    <div class="carousel-slide">
                        <img src="../public/images/pet_image_3.png" alt="Equipo veterinario con mascota" />
                        <div class="carousel-caption">Nuestro equipo cuidando de Rocky</div>
                    </div>
                    <button class="carousel-button prev" id="carouselPrev">&#10094;</button>
                    <button class="carousel-button next" id="carouselNext">&#10095;</button>
                </div>
            </section>
        </main>
    </div>

    <script>
        function mostrarSeccion(id) {
            document.querySelectorAll('.panel-section').forEach(section => {
                section.style.display = 'none';
            });
            document.getElementById(id).style.display = 'block';

            document.querySelectorAll('.nav-button').forEach(button => {
                button.classList.remove('active-nav-button');
            });

            // Marcar botón activo
            const botones = document.querySelectorAll('.nav-button');
            botones.forEach(boton => {
                if (boton.textContent.trim() === id.replace('Section', '').replace(/([A-Z])/g, ' $1').trim()) {
                    boton.classList.add('active-nav-button');
                }
            });
        }
    </script>
</body>
</html>
