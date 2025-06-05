document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }
});

async function handleLogin(e) {
    e.preventDefault();

    const email = e.target.email.value.trim();
    const password = e.target.password.value;

    // Validar campos vacíos
    if (!email || !password) {
        await Swal.fire({
            icon: 'error',
            title: 'Campos incompletos',
            text: 'Por favor, complete todos los campos.',
            confirmButtonColor: '#ff934f'
        });
        return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'login');
        formData.append('email', email);
        formData.append('password', password);

        const response = await fetch(loginForm.action, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            await Swal.fire({
                icon: 'success',
                title: '¡Bienvenido!',
                text: 'Inicio de sesión exitoso',
                confirmButtonColor: '#ff934f'
            });
            window.location.href = 'index.php';
        } else {
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.error || 'Error al iniciar sesión',
                confirmButtonColor: '#ff934f'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al procesar la solicitud',
            confirmButtonColor: '#ff934f'
        });
    }
} 