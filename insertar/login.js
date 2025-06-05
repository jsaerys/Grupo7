import Swal from "sweetalert2";

// Simulación simple de inicio de sesión
const VALID_USERS = [
  { username: 'cliente', password: 'guau2024' },
  { username: 'admin', password: 'admin123' }
];

document.getElementById('loginForm').addEventListener('submit', e => {
  e.preventDefault();

  const username = e.target.username.value.trim();
  const password = e.target.password.value;

  // Validar campos vacíos
  if (!username || !password) {
    Swal.fire({
      icon: 'error',
      title: 'Campos incompletos',
      text: 'Por favor, introduce tu usuario y contraseña.',
      confirmButtonColor: '#ff934f'
    });
    return;
  }

  // Validar usuario
  const userFound = VALID_USERS.find(
    user => user.username === username && user.password === password
  );
  if (userFound) {
    Swal.fire({
      icon: 'success',
      title: '¡Bienvenido a Guau!',
      text: `Hola, ${username} 🐶`,
      confirmButtonColor: '#ff934f'
    }).then(() => {
      // Redireccionar a la tienda o dashboard
      window.location.href = "#";
    });
  } else {
    Swal.fire({
      icon: 'error',
      title: 'Usuario o contraseña incorrectos',
      text: 'Por favor, revisa tus datos.',
      confirmButtonColor: '#ff934f'
    });
  }
});

