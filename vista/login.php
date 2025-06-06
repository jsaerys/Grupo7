<?php
session_start();

// Si ya hay una sesión activa, redirigir según el rol
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['tipo'] === 'admin') {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: cliente/panel.php');
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Guau - Ingreso</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles/styles.css">
  <script type="importmap">
    {
      "imports": {
        "sweetalert2": "https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"
      }
    }
  </script>
</head>
<body style="display: flex; justify-content: center; align-items: center;">
  <div class="login-container">
    <span class="logo">🐾 Guau</span>
    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="message">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="error">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <form id="loginForm" action="../controlador/usuariocontroller.php" method="POST">
      <input type="hidden" name="action" value="login">
      <label for="email">Correo</label>
      <input type="email" id="email" name="email" required>
      <label for="password">Contraseña</label>
      <input type="password" id="password" name="password" required>
      <button type="submit">Ingresar</button>
    </form>
    <div class="info">

        <a href="index.php" style="display:block; text-align:center; margin-top: 15px;">Volver al inicio</a>
        <a href="registro.php" style="display:block; text-align:center; margin-top: 10px;">Registrarse</a>
    </div>
  </div>

</body>
</html>
