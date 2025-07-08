<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include(__DIR__ . '../../../config/conexion.php');

// Verificar si el usuario está autenticado
$validar = $_SESSION['usuario'];

if ($validar == null || $validar == '') {
    header("Location: ../../../../index.php");
    die();
}

$id_usuario = $_SESSION['id_usuario'];

// Consultar mensajes no leídos
$sql_select_mensajes = "SELECT id, id_solicitud, mensaje, fecha FROM mensajes_temporales WHERE id_usuario = ? AND leido = FALSE";
$stmt_select = $conexion->prepare($sql_select_mensajes);
$stmt_select->bind_param("i", $id_usuario);
$stmt_select->execute();
$result = $stmt_select->get_result();

$mensajes = [];
while ($row = $result->fetch_assoc()) {
    $mensajes[] = $row;
}

$stmt_select->close();

// Marcar los mensajes como vistos
foreach ($mensajes as $mensaje) {
    $id_mensaje = $mensaje['id'];
    // Consulta para actualizar el estado a "visto"
    $sql_update_leido = "UPDATE mensajes_temporales SET leido = TRUE WHERE id = ?";
    $stmt_update = $conexion->prepare($sql_update_leido);
    $stmt_update->bind_param("i", $id_mensaje);
    $stmt_update->execute();
    $stmt_update->close();
}

$conexion->close();

// Mostrar mensajes en formato HTML
if (!empty($mensajes)) {
    echo '<ul>';
    foreach ($mensajes as $mensaje) {
        echo '<li>' . htmlspecialchars($mensaje['mensaje'], ENT_QUOTES, 'UTF-8') . ' - <em>' . htmlspecialchars($mensaje['fecha'], ENT_QUOTES, 'UTF-8') . '</em></li>';
    }
    echo '</ul>';
} else {
    echo '<p>No tienes mensajes nuevos.</p>';
}
?>
