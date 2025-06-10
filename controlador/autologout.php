<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Si hay una cookie de sesión, destruirla también
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Destruir la sesión
session_destroy();

// Responder con éxito
echo json_encode(['success' => true, 'message' => 'Sesión cerrada']);
?> 