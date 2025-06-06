import Swal from "sweetalert2";

document.addEventListener('DOMContentLoaded', function() {
    const registroForm = document.getElementById('registroForm');
    if (registroForm) {
        registroForm.addEventListener('submit', handleRegistro);
    }
});

async function handleRegistro(e) {
    e.preventDefault();

    const nombre = e.target.nombre.value.trim();
    const email = e.target.email.value.trim();
    const telefono = e.target.telefono.value.trim();
    const password = e.target.password.value;

    // Validar campos vacíos
    if (!nombre || !email || !password) {
        await Swal.fire({
            icon: 'error',
            title: 'Campos incompletos',
            text: 'Por favor, complete todos los campos obligatorios (Nombre, Correo, Contraseña).',
            confirmButtonColor: '#ff934f'
        });
        return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'register');
        formData.append('nombre', nombre);
        formData.append('email', email);
        formData.append('telefono', telefono);
        formData.append('password', password);

        const response = await fetch(registroForm.action, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            await Swal.fire({
                icon: 'success',
                title: '¡Registro Exitoso!',
                text: 'Tu cuenta ha sido creada. Ahora puedes iniciar sesión.',
                confirmButtonColor: '#ff934f'
            });
            window.location.href = 'login.php'; // Redirigir al login
        } else {
            await Swal.fire({
                icon: 'error',
                title: 'Error en el Registro',
                text: data.error || 'Hubo un problema al registrar tu cuenta.',
                confirmButtonColor: '#ff934f'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        await Swal.fire({
            icon: 'error',
            title: 'Error de Conexión',
            text: 'No se pudo conectar con el servidor. Inténtalo de nuevo más tarde.',
            confirmButtonColor: '#ff934f'
        });
    }
}
