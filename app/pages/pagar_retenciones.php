<?php
session_start();
include(__DIR__ . '../../../config/conexion.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    $_SESSION['error_message'] = "No se ha iniciado sesión.";
    header("Location: ../../../../index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Inicializar $total_retencion
$total_retencion = 0;
$retenciones = [];

// Obtener las retenciones pendientes
$sql = "SELECT * FROM retenciones WHERE id_usuarios = ? AND pagado = 0";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $total_retencion += $row['retencion'];
    $retenciones[] = $row; // Guardar detalles de cada retención
}
$stmt->close();

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagar Retenciones</title>
    <link rel="stylesheet" href="/app/assets/css/retenciones.css">
</head>
<body>
   <div class="contenedor">
        <h1>Pagar Retenciones</h1>
        <?php if (isset($_SESSION['success_message'])): ?>
            <p class="mensaje"><?php echo $_SESSION['success_message']; ?></p>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <p class="mensaje"><?php echo $_SESSION['error_message']; ?></p>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php if (count($retenciones) > 0): ?>
            <h2>Detalles de Retenciones Pendientes</h2>
            <ul>
                <?php foreach ($retenciones as $retencion): ?>
                    <li>
                        Retención: <strong><?php echo number_format($retencion['retencion'], 0, ',', '.'); ?> COP</strong>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p>Total de retenciones pendientes: <strong><?php echo number_format($total_retencion, 0, ',', '.'); ?> COP</strong></p>
            <form id="pagoNequiForm">
                <label for="numeroNequi">Número de Cuenta Nequi:</label>
                <input type="text" id="numeroNequi" name="numeroNequi" required>
                <button id="btnPagarNequi" type="submit">Pagar Retenciones</button>
                <a class="regresar" href="/app/pages/inicio.php">Regresar</a>
            </form>
        <?php else: ?>
            <p>No tienes retenciones pendientes.</p>
            <a class="regresar" href="/app/pages/inicio.php">Regresar</a>
        <?php endif; ?>
    </div>
    <script src="/app/assets/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('pagoNequiForm').addEventListener('submit', function(e) {
                e.preventDefault(); // Prevenir el envío del formulario

                var numeroNequi = document.getElementById('numeroNequi').value.trim();

                // Validar el número de cuenta Nequi
                if (numeroNequi.length < 10) {
                    alert('Por favor ingresa un número de cuenta Nequi válido.');
                    return;
                }

                // Redirigir a Nequi con el número de cuenta
                var urlNequi = 'https://www.nequi.com.co/?numero=' + encodeURIComponent(numeroNequi);
                window.location.href = urlNequi;

                // Aquí puedes agregar lógica adicional para manejar la respuesta de Nequi en el futuro.
            });
        });
    </script>
</body>
</html>
