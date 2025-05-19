document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const mensajeDiv = document.getElementById('mensaje');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        const data = {
            correo: formData.get('correo'),
            contraseña: formData.get('contraseña')
        };

        try {
            const response = await fetch('/Mysiteart/public/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                mostrarMensaje(result.message, 'success');
                // Redirigir al dashboard después de 2 segundos
                setTimeout(() => {
                    window.location.href = result.redirect || 'dashboard.html';
                }, 2000);
            } else {
                mostrarMensaje(result.message, 'error');
            }
        } catch (error) {
            mostrarMensaje('Error al procesar la solicitud', 'error');
            console.error('Error:', error);
        }
    });

    function mostrarMensaje(mensaje, tipo) {
        mensajeDiv.textContent = mensaje;
        mensajeDiv.className = `mensaje ${tipo}`;
    }
}); 