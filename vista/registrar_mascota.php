<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php?page=login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Registrar Mascota</title>
<link rel="stylesheet" href="../style.css" />
</head>
<body>
    <div class="main-panel">
        <header class="main-header">
            <h1>Registrar Nueva Mascota</h1>
            <nav>
                <a href="panel_principal.php">Volver al Panel</a>
                <a href="../index.php?page=logout">Cerrar Sesión</a>
            </nav>
        </header>

        <main>
            <form action="../controlador/guardar_mascota.php" method="POST" class="styled-form">
                <div class="input-group">
                    <label for="nombre">Nombre de la Mascota:</label>
                    <input type="text" id="nombre" name="nombre" required />
                </div>

                <div class="input-group">
                    <label for="especie">Especie:</label>
                    <select id="especie" name="especie" required>
                        <option value="">Selecciona la especie...</option>
                        <option value="Perro">Perro</option>
                        <option value="Gato">Gato</option>
                        <option value="Ave">Ave</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="raza">Raza:</label>
                    <input type="text" id="raza" name="raza" />
                </div>

                <div class="input-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" />
                </div>

                <button type="submit" class="submit-button">Registrar Mascota</button>
            </form>
        </main>
    </div>
</body>
</html>
