document.addEventListener('DOMContentLoaded', function() {
    const menuIcon = document.getElementById('menuIcon');
    const closeIcon = document.getElementById('closeIcon');
    const barraLateral = document.getElementById('barraLateral');

    menuIcon.addEventListener('click', function() {
        barraLateral.style.display = 'block';
        menuIcon.style.display = 'none';
        closeIcon.style.display = 'block';
    });

    closeIcon.addEventListener('click', function() {
        barraLateral.style.display = 'none';
        menuIcon.style.display = 'block';
        closeIcon.style.display = 'none';
    });

    
});

document.addEventListener('DOMContentLoaded', function () {
    const modoOscuroSwitch = document.querySelector('.modo-oscuro .switch');
    const body = document.body;

    // Inicializar el modo oscuro según el estado guardado en localStorage
    if (localStorage.getItem('modoOscuro') === 'enabled') {
        body.classList.add('modo-oscuro');
    }

    // Manejar el cambio de modo oscuro
    modoOscuroSwitch.addEventListener('click', function () {
        if (body.classList.contains('modo-oscuro')) {
            body.classList.remove('modo-oscuro');
            localStorage.setItem('modoOscuro', 'disabled');
        } else {
            body.classList.add('modo-oscuro');
            localStorage.setItem('modoOscuro', 'enabled');
        }
    });
});



document.addEventListener('DOMContentLoaded', function () {
    const accessibilityIcon = document.getElementById('accessibility-icon');
    const accessibilityPanel = document.getElementById('accessibility-panel');

    // Mostrar/Ocultar panel de accesibilidad
    accessibilityIcon.addEventListener('click', function () {
        if (accessibilityPanel.style.display === 'none') {
            accessibilityPanel.style.display = 'block';
        } else {
            accessibilityPanel.style.display = 'none';
        }
    });

    // Comprobar si el usuario ya configuró el contraste o el tamaño de texto
    if (localStorage.getItem('highContrast') === 'enabled') {
        document.body.classList.add('high-contrast');
    }

    if (localStorage.getItem('largeText') === 'enabled') {
        document.body.classList.add('large-text');
    }

    // Aumentar tamaño de texto
    document.getElementById('decreaseText').addEventListener('click', function () {
        document.body.classList.add('large-text');
        localStorage.setItem('largeText', 'enabled');
    });

    // Disminuir tamaño de texto
    document.getElementById('increaseText').addEventListener('click', function () {
        document.body.classList.remove('large-text');
        localStorage.setItem('largeText', 'disabled');
    });

    // Cerrar el panel si se hace clic fuera de él
    document.addEventListener('click', function (event) {
        const isClickInside = accessibilityPanel.contains(event.target) || accessibilityIcon.contains(event.target);
        if (!isClickInside) {
            accessibilityPanel.style.display = 'none';
        }
    });
});

