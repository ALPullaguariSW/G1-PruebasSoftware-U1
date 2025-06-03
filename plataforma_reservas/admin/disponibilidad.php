<?php
require_once "../includes/verificarSesion.php";
require_once "../includes/db.php";

// Solo permitir acceso a administradores
if ($_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}

$page_title = "Disponibilidad de Habitaciones";
$specific_css = "disponibilidad.css";

include '../includes/headerAdmin.php';

// Valores del formulario
$fecha_inicio = isset($_GET["fecha_inicio"]) ? $_GET["fecha_inicio"] : "";
$fecha_fin = isset($_GET["fecha_fin"]) ? $_GET["fecha_fin"] : "";
$habitaciones_disponibles = [];

if (!empty($fecha_inicio) && !empty($fecha_fin) && $fecha_fin > $fecha_inicio) {
    // Buscar habitaciones que NO estén reservadas en ese rango
    $sql = "SELECT h.id, h.numero, h.tipo, h.descripcion
            FROM habitaciones h
            WHERE h.id NOT IN (
                SELECT habitacion_id FROM reservas
                WHERE (fecha_inicio < ? AND fecha_fin > ?)
                   OR (fecha_inicio >= ? AND fecha_inicio < ?)
            )
            ORDER BY h.numero";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $fecha_fin, $fecha_inicio, $fecha_inicio, $fecha_fin);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $habitaciones_disponibles[] = $row;
    }
    $stmt->close();
}
?>

<div class="container">
    <h1>Disponibilidad de Habitaciones</h1>
    <form method="get" class="form-inline" style="margin-bottom:2em;">
        <label for="fecha_inicio">Desde:</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>" required>
        <label for="fecha_fin">Hasta:</label>
        <input type="date" name="fecha_fin" id="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>" required>
        <button type="submit" class="btn btn-primary">Consultar</button>
    </form>

    <?php if (!empty($fecha_inicio) && !empty($fecha_fin)): ?>
        <?php if ($fecha_fin <= $fecha_inicio): ?>
            <p class="message message-error">La fecha de salida debe ser posterior a la de entrada.</p>
        <?php elseif (count($habitaciones_disponibles) > 0): ?>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($habitaciones_disponibles as $hab): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($hab["numero"]); ?></td>
                        <td><?php echo htmlspecialchars($hab["tipo"]); ?></td>
                        <td><?php echo htmlspecialchars($hab["descripcion"]); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="message message-info">No hay habitaciones disponibles en ese rango.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>