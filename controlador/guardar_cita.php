<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php?page=login');
    exit;
}

require_once '../config/conexion.php'; // archivo que conecta a la base de datos

// Recibir datos POST
$mascota_id = $_POST['mascota_id'] ?? null;
$servicio = $_POST['servicio'] ?? null;
$fecha = $_POST['fecha'] ?? null;
$hora = $_POST['hora'] ?? null;
$usuario_id = $_SESSION['usuario_id']; // viene de sesión
$estado = 'pendiente';

if ($mascota_id && $servicio && $fecha && $hora) {
    $sql = "INSERT INTO citas (fecha, hora, servicio, mascota_id, usuario_id, estado) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssiis", $fecha, $hora, $servicio, $mascota_id, $usuario_id, $estado);
    
    if ($stmt->execute()) {
        header('Location: ../panel/panel_principal.php?msg=Cita agendada correctamente');
    } else {
        echo "Error al guardar la cita: " . $stmt->error;
    }
} else {
    echo "Faltan datos obligatorios para agendar la cita.";
}
?>
