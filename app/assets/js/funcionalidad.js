document.addEventListener('DOMContentLoaded', function () {
    const eliminarCuentaLink = document.getElementById('eliminarCuentaLink');
    const politicasLink = document.getElementById('politicasLink');
    const eliminarCuentaModal = document.getElementById('eliminarCuentaModal');
    const politicasModal = document.getElementById('politicasModal');

    // Abrir modal de eliminar cuenta
    if (eliminarCuentaLink && eliminarCuentaModal) {
        eliminarCuentaLink.addEventListener('click', function (e) {
            e.preventDefault();
            eliminarCuentaModal.style.display = 'block';
        });
    }

    // Abrir modal de políticas
    if (politicasLink && politicasModal) {
        politicasLink.addEventListener('click', function (e) {
            e.preventDefault();
            politicasModal.style.display = 'block';
        });
    }

    // Cerrar modales al hacer clic fuera de ellos
    window.addEventListener('click', function (event) {
        if (event.target === eliminarCuentaModal) {
            eliminarCuentaModal.style.display = 'none';
        }
        if (event.target === politicasModal) {
            politicasModal.style.display = 'none';
        }
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // ✅ Código del modal de mensajes corregido y movido aquí
    var modal = document.getElementById('mensajeModal');
    var openModalBtn = document.getElementById('openModalBtn');
    var closeModal = document.getElementsByClassName('close')[0];
    var mensajesContainer = document.getElementById('mensajesContainer');

    if (openModalBtn) {
        openModalBtn.onclick = function () {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        mensajesContainer.innerHTML = xhr.responseText;
                        modal.style.display = 'block';
                        marcarMensajesLeidos();
                    } else {
                        alert('Hubo un problema al cargar los mensajes.');
                    }
                }
            };
            xhr.open('GET', '/app/include/mostrar_mensajes.php', true);
            xhr.send();
        };
    }

    if (closeModal) {
        closeModal.onclick = function () {
            modal.style.display = 'none';
        };
    }
});

function marcarMensajesLeidos() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/app/include/marcar_mensajes_leidos.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send();
}


// funcion ajax para verificar los documentos y regirigir 
function mostrarAlerta(event) {
    event.preventDefault(); // Prevenir el comportamiento por defecto del enlace

    console.log('Iniciando función mostrarAlerta');

    // Verificar que el userId esté definido correctamente
    if (!userId) {
        console.log('Error: userId no está definido');
        return;
    }

    // Llamada AJAX para verificar si los documentos ya fueron subidos
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/app/include/verificar_documentos.php", true); // Cambia la ruta según tu estructura
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        console.log('Estado readyState:', xhr.readyState);
        console.log('Estado de la solicitud:', xhr.status);

        if (xhr.readyState == 4 && xhr.status == 200) {
            var respuesta = xhr.responseText.trim(); // Asegúrate de que no haya espacios en blanco

            console.log('Respuesta del servidor:', respuesta);

            // Si los documentos ya están subidos
            if (respuesta === "Ya has subido tus documentos") {
                console.log('Redirigiendo a la página de aceptar solicitudes');
                window.location.href = '/app/pages/sermototaxista.php'; // Redirigir a la página de aceptar solicitudes
            } else if (respuesta === "No has subido tus documentos") {
                console.log('Mostrando confirmación para subir documentos');
                var confirmacion = confirm("Es importante llenar el formulario con los documentos de tu vehículo para prestar el servicio de mototaxi. ¿Deseas continuar?");
                if (confirmacion) {
                    window.location.href = '/app/pages/registro_de_documentos.php'; // Redirigir al formulario de documentos
                }
            } else {
                console.log('Respuesta inesperada:', respuesta); // Mostrar mensaje si la respuesta no coincide
            }
        }
    };

    // Enviar la solicitud al servidor con el ID del usuario
    xhr.send("userId=" + encodeURIComponent(userId)); // Asegúrate de que userId esté disponible en el frontend
}



// Ocultar mensaje después de 5 segundos si existe
document.addEventListener('DOMContentLoaded', function() {
    var mensajeDiv = document.getElementById('mensaje-solicitante');
    if (mensajeDiv) {
        setTimeout(function() {
            mensajeDiv.style.display = 'none';
        }, 5000);
    }
});



// Modal de configuración: eliminar cuenta y mostrar políticas
document.addEventListener('DOMContentLoaded', function() {
    const eliminarCuentaLink = document.getElementById('eliminarCuentaLink');
    const politicasLink = document.getElementById('politicasLink');
    const eliminarCuentaModal = document.getElementById('eliminarCuentaModal');
    const politicasModal = document.getElementById('politicasModal');

    // Abrir modal "Eliminar cuenta"
    eliminarCuentaLink.addEventListener('click', function(e) {
        e.preventDefault();
        eliminarCuentaModal.style.display = 'block';
    });

    // Abrir modal "Políticas y privacidad"
    politicasLink.addEventListener('click', function(e) {
        e.preventDefault();
        politicasModal.style.display = 'block';
    });

    // Cerrar modales al hacer clic en la 'X'
    const closeButtons = document.getElementsByClassName('close');
    for (let i = 0; i < closeButtons.length; i++) {
        closeButtons[i].addEventListener('click', function() {
            eliminarCuentaModal.style.display = 'none';
            politicasModal.style.display = 'none';
        });
    }

    // Cerrar modales al hacer clic fuera del contenido
    window.onclick = function(event) {
        if (event.target == eliminarCuentaModal) {
            eliminarCuentaModal.style.display = 'none';
        }
        if (event.target == politicasModal) {
            politicasModal.style.display = 'none';
        }
    };
});

// Ocultar mensaje de error después de 5 segundos
setTimeout(function() {
    var errorMessage = document.getElementById('error-message');
    if (errorMessage) {
        errorMessage.style.display = 'none';
    }
}, 5000);



