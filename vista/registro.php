<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Guau - Registro</title>
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

    <form id="registroForm" action="../controlador/usuariocontroller.php?action=register" method="POST">
      <label for="nombre">Nombre</label>
      <input type="text" id="nombre" name="nombre" required>
      <label for="email">Correo</label>
      <input type="email" id="email" name="email" required>
      <label for="telefono">Teléfono</label>
      <input type="text" id="telefono" name="telefono">
      <label for="password">Contraseña</label>
      <input type="password" id="password" name="password" required>
      <button type="submit">Registrarse</button>
    </form>
    <div class="info">
        <a href="login.php" style="display:block; text-align:center; margin-top: 15px;">Volver al inicio de sesión</a>
    </div>
  </div>

</body>
</html>
