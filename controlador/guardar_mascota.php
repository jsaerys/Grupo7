<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php?page=login');
    exit;
}

require_once '../config/conexion.php'; // conexión a BD con $mysqli

$nombre = trim($_POST['nombre'] ?? '');
$especie = $_POST['especie'] ?? '';
$raza = trim($_POST['raza'] ?? '');
$edad = $_POST['edad'] !== '' ? (int)$_POST['edad'] : null;
$usuario_id = $_SESSION['usuario_id'];

if ($nombre && $especie) {
    $sql = "INSERT INTO mascotas (nombre, especie, raza, edad, usuario_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssii", $nombre, $especie, $raza, $edad, $usuario_id);

    if ($stmt->execute()) {
        header('Location: ../panel/panel_principal.php?msg=Mascota registrada correctamente');
    } else {
        echo "Error al registrar mascota: " . $stmt->error;
    }
} else {
    echo "El nombre y la especie son obligatorios.";
}
?>
