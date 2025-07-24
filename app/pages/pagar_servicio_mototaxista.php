<?php
include(__DIR__ . '../../../config/conexion.php');
session_start();
$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT * FROM solicitudes WHERE id_usuarios = $id_usuario AND pago_completo = 0 AND estado = 'finalizado'";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagar al mototaxista</title>
    <link rel="stylesheet" href="/app/assets/css/retenciones.css">
    
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4 text-center">Pagar al mototaxista</h2>

    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <form action="/app/procesar_pago_mototaxista.php" method="POST">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Solicitud</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Costo Total</th>
                            <th>Fecha</th>
                            <th>Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $row['id_solicitud'] ?></td>
                                <td><?= $row['origen'] ?></td>
                                <td><?= $row['destino'] ?></td>
                                <td>$<?= number_format($row['costo_total'], 0, ',', '.') ?></td>
                                <td><?= $row['fecha'] ?? 'N/A' ?></td>
                                <td>
                                    <input type="radio" name="id_solicitud" value="<?= $row['id_solicitud'] ?>" required>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="botones-container mt-4">
                <button type="submit" class="btn btn-success">Simular pago con Nequi</button>
                <a href="/app/pages/inicio.php" class="btn btn-success">Regresar</a>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-info text-center">
            No tienes pagos pendientes al mototaxista.
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-warning mt-4 text-center">
            <?= $_SESSION['mensaje']; ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>
</div>

<!-- Ícono de accesibilidad -->
<div id="accessibility-icon">
    <ion-icon name="accessibility-outline"></ion-icon>
</div>

<!-- Controles de accesibilidad -->
<div id="accessibility-panel" class="accessibility-controls" style="display: none;">
    <button id="increaseText">Aumentar letra</button>
    <button id="decreaseText">Disminuir letra</button>
</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="/app/assets/js/funcionalidad.js"></script>
<script src="/app/assets/js/script.js"></script>

</body>
</html>
