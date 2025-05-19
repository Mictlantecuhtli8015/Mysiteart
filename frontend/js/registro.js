document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registroForm');
    const errorMessage = document.getElementById('errorMessage');
    const debugInfo = document.getElementById('debugInfo');
    const passwordInput = document.getElementById('contraseña');
    const confirmPasswordInput = document.getElementById('confirmar_contraseña');
    const passwordRequirements = document.getElementById('passwordRequirements');

    // Función para validar la contraseña
    function validatePassword(password) {
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password)
        };

        // Actualizar indicadores visuales
        document.getElementById('length').className = requirements.length ? 'valid' : 'invalid';
        document.getElementById('uppercase').className = requirements.uppercase ? 'valid' : 'invalid';
        document.getElementById('lowercase').className = requirements.lowercase ? 'valid' : 'invalid';
        document.getElementById('number').className = requirements.number ? 'valid' : 'invalid';

        return Object.values(requirements).every(Boolean);
    }

    // Función para mostrar/ocultar contraseña
    function togglePasswordVisibility(inputId, buttonId) {
        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);
        
        button.addEventListener('click', function() {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            button.innerHTML = type === 'password' ? 
                '<i class="fas fa-eye"></i>' : 
                '<i class="fas fa-eye-slash"></i>';
        });
    }

    // Inicializar toggles de contraseña
    togglePasswordVisibility('contraseña', 'togglePassword');
    togglePasswordVisibility('confirmar_contraseña', 'toggleConfirmPassword');

    // Validar contraseña en tiempo real
    passwordInput.addEventListener('input', function() {
        validatePassword(this.value);
    });

    // Validar coincidencia de contraseñas
    confirmPasswordInput.addEventListener('input', function() {
        const match = this.value === passwordInput.value;
        this.setCustomValidity(match ? '' : 'Las contraseñas no coinciden');
    });

    // Manejar envío del formulario
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Ocultar mensajes anteriores
        errorMessage.style.display = 'none';
        debugInfo.style.display = 'none';

        // Validar contraseña
        if (!validatePassword(passwordInput.value)) {
            errorMessage.textContent = 'La contraseña no cumple con los requisitos mínimos';
            errorMessage.style.display = 'block';
            return;
        }

        // Validar coincidencia de contraseñas
        if (passwordInput.value !== confirmPasswordInput.value) {
            errorMessage.textContent = 'Las contraseñas no coinciden';
            errorMessage.style.display = 'block';
            return;
        }

        // Recopilar datos del formulario
        const formData = {
            nombre: document.getElementById('nombre').value,
            correo: document.getElementById('correo').value,
            contraseña: passwordInput.value,
            tipo_usuario: document.getElementById('tipo_usuario').value
        };

        try {
            console.log('Enviando solicitud de registro...');
            const response = await fetch('/Mysiteart/public/registro.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            console.log('Respuesta recibida:', response);
            const data = await response.json();
            console.log('Datos recibidos:', data);

            if (data.success) {
                // Mostrar mensaje de éxito
                errorMessage.textContent = data.message;
                errorMessage.style.color = '#27ae60';
                errorMessage.style.display = 'block';

                // Redirigir después de 2 segundos
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 2000);
            } else {
                // Mostrar mensaje de error
                errorMessage.textContent = data.message;
                errorMessage.style.color = '#e74c3c';
                errorMessage.style.display = 'block';

                // Mostrar información de depuración si está disponible
                if (data.debug) {
                    debugInfo.innerHTML = `
                        <strong>Información de depuración:</strong><br>
                        <pre>${JSON.stringify(data.debug, null, 2)}</pre>
                    `;
                    debugInfo.style.display = 'block';
                }
            }
        } catch (error) {
            console.error('Error:', error);
            errorMessage.textContent = 'Error al conectar con el servidor';
            errorMessage.style.display = 'block';
            
            debugInfo.innerHTML = `
                <strong>Error:</strong><br>
                <pre>${error.message}</pre>
            `;
            debugInfo.style.display = 'block';
        }
    });
}); 