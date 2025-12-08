document.addEventListener('DOMContentLoaded', () => {

    // 1. Obtener todos los enlaces de la navbar y las secciones de contenido
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const contentSections = document.querySelectorAll('.content-section');

    // Función para manejar el clic en los enlaces de la navbar
    navLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Detiene el comportamiento predeterminado del enlace

            const targetId = this.getAttribute('data-target'); // Obtiene el ID de la sección a mostrar

            // Oculta TODAS las secciones de contenido
            contentSections.forEach(section => {
                section.style.display = 'none';
                section.classList.remove('active-content');
            });

            // Muestra la sección TARGET
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                // Usamos 'flex' si es la sección hero, sino usamos 'block' (o lo que se defina en CSS)
                if (targetSection.classList.contains('hero')) {
                    targetSection.style.display = 'flex';
                } else {
                    targetSection.style.display = 'block';
                }
                targetSection.classList.add('active-content');

                // Opcional: Desplazar la vista al inicio de la sección (por si el contenido es corto)
                targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            // Actualiza la clase 'active' de la navbar
            navLinks.forEach(nav => nav.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // 2. Lógica para el botón 'EXPLORE'
    const exploreButton = document.querySelector('.btn-explore');

    if (exploreButton) {
        exploreButton.addEventListener('click', () => {
             alert('Explora el mundo del juego 🎮');
             // Opcional: Puedes redirigir a otra pestaña después del alert
             // document.querySelector('[data-target="historia"]').click();
        });
    }

    // 3. Inicialización: Asegurar que solo la sección 'Presentación' esté visible al cargar
    contentSections.forEach(section => {
        if (!section.classList.contains('active-content')) {
            section.style.display = 'none';
        }
    });
});