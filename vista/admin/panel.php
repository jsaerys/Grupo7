<script>
// Función para cerrar sesión
function autoLogout() {
    fetch('../../controlador/autologout.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Sesión cerrada automáticamente');
            }
        })
        .catch(error => console.error('Error en auto logout:', error));
}

// Detectar cuando la pestaña/navegador se cierra
window.addEventListener('beforeunload', function(e) {
    autoLogout();
});

// Detectar cuando el navegador se cierra
window.addEventListener('unload', function() {
    autoLogout();
});
</script> 