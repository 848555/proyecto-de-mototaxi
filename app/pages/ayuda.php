<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Ayuda</title>
    <link rel="stylesheet" href="/app/assets/css/(ayuda)style.css">
    <script>
        // Función para subir al inicio de la página
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Centro de Ayuda</h1>
            <p>Encuentra respuestas a las preguntas más frecuentes y guías para ayudarte a aprovechar al máximo nuestra aplicación.</p>
        </header>
        
        <nav>
            <ul>
                <li><a href="/app/pages/inicio.php">Regresar</a></li>
                <li><a href="#primeros-pasos">Primeros Pasos</a></li>
                <li><a href="#uso-aplicacion">Uso de la Aplicación</a></li>
                <li><a href="#mototaxistas">Mototaxistas</a></li>
                <li><a href="#pagos">Pagos</a></li>
                <li><a href="#soporte">Soporte</a></li>
                
            </ul>
        </nav>
        
        <main>
            <section id="primeros-pasos">
                <h2>Primeros Pasos</h2>
                <h3>¿Cómo registrarse?</h3>
                <p>1. Ve a la página principal de la aplicación.</p>
                <p>2. Haz clic en el botón "Registrarse".</p>
                <p>3. Completa el formulario con tus datos personales.</p>
                <p>4. y listo te saldra un mensaje de registro exitoso.</p>

                <h3>¿Cómo iniciar sesión?</h3>
                <p>1. Ve a la página principal de la aplicación.</p>
                <p>2. Haz clic en el botón "Iniciar Sesión".</p>
                <p>3. Introduce tu usuario y contraseña.</p>
                <p>4. Haz clic en "Entrar".</p>

            </section>
            
            <section id="uso-aplicacion">
                <h2>Uso de la Aplicación</h2>
                <h3>¿Cómo solicitar un servicio?</h3>
                <p>1. Inicia sesión en la aplicación.</p>
                <p>2. Selecciona la opción "Solicitar Servicio" en el menú principal.</p>
                <p>3. Introduce los detalles del origen, destino y otros parámetros requeridos.</p>
                <p>4. Selecciona el método de pago y confirma la solicitud.</p>

                <h3>¿Cómo pagar por un servicio?</h3>
                <p>1. Durante la solicitud del servicio, selecciona el método de pago preferido (Efectivo o Nequi).</p>
                <p>2. Si seleccionaste Nequi, presiona el icono de tres lineas y selecciona pagar, te llevara a nequi alli sigue las instrucciones para completar el pago a través de la plataforma Nequi.</p>
                <p>3. Recibirás una confirmación del pago de nequi y el mensaje de pago exitoso.</p>
            </section>
            
            <section id="mototaxistas">
                <h2>Mototaxistas</h2>
                <h3>¿Cómo convertirse en mototaxista?</h3>
                <p>1. Inicia sesión en la aplicación.</p>
                <p>2. En el menú principal, selecciona "Quiero ser mototaxista".</p>
                <p>3. te saldra un mensaje donde te informa que debes subir los documentos, presiona aceptar.</p>
                <p>3. Completa el formulario con la información requerida.</p>
                <p>4. si el registro fue exitoso te llevara al apartado para aceptar las solicitudes.</p>

                <h3>¿Cómo aceptar un servicio?</h3>
                <p>1. Una vez registrado como mototaxista, inicia sesión en la aplicación.</p>
                <p>2. Ve a la sección de "quiero ser mototaxista".</p>
                <p>3. Selecciona el servicio que deseas aceptar y aceptalo.</p>
            </section>
            
            <section id="pagos">
                <h2>Pagos</h2>
                <h3>Métodos de pago disponibles</h3>
                <p>Actualmente, ofrecemos los siguientes métodos de pago:</p>
                <ul>
                    <li>Efectivo</li>
                    <li>Nequi</li>
                </ul>

                <h3>¿Cómo funciona el pago con Nequi?</h3>
                <p>1. Selecciona Nequi como método de pago al solicitar un servicio.</p>
                <p>2. una vez se te sea prestado el servicio deberas proeder con el pago.</p>
                <p>3. para eso vez a las tres lineas que aparece en el apartado principal del lado derecho y selecciona pagar.</p>
                <p>2. escribe tu  numero nequi y se te redireccionara a nequi para que realices el pago sigue el proceso de pago de nequi.</p>
                
                <h3>¿Cómo se gestionan las retenciones?</h3>
                <p>Las retenciones se aplican de acuerdo con las políticas de la aplicación. Puedes ver  tus retenciones en la sección de "Retenciones" de tu perfil.</p>
            </section>
            
            <section id="soporte">
                <h2>Soporte</h2>
                <h3>Contacto y soporte técnico</h3>
                <p>Si necesitas ayuda adicional, puedes contactarnos a través de:</p>
                <ul>
                    <li>Correo electrónico: soporte@tuapp.com</li>
                    <li>Teléfono: +123 456 7890</li>
                </ul>
                <p>Estamos disponibles de lunes a viernes, de 9:00 a 18:00.</p>
            </section>
        </main>
        <button onclick="scrollToTop()" id="scrollToTopBtn" title="Subir">
        &#8679;
    </button>
     <!-- Ícono de accesibilidad para abrir el panel -->
     <div id="accessibility-icon">
    <ion-icon name="accessibility-outline"></ion-icon>
    </div>

    <!-- Controles de accesibilidad, ocultos inicialmente -->
    <div id="accessibility-panel" class="accessibility-controls" style="display: none;">
        <button id="increaseText">Aumentar letra</button>
        <button id="decreaseText">Disminuir letra</button>
    </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="/app/assets/js/script.js"></script>
</body>
</html>
