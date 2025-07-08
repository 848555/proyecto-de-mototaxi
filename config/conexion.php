<?php 
$conexion = new mysqli("sql106.infinityfree.com", "if0_38237297", "YZqjc7ILfc58aM9", "if0_38237297_formulario");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>
