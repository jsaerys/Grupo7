document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }
});

async function handleLogin(e) {
    e.preventDefault();

    const username = e.target.username.value.trim();
    const password = e.target.password.value;

    // Validar campos vacíos
    if (!username || !password) {
        await Swal.fire({
            icon: 'error',
            title: 'Campos incompletos',
            text: 'Por favor, introduce tu usuario y contraseña.',
            confirmButtonColor: '#ff934f'
        });
        return;
    }

    try {
        const response = await fetch(BASE_URL + '/index.php?page=auth&action=login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
        });

        const data = await response.json();

        if (data.success) {
            await Swal.fire({
                icon: 'success',
                title: '¡Bienvenido a Guau!',
                text: `Hola, ${username} 🐶`,
                confirmButtonColor: '#ff934f'
            });
            window.location.href = BASE_URL + '/index.php?page=home';
        } else {
            await Swal.fire({
                icon: 'error',
                title: 'Usuario o contraseña incorrectos',
                text: 'Por favor, revisa tus datos.',
                confirmButtonColor: '#ff934f'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al intentar iniciar sesión.',
            confirmButtonColor: '#ff934f'
        });
    }
} 