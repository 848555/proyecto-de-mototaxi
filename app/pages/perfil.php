<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Incluir la conexión a la base de datos
include(__DIR__ . '../../../config/conexion.php');

// Obtener el ID del usuario desde la sesión
$id_usuario = $_SESSION['id_usuario']; // Obtener el ID del usuario

// Verificar si se envió el formulario para actualizar los datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $telefono = $_POST['telefono'];
    $departamento = $_POST['departamento'];
    $ciudad = $_POST['ciudad'];
    $direccion = $_POST['direccion'];
    $usuario = $_POST['usuario'];
    $nueva_contraseña = $_POST['nueva_contraseña'];

    // Si se ha ingresado una nueva contraseña, actualizarla
    if (!empty($nueva_contraseña)) {
        $nueva_contraseña = password_hash($nueva_contraseña, PASSWORD_DEFAULT); // Encriptar la contraseña
        // Consulta para actualizar los datos incluyendo la nueva contraseña
        $query_actualizacion = "UPDATE usuarios 
                                SET telefono = ?, Departamento = ?, Ciudad = ?, Direccion = ?, Usuario = ?, Password = ?
                                WHERE id_usuarios = ?";

        // Preparar la consulta
        $stmt_actualizacion = $conexion->prepare($query_actualizacion);

        if (!$stmt_actualizacion) {
            die('Error al preparar la consulta: ' . $conexion->error);
        }

        // Enlazar los parámetros, agregando la nueva contraseña
        $stmt_actualizacion->bind_param("ssssssi", $telefono, $departamento, $ciudad, $direccion, $usuario, $nueva_contraseña, $id_usuario);
    } else {
        // Consulta para actualizar los datos sin la nueva contraseña
        $query_actualizacion = "UPDATE usuarios 
                                SET telefono = ?, Departamento = ?, Ciudad = ?, Direccion = ?, Usuario = ?
                                WHERE id_usuarios = ?";

        // Preparar la consulta
        $stmt_actualizacion = $conexion->prepare($query_actualizacion);

        if (!$stmt_actualizacion) {
            die('Error al preparar la consulta: ' . $conexion->error);
        }

        // Enlazar los parámetros sin la nueva contraseña
        $stmt_actualizacion->bind_param("sssssi", $telefono, $departamento, $ciudad, $direccion, $usuario, $id_usuario);
    }

    // Ejecutar la consulta
    if ($stmt_actualizacion->execute()) {
        $_SESSION['success_mensaje'] = "¡Datos actualizados con éxito!";
    } else {
        $_SESSION['mensaje'] = ['tipo' => 'error', 'mensaje' => 'Error al actualizar los datos: ' . $stmt_actualizacion->error]; // Mensaje de error
    }

    $stmt_actualizacion->close();

    // Redirigir para evitar que el formulario se envíe nuevamente al actualizar la página
    header("Location: perfil.php");
    exit();
}

// Consulta para obtener los datos del perfil del usuario junto con el nombre del departamento y la ciudad
$query = "SELECT u.*, d.departamentos AS nombre_departamento, c.ciudades AS nombre_ciudad 
          FROM usuarios u
          LEFT JOIN departamentos d ON u.Departamento = d.id_departamentos
          LEFT JOIN ciudades c ON u.Ciudad = c.id_ciudades
          WHERE u.id_usuarios = ?";

$stmt = $conexion->prepare($query);

if (!$stmt) {
    die('Error al preparar la consulta: ' . $conexion->error);
}

// Enlazar el parámetro y ejecutar la consulta
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontraron resultados
if ($result->num_rows > 0) {
    $perfil_usuario = $result->fetch_assoc();
} else {
    $perfil_usuario = null;
}

$stmt->close();

// Obtener la lista de departamentos
$query_departamentos = "SELECT * FROM departamentos";
$resultado_departamentos = $conexion->query($query_departamentos);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/app/assets/css/perfil_style.css">
    <title>Perfil de Usuario</title>
    <style>

    </style>
</head>

<body>
    <div class="container">
        <a href="/app/pages/inicio.php?uid=<?php echo $_SESSION['id_usuario']; ?>&php=<?php echo uniqid(); ?>">
            <img src="/app/assets/imagenes/images.png" alt="Regresar" class="retroceder">
        </a>
        <h2>Perfil <?php echo $_SESSION['usuario']; ?></h2>

        <?php
        // Mostrar mensajes de éxito y error si existen
        if (isset($_SESSION['success_mensaje'])) {
            echo "<div id='success-mensaje' class='alert-message alert-message-success'>";
            echo $_SESSION['success_mensaje'];
            echo "</div>";
            unset($_SESSION['success_mensaje']); // Limpiar el mensaje después de mostrarlo
        }

        if (isset($_SESSION['error_message'])) {
            echo "<div id='error-message' class='alert-message alert-message-error'>";
            echo $_SESSION['error_message'];
            echo "</div>";
            unset($_SESSION['error_message']); // Limpiar el mensaje después de mostrarlo
        }
        ?>

        <?php if ($perfil_usuario): ?>
            <form action="perfil.php" method="POST">
                <p><strong>Nombres:</strong> <?php echo $perfil_usuario['Nombres']; ?></p>
                <p><strong>Apellidos:</strong> <?php echo $perfil_usuario['Apellidos']; ?></p>
                <p><strong>DNI:</strong> <?php echo $perfil_usuario['DNI']; ?></p>
                <p><strong>Fecha de Nacimiento:</strong> <?php echo $perfil_usuario['fecha_de_nacimiento']; ?></p>
                <p><strong>Teléfono:</strong>
                    <input type="text" name="telefono" value="<?php echo $perfil_usuario['telefono']; ?>" required>
                </p>
                <div class="mb-3">
                    <label for="departamento" class="form-label">Departamento</label>
                    <select name="departamento" id="departamento" class="form-select" onchange="getCiudades()">
                        <option value="<?php echo $perfil_usuario['Departamento']; ?>" selected><?php echo $perfil_usuario['nombre_departamento']; ?></option>
                        <?php while ($departamento = $resultado_departamentos->fetch_assoc()) { ?>
                            <option value="<?php echo $departamento['id_departamentos']; ?>">
                                <?php echo $departamento['departamentos']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="ciudad" class="form-label">Ciudad</label>
                    <select name="ciudad" id="ciudad" class="form-select">
                        <option value="<?php echo $perfil_usuario['Ciudad']; ?>" selected><?php echo $perfil_usuario['nombre_ciudad']; ?></option>
                    </select>
                </div>
                <p><strong>Dirección:</strong>
                    <input type="text" name="direccion" value="<?php echo $perfil_usuario['Direccion']; ?>" required>
                </p>
                <p><strong>Usuario:</strong>
                    <input type="text" name="usuario" value="<?php echo $perfil_usuario['Usuario']; ?>" required>
                </p>
                <p><strong>Contraseña:</strong>
                    <input type="password" name="nueva_contraseña" placeholder="Ingrese nueva contraseña">
                </p>
                <p><strong>Estado:</strong> <?php echo $perfil_usuario['Estado']; ?></p>
                <br>
                <button type="submit" class="btn">Guardar Cambios</button>
            </form>
        <?php else: ?>
            <p>No se encontraron datos de perfil para el usuario.</p>
        <?php endif; ?>
    </div>
    <script>
        function getCiudades() {
            var departamentoId = document.getElementById("departamento").value;
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "/admin/include/obtener_ciudades.php?departamento=" + departamentoId, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var ciudades = JSON.parse(xhr.responseText);
                    var ciudadSelect = document.getElementById("ciudad");
                    ciudadSelect.innerHTML = '<option value="">Selecciona una ciudad</option>';
                    ciudades.forEach(function(ciudad) {
                        var option = document.createElement("option");
                        option.value = ciudad.id_ciudades;
                        option.textContent = ciudad.ciudades;
                        ciudadSelect.appendChild(option);
                    });
                }
            };
            xhr.send();
        }
    </script>
    <script>
        // Esperar 5 segundos y luego ocultar los mensajes
        setTimeout(function() {
            var successMensaje = document.getElementById('success-mensaje');
            var errorMessage = document.getElementById('error-message');

            if (successMensaje) {
                successMensaje.style.display = 'none';
            }

            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        }, 5000); // 5000 milisegundos = 5 segundos
    </script>

</body>

</html>