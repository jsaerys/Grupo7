<?php
function conectarDB() {
    $host = 'localhost';
    $usuario = 'root';       // Cambia si tu MySQL tiene otro usuario
    $password = '';          // Cambia si tu MySQL tiene contraseña
    $base_datos = 'veterinaria';

    $conexion = new mysqli($host, $usuario, $password, $base_datos);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    return $conexion;
}
