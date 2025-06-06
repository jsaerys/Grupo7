<?php
session_start();
require_once 'usuariocontroller.php';

$controller = new UsuarioController();
$resultado = $controller->cerrarSesion();

// Redirigir al usuario a la página de inicio después de cerrar sesión
header('Location: ../vista/index.php');
exit;
?>
