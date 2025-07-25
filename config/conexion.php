<?php 
$conexion = new mysqli("localhost", "root", "", "formulario");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>
