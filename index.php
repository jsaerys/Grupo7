<?php
session_start();

// Redirigir a la página correspondiente
if (isset($_SESSION['user'])) {
    header('Location: vista/' . ($_SESSION['user']['tipo'] === 'admin' ? 'admin/dashboard.php' : 'cliente/panel.php'));
} else {
    header('Location: vista/index.php');
}
exit;