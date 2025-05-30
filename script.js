document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const registerButton = document.getElementById('registerButton');

    if (loginForm) {
        loginForm.addEventListener('submit', (event) => {
            event.preventDefault(); // Prevent actual form submission for now
            const username = event.target.username.value;
            const password = event.target.password.value;

            if (username && password) {
                alert(`Intento de Ingreso:\nUsuario: ${username}\nContraseña: ${password}\n(Funcionalidad de login no implementada)`);
                // Here you would typically send data to a server
            } else {
                alert('Por favor, completa todos los campos.');
            }
        });
    }

    if (registerButton) {
        registerButton.addEventListener('click', () => {
            alert('Botón de Registrarse presionado.\n(Funcionalidad de registro no implementada)');
            // Here you would typically redirect to a registration page or show a registration modal
        });
    }

    console.log("Script de login cargado.");
});
// ...existing code...

// --- Panel principal: navegación de secciones ---
document.addEventListener('DOMContentLoaded', () => {
    const navButtons = document.querySelectorAll('.nav-button[data-target]');
    const sections = document.querySelectorAll('.panel-section');

    navButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Quitar clase activa de todos los botones
            navButtons.forEach(btn => btn.classList.remove('active-nav-button'));
            this.classList.add('active-nav-button');

            // Ocultar todas las secciones
            sections.forEach(section => section.classList.remove('active-section'));
            // Mostrar la sección correspondiente
            const target = this.getAttribute('data-target');
            document.getElementById(target).classList.add('active-section');
        });
    });
});

