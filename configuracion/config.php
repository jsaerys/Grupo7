<?php
// Definir la URL base del proyecto
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
define('BASE_URL', $protocol . $host . '/Grupo7');

// Función para generar URLs completas
function url($path = '') {
    return BASE_URL . $path;
}

// Función para redireccionar
function redirect($path) {
    header('Location: ' . url($path));
    exit;
}

// Función para obtener la ruta actual
function getCurrentPath() {
    $path = $_SERVER['REQUEST_URI'];
    $base_path = parse_url(BASE_URL, PHP_URL_PATH);
    return str_replace($base_path, '', $path);
} 