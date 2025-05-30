<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php?page=login');
    exit;
}

// Conexión a la base de datos
require_once '../configuracion/db.php'; // AJUSTE: ruta correcta al archivo

// Obtener y limpiar los datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$especie = trim($_POST['especie'] ?? '');
$raza = trim($_POST['raza'] ?? '');
$edad = isset($_POST['edad']) && $_POST['edad'] !== '' ? (int)$_POST['edad'] : null;
$usuario_id = $_SESSION['usuario_id'];

// Validación mínima
if ($nombre && $especie) {
    $sql = "INSERT INTO mascotas (nombre, especie, raza, edad, usuario_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssii", $nombre, $especie, $raza, $edad, $usuario_id);

    if ($stmt->execute()) {
        // Redirige al panel con mensaje
        header('Location: ../vista/panel_principal.php?msg=Mascota registrada correctamente');
        exit;
    } else {
        echo "❌ Error al registrar mascota: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "⚠️ El nombre y la especie son obligatorios.";
}

$mysqli->close();
?>
