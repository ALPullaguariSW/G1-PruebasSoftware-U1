<?php
require_once "includes/verificarSesion.php";
require_once "includes/db.php";

$page_title = "Mis Reservas";
$specific_css = "mis_reservas.css";
$usuario_id = $_SESSION["usuario_id"];
$mensaje = "";
$claseMensaje = "";

// Cancelar reserva si se envía el ID por POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cancelar_reserva_id"])) {
    $reserva_id_cancelar = $_POST["cancelar_reserva_id"];

    // Verificación adicional: la reserva pertenece al usuario?
    $sql_check = "SELECT id FROM reservas WHERE id = ? AND usuario_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $reserva_id_cancelar, $usuario_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 1) {
        $sql_delete = "DELETE FROM reservas WHERE id = ?"; // No es necesario usuario_id aquí por el check anterior
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $reserva_id_cancelar);

        if ($stmt_delete->execute()) {
            $mensaje = "Reserva cancelada correctamente.";
            $claseMensaje = "success";
        } else {
            // error_log("Error al cancelar reserva: " . $stmt_delete->error);
            $mensaje = "Error al cancelar la reserva. Inténtelo más tarde.";
            $claseMensaje = "error";
        }
        $stmt_delete->close();
    } else {
        $mensaje = "No se pudo cancelar la reserva (posiblemente no le pertenece o no existe).";
        $claseMensaje = "error";
    }
    $stmt_check->close();
}

// Obtener las reservas del usuario con detalles de la habitación
// **IMPORTANTE: Esto asume que has corregido la tabla `reservas` para tener `habitacion_id` (INT) en lugar de `habitacion` (VARCHAR)**
$sql_reservas = "SELECT r.id, r.fecha_inicio, r.fecha_fin, r.creado_en, 
                        h.tipo AS habitacion_tipo, h.numero AS habitacion_numero, h.imagen AS habitacion_imagen, h.precio AS habitacion_precio
                 FROM reservas r
                 JOIN habitaciones h ON r.habitacion_id = h.id
                 WHERE r.usuario_id = ? 
                 ORDER BY r.fecha_inicio DESC"; // Más recientes primero o como prefieras
$stmt_reservas = $conn->prepare($sql_reservas);
$stmt_reservas->bind_param("i", $usuario_id);
$stmt_reservas->execute();
$resultado_reservas = $stmt_reservas->get_result();
$reservas = [];
while ($fila = $resultado_reservas->fetch_assoc()) {
    $reservas[] = $fila;
}
$stmt_reservas->close();

include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Mis Reservas</h1>
    </div>

    <?php if (!empty($mensaje)): ?>
        <p class="message message-<?php echo $claseMensaje; ?>"><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>

    <div class="reservas-list">
        <?php if (count($reservas) > 0): ?>
            <?php foreach ($reservas as $reserva): ?>
                <div class="reserva-card">
                    <div class="reserva-image">
                        <img src="<?php echo !empty($reserva["habitacion_imagen"]) ? htmlspecialchars($reserva["habitacion_imagen"]) : $base_url . 'images/default_room.jpg'; ?>" alt="Habitación <?php echo htmlspecialchars($reserva["habitacion_numero"]); ?>">
                    </div>
                    <div class="reserva-details">
                        <h3>Habitación <?php echo htmlspecialchars($reserva["habitacion_numero"]); ?> (<?php echo htmlspecialchars($reserva["habitacion_tipo"]); ?>)</h3>
                        <p><strong>Entrada:</strong> <?php echo date("d/m/Y", strtotime($reserva["fecha_inicio"])); ?></p>
                        <p><strong>Salida:</strong> <?php echo date("d/m/Y", strtotime($reserva["fecha_fin"])); ?></p>
                        <p><strong>Precio por noche:</strong> $<?php echo number_format($reserva["habitacion_precio"], 2); ?></p>
                        <p><strong>Reservado el:</strong> <?php echo date("d/m/Y H:i", strtotime($reserva["creado_en"])); ?></p>
                    </div>
                    <div class="reserva-actions">
                        <?php if (strtotime($reserva["fecha_inicio"]) > time()): // Solo permitir cancelar si la fecha de inicio es futura ?>
                            <form method="POST" action="mis_reservas.php" onsubmit="return confirm('¿Estás seguro de que quieres cancelar esta reserva? Esta acción no se puede deshacer.');">
                                <input type="hidden" name="cancelar_reserva_id" value="<?php echo $reserva['id']; ?>">
                                <button type="submit" class="btn btn-danger">Cancelar Reserva</button>
                            </form>
                        <?php else: ?>
                            <p><em>Reserva pasada o en curso.</em></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="message message-info">Aún no tienes reservas realizadas. <a href="reservar.php">¡Haz tu primera reserva!</a></p>
        <?php endif; ?>
    </div>
     <p class="text-center mt-3"><a href="index.php" class="btn btn-secondary">Volver al Dashboard</a></p>
</div>

<?php
include 'includes/footer.php';
?>