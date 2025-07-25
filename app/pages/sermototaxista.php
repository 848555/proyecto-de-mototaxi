<?php
session_start();
include(__DIR__ . '../../../config/conexion.php');

// Verificar si el usuario tiene acceso al contenido de esta página
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 2) {
    header("Location: ../../../../index.php");
    exit;
}

$user_id = $_SESSION['id_usuario'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/app/assets/css/sermototaxista.css"> <!-- Enlace a estilos CSS -->

    <title>Aceptar servicio</title>
</head>

<body>

    <div class="contenedor">
        <div class="estado-en-linea">
    <button id="toggleOnlineBtn" class="boton-estado">Conectarse</button>
    <p id="estadoTexto">Estado: Desconectado</p>
</div>

        <form id="verSolicitudes" action="/app/include/aceptar_solicitud.php" method="post">
        <a href="/app/pages/inicio.php?id_usuario=<?php echo $_SESSION['id_usuario']; ?>&uid=<?php echo uniqid(); ?>">
        <img src="/app/assets/imagenes/images.png" alt="" class="retroceder">
            </a>
            <h1>Solicitudes Por Aceptar</h1><br>
            <?php
            if (isset($_SESSION['success_message'])) {
                echo "<div id='success-message' class='alert-message alert-message-success'>";
                echo $_SESSION['success_message'];
                echo "</div>";
                unset($_SESSION['success_message']);
            }
            if (isset($_SESSION['success_mensaje'])) {
                echo "<div id='success-mensaje' class='alert-mensaje alert-mensaje-success'>";
                echo $_SESSION['success_mensaje'];
                echo "</div>";
                unset($_SESSION['success_mensaje']);
            }
            if (isset($_SESSION['error_message'])) {
                echo "<div id='error-message' class='alert-message alert-message-error'>";
                echo $_SESSION['error_message'];
                echo "</div>";
                unset($_SESSION['error_message']);
            }
            if (isset($_SESSION['warning_message'])) {
                echo "<p style='color: blue;'>" . $_SESSION['warning_message'] . "</p>";
                unset($_SESSION['warning_message']);
            }
            ?>
    </div>

    <div class="table-container" id="solicitudes-container">
        <!-- Aquí se cargarán las solicitudes -->
    </div>

    <div class="pagination" id="pagination">
        <!-- Aquí se cargarán los enlaces de paginación -->
    </div>

    </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successMessage = document.getElementById('success-message');
            var errorMessage = document.getElementById('error-message');

            if (successMessage) {
                successMessage.style.display = 'block';
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 5000);
            }

            if (errorMessage) {
                errorMessage.style.display = 'block';
                setTimeout(function() {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
        });
    </script>

    <script>
        function fetchSolicitudes(page = 1) {
            const solicitudesContainer = document.getElementById('solicitudes-container');
            const paginationContainer = document.getElementById('pagination');

            fetch(`/app/include/obtener_solicitudes.php?page=${page}`)
                .then(response => response.json())
                .then(data => {
                    solicitudesContainer.innerHTML = '';

                    if (data.solicitudes.length > 0) {
                        data.solicitudes.forEach(row => {
                            const solicitudDiv = document.createElement('div');
                            solicitudDiv.classList.add('solicitud');
                            solicitudDiv.innerHTML = `
                                <h3>${row.Nombres}</h3>
                                <h3>${row.Apellidos}</h3>
                                <p><strong>Origen:</strong> ${row.origen}</p>
                                <p><strong>Destino:</strong> ${row.destino}</p>
                                <p><strong>Cantidad Personas:</strong> ${row.cantidad_personas}</p>
                                <p><strong>Cantidad Motos:</strong> ${row.cantidad_motos}</p>
                                <p><strong>Método de Pago:</strong> ${row.metodo_pago}</p>
                                <p>
                                    <a  href='/app/include/aceptar_solicitud.php?id_solicitud=${row.id_solicitud}&id_usuario=${row.id_usuarios}'>Aceptar Solicitud</a>
                                </p>
                                <p>
                                    <button class="btn1" onclick="openCalificarModal(${row.id_solicitud}, ${row.id_usuarios})">Cliente Ausente</button>
                                </p>
                                <p>
                                    <button class="btn2" onclick="terminarServicio(${row.id_solicitud})">Terminar Servicio</button>
                                </p>
                            `;
                            solicitudesContainer.appendChild(solicitudDiv);
                        });
                    } else {
                        solicitudesContainer.innerHTML = '<p>No se encontraron registros.</p>';
                    }

                    paginationContainer.innerHTML = '';
                    if (data.total_pages > 1) {
                        for (let i = 1; i <= data.total_pages; i++) {
                            const pageLink = document.createElement('a');
                            pageLink.classList.add('page-link');
                            if (i === page) pageLink.classList.add('active');
                            pageLink.href = `javascript:fetchSolicitudes(${i})`;
                            pageLink.textContent = i;
                            paginationContainer.appendChild(pageLink);
                        }
                    }
                })
                .catch(error => console.error('Error fetching solicitudes:', error));
        }

        document.addEventListener('DOMContentLoaded', function() {
            fetchSolicitudes();
            setInterval(fetchSolicitudes, 5 * 60 * 1000);
        });

        function terminarServicio(idSolicitud) {
    console.log("ID Solicitud:", idSolicitud); // Verifica que se está llamando
    document.getElementById('id_solicitud_terminar').value = idSolicitud;
    document.getElementById('terminarServicioModal').style.display = 'block'; 
}



function closeTerminarServicioModal() {
        document.getElementById('terminarServicioModal').style.display = 'none';
    }

    // Cerrar modal al hacer clic fuera de él
    document.addEventListener('DOMContentLoaded', function() {
        const terminarServicioModal = document.getElementById('terminarServicioModal');

        terminarServicioModal.addEventListener('click', function(event) {
            // Verificar si se hizo clic en el fondo del modal
            if (event.target === terminarServicioModal) {
                closeTerminarServicioModal();
            }
        });
    });



    </script>
    <!-- Modal para terminar servicio -->
    <div id="terminarServicioModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Terminar Servicio</h2>
            <form id="terminarServicioForm" action="/app/include/terminar_servicio.php" method="POST">
                <input type="hidden" id="id_solicitud_terminar" name="id_solicitud_terminar">
                <label for="pago_completo">¿Le pagaron el servicio?</label>
                <select id="pago_completo" name="pago_completo">
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>

                <label for="cliente_ausente">¿El cliente estuvo ausente?</label>
                <select id="cliente_ausente" name="cliente_ausente">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>

                <button type="submit"  class="btn1">Confirmar</button>
                <button type="button" onclick="closeTerminarServicioModal()" class="btn1">Cancelar</button>
            </form>
        </div>
    </div>



    <!-- Modal para calificar al cliente -->
    <div id="calificarClienteModal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Calificar Cliente</h2>
            <form id="calificarClienteForm">
                <input type="hidden" id="id_solicitud" name="id_solicitud">
                <input type="hidden" id="id_usuarios" name="id_usuarios">

                <label for="rating">Calificación:</label>
                <select id="rating" name="rating">
                    <option value="5">Excelente</option>
                    <option value="4">Muy Bueno</option>
                    <option value="3">Bueno</option>
                    <option value="2">Regular</option>
                    <option value="1">Malo</option>
                </select>

                <label for="comentarios">Comentarios:</label>
                <textarea id="comentarios" name="comentarios"></textarea>

                <button type="submit" class="btn1">Enviar Calificación</button>
                <button type="button" onclick="closeCalificarModal()" class="btn1">Cancelar</button>
            </form>
        </div>
    </div>

    <script>
        function openCalificarModal(idSolicitud, idUsuarios) {
            document.getElementById('id_solicitud').value = idSolicitud;
            document.getElementById('id_usuarios').value = idUsuarios;
            document.getElementById('calificarClienteModal').style.display = 'block';
        }

        function closeCalificarModal() {
            document.getElementById('calificarClienteModal').style.display = 'none';
        }

        document.getElementById('calificarClienteForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const idSolicitud = document.getElementById('id_solicitud').value;
            const idUsuarios = document.getElementById('id_usuarios').value;
            const rating = document.getElementById('rating').value;
            const comentarios = document.getElementById('comentarios').value;

            fetch('/app/include/calificar_cliente.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id_solicitud: idSolicitud,
                        id_usuarios: idUsuarios,
                        rating: rating,
                        comentarios: comentarios
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Calificación enviada correctamente');
                        closeCalificarModal();
                        fetchSolicitudes();
                    } else {
                        alert('Error al enviar la calificación');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
    <!-- Ícono de accesibilidad para abrir el panel -->
    <div id="accessibility-icon">
        <ion-icon name="accessibility-outline"></ion-icon>
    </div>

    <!-- Controles de accesibilidad, ocultos inicialmente -->
    <div id="accessibility-panel" class="accessibility-controls" style="display: none;">
        <button id="increaseText">Aumentar letra</button>
        <button id="decreaseText">Disminuir letra</button>
    </div>
</body>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="/app/assets/js/script.js"></script>
<script src="/app/assets/js/funcionalidad.js"></script>
<script>
function escucharAsignaciones() {
    fetch('/app/include/asignar_solicitudes.php')
    .then(res => res.json())
    .then(data => {
        if (data.asignada && data.id_usuario == <?php echo $user_id; ?>) {
            if (confirm(`Tienes una solicitud de ${data.solicitud.origen} a ${data.solicitud.destino}. ¿Aceptar?`)) {
                window.location.href = `/app/include/aceptar_solicitud.php?id_solicitud=${data.solicitud.id_solicitud}&id_usuario=${data.solicitud.id_usuarios}`;
            }
        }
    })
    .catch(console.error);
}

setInterval(escucharAsignaciones, 10000); // cada 10 segundos
</script>

</html>