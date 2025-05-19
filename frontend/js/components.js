// Función para cargar componentes
async function loadComponent(componentPath, containerId) {
    try {
        const response = await fetch(componentPath);
        const html = await response.text();
        const container = document.getElementById(containerId);
        if (container) {
            container.innerHTML = html;
        }
    } catch (error) {
        console.error(`Error al cargar el componente ${componentPath}:`, error);
    }
}

// Función para inicializar los componentes
function initializeComponents() {
    // Cargar los componentes
    loadComponent('components/cart.html', 'cart-container');
    loadComponent('components/profile.html', 'profile-container');

    // Agregar event listeners a los botones después de que se cargue la barra de navegación
    document.addEventListener('click', function(e) {
        // Manejar clic en el carrito
        if (e.target.closest('[data-bs-target="#cartOffcanvas"]')) {
            const cartOffcanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanvas'));
            cartOffcanvas.show();
        }
        
        // Manejar clic en el perfil
        if (e.target.closest('[data-bs-target="#profileOffcanvas"]')) {
            const profileOffcanvas = new bootstrap.Offcanvas(document.getElementById('profileOffcanvas'));
            profileOffcanvas.show();
        }
    });
}

// Esperar a que el DOM esté listo
document.addEventListener('DOMContentLoaded', initializeComponents); 