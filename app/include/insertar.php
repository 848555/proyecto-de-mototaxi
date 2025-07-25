<?php
session_start();
include(__DIR__ . '../../../config/conexion.php');

// Verificar si el ID de usuario está presente en la sesión
if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['error_message'] = "No se ha iniciado sesión.";
    header("Location: ../../../../index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$origen = $_POST['origen'];
$destino = $_POST['destino'];
$cantidad_personas = $_POST['personas'];
$cantidad_motos = $_POST['cantidad'];
$metodo_pago = $_POST['pago'];

// Definir la tarifa y la retención
$tarifa = 4000; // Tarifa fija de 4000 pesos
$retencion = 1000; // Retención de 1000 pesos por cada 4000

// Calcular el costo total
$costo_total = $cantidad_motos * $tarifa;
$retencion_total = $cantidad_motos * $retencion;

// Verificar si se recibieron los datos esperados
if (empty($origen) || empty($destino) || empty($cantidad_personas) || empty($cantidad_motos) || empty($metodo_pago)) {
    $_SESSION['error_message'] = "No se recibieron todos los datos necesarios.";
    header("Location: ../../../../app/pages/solicitud.php");
    exit();
}

// Verificar si el usuario ya tiene una solicitud pendiente
$query = "SELECT * FROM solicitudes WHERE id_usuarios = ? AND estado = 'pendiente'";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['error_message'] = "Ya tienes una solicitud pendiente. Por favor espera.";
    $stmt->close();
    header("Location:../../../../app/pages/solicitud.php");
    exit();
}

// Insertar nueva solicitud
$insertQuery = "INSERT INTO solicitudes (origen, destino, cantidad_personas, cantidad_motos, metodo_pago, estado, id_usuarios, costo_total, retencion_total) VALUES (?, ?, ?, ?, ?, 'pendiente', ?, ?, ?)";
$stmt_insert = $conexion->prepare($insertQuery);
$stmt_insert->bind_param("sssisiid", $origen, $destino, $cantidad_personas, $cantidad_motos, $metodo_pago, $id_usuario, $costo_total, $retencion_total);

if ($stmt_insert->execute()) {
    $_SESSION['success_message'] = "Solicitud realizada con éxito. Costo total: $costo_total.";
} else {
    $_SESSION['error_message'] = "Hubo un error al realizar la solicitud. Por favor, inténtalo de nuevo.";
}

$stmt_insert->close();
$conexion->close();

header("Location:../../../../app/pages/solicitud.php");
exit();
?>
