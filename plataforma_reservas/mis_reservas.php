<?php
require_once "includes/verificarSesion.php";
require_once "includes/db.php";

$usuario_id = $_SESSION["usuario_id"];
$mensaje = "";

// Cancelar reserva si se envía el ID por POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["reserva_id"])) {
    $reserva_id = $_POST["reserva_id"];

    $sql = "DELETE FROM reservas WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $reserva_id, $usuario_id);

    if ($stmt->execute()) {
        $mensaje = "Reserva cancelada correctamente.";
    } else {
        $mensaje = "Error al cancelar la reserva.";
    }
    $stmt->close();
}

// Obtener las reservas del usuario
$sql = "SELECT id, habitacion, fecha_inicio, fecha_fin, creado_en 
        FROM reservas 
        WHERE usuario_id = ? 
        ORDER BY fecha_inicio";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Reservas</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<div class="formulario-contenedor">
    <h2>Mis Reservas</h2>
    <?php if ($mensaje): ?>
        <p class="mensaje"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <?php if ($resultado->num_rows > 0): ?>
        <table>
            <tr>
                <th>Habitación</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Fecha de Reserva</th>
                <th>Acción</th>
            </tr>
            <?php while ($reserva = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reserva["habitacion"]); ?></td>
                    <td><?php echo $reserva["fecha_inicio"]; ?></td>
                    <td><?php echo $reserva["fecha_fin"]; ?></td>
                    <td><?php echo $reserva["creado_en"]; ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('¿Cancelar esta reserva?');">
                            <input type="hidden" name="reserva_id" value="<?php echo $reserva['id']; ?>">
                            <button type="submit">Cancelar</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No tienes reservas realizadas.</p>
    <?php endif; ?>

    <p><a href="index.php">Volver al inicio</a></p>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
