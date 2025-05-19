// Función para cargar el componente de navegación
async function loadNavbar() {
    try {
        const response = await fetch('components/navbar.html');
        const html = await response.text();
        document.getElementById('navbar-container').innerHTML = html;
        // Re-inicializar dropdowns de Bootstrap si existen
        if (window.bootstrap) {
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        }
    } catch (error) {
        console.error('Error al cargar la barra de navegación:', error);
    }
}

// Cargar la barra de navegación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', loadNavbar); 