<?php
session_start();
include(__DIR__ . '../../../config/conexion.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    header("Location: ../../../../index.php");
    exit();
}

$validar = $_SESSION['usuario'];

// Verificar si se ha proporcionado el ID de usuario y de solicitud
if (!isset($_GET['id_usuario']) || !isset($_GET['id_solicitud'])) {
    $_SESSION['error_message'] = "No se ha proporcionado el ID de usuario o de la solicitud.";
    header("Location: ../../../pages/sermototaxista.php");
    exit();
}

$id_usuario = intval($_GET['id_usuario']); // Convertir a entero para seguridad
$id_solicitud = intval($_GET['id_solicitud']); // Convertir a entero para seguridad

if ($id_usuario <= 0 || $id_solicitud <= 0) {
    $_SESSION['error_message'] = "ID de usuario o solicitud no válido.";
    header("Location: ../../../pages/sermototaxista.php");
    exit();
}

// Obtener la solicitud
$query = "SELECT * FROM solicitudes WHERE id_solicitud = ? AND estado = 'pendiente'";
$stmt = $conexion->prepare($query);

if ($stmt) {
    $stmt->bind_param("i", $id_solicitud);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Actualizar el estado de la solicitud a 'aceptada' y asignar el mototaxista
        $sql_update = "UPDATE solicitudes SET estado='aceptada', id_usuarios=? WHERE id_solicitud=?";
        $stmt_update = $conexion->prepare($sql_update);

        if ($stmt_update) {
            $stmt_update->bind_param("ii", $id_usuario, $id_solicitud);

            if ($stmt_update->execute()) {
                // Insertar mensaje en la tabla de mensajes
                $mensaje = "Tu solicitud ha sido aceptada. Enseguida van por ti, espera en el lugar acordado.";
                $sql_insert_mensaje = "INSERT INTO mensajes_temporales (id_solicitud, id_usuario, mensaje, fecha) VALUES (?, ?, ?, NOW())";
                $stmt_insert = $conexion->prepare($sql_insert_mensaje);

                if ($stmt_insert) {
                    $stmt_insert->bind_param("iis", $id_solicitud, $id_usuario, $mensaje);
                    $stmt_insert->execute();
                    $stmt_insert->close();

                    $_SESSION['success_message'] = "Solicitud aceptada correctamente y notificación enviada al solicitante.";
                } else {
                    $_SESSION['error_message'] = "Error al enviar notificación: " . $conexion->error;
                }
            } else {
                $_SESSION['error_message'] = "Error al actualizar la solicitud: " . $conexion->error;
            }

            $stmt_update->close();
        } else {
            $_SESSION['error_message'] = "Error al preparar la consulta de actualización: " . $conexion->error;
        }
    } else {
        $_SESSION['error_message'] = "Solicitud no encontrada o ya aceptada.";
    }

    $stmt->close();
} else {
    $_SESSION['error_message'] = "Error al preparar la consulta de selección: " . $conexion->error;
}

if ($conexion) {
    $conexion->close();
}

// Redirigir a la página de solicitudes
header("Location: ../pages/sermototaxista.php");
exit();
?>
