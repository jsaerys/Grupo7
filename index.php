<?php
session_start();
$base_path = '/proyectos/colaborativo/Grupo7/';

if (!isset($_SESSION['user'])) {
    header('Location: ' . $base_path . 'vista/login.php');
    exit;
}

// Redirigir según el tipo de usuario
if ($_SESSION['user']['tipo'] === 'admin') {
    header('Location: ' . $base_path . 'vista/dashboard.php');
} else {
    header('Location: ' . $base_path . 'vista/cliente/panel.php');
}
exit;
?>
http://localhost/proyectos/colaborativo/Grupo7/