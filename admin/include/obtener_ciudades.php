<?php
session_start();
include(__DIR__ . '/permisos.php'); // ✅ Incluye la función tienePermiso()

// ✅ Validar si el usuario tiene permiso para editar (por ejemplo, módulo 1: Gestión de Usuarios)
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1 || !tienePermiso(1, 'editar')) {
    $_SESSION['error_message'] = '<div class="alert alert-danger" role="alert">No tienes permiso para realizar esta acción.</div>';
    header("Location: ../pages/principal.php");
    exit();
}
include(__DIR__ . '../../../config/conexion.php');

// Verificar si se recibió el parámetro departamento
if (isset($_GET['departamento'])) {
    $departamentoId = $_GET['departamento'];

    // Consulta para obtener ciudades del departamento seleccionado
    $sql_ciudades = "SELECT id_ciudades, ciudades FROM ciudades WHERE id_departamentos = $departamentoId";
    $resultado_ciudades = $conexion->query($sql_ciudades);

    // Preparar un array para almacenar las ciudades
    $ciudades = array();
    while ($ciudad = $resultado_ciudades->fetch_assoc()) {
        $ciudades[] = $ciudad;
    }

    // Devolver las ciudades en formato JSON
    echo json_encode($ciudades);
} else {
    // Si no se recibió el parámetro correcto, devolver un error o mensaje adecuado
    echo json_encode(array('error' => 'Parámetro departamento no recibido.'));
}
?>
