<?php
require_once "../includes/verificarSesion.php";
require_once "../includes/db.php";

// Solo permitir acceso a administradores
if ($_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}

$page_title = "Gestión de Reservas";
$specific_css = "dashboard.css";

include '../includes/headerAdmin.php';

// Cancelar (eliminar) reserva si se envía el ID por POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cancelar_id"])) {
    $cancelar_id = intval($_POST["cancelar_id"]);
    $stmt = $conn->prepare("DELETE FROM reservas WHERE id = ?");
    $stmt->bind_param("i", $cancelar_id);
    $stmt->execute();
    $stmt->close();
    // Redirigir para evitar reenvío del formulario
    header("Location: reservas.php?msg=cancelada");
    exit;
}

// Consulta de todas las reservas con JOIN a usuarios y habitaciones
$sql = "SELECT r.id, r.fecha_inicio, r.fecha_fin, r.creado_en, 
               u.nombre AS usuario, u.correo, 
               h.tipo AS habitacion_tipo, h.numero AS habitacion_numero
        FROM reservas r
        JOIN usuarios u ON r.usuario_id = u.id
        JOIN habitaciones h ON r.habitacion_id = h.id
        ORDER BY r.fecha_inicio DESC";
$result = $conn->query($sql);
?>

<div class="container">
    <h1>Gestión de Reservas</h1>
    <?php if (isset($_GET["msg"]) && $_GET["msg"] === "cancelada"): ?>
        <p class="message message-success">Reserva cancelada correctamente.</p>
    <?php endif; ?>
    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Habitación</th>
                <th>Tipo</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Reservado el</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo htmlspecialchars($row["usuario"]); ?></td>
                <td><?php echo htmlspecialchars($row["correo"]); ?></td>
                <td><?php echo htmlspecialchars($row["habitacion_numero"]); ?></td>
                <td><?php echo htmlspecialchars($row["habitacion_tipo"]); ?></td>
                <td><?php echo htmlspecialchars($row["fecha_inicio"]); ?></td>
                <td><?php echo htmlspecialchars($row["fecha_fin"]); ?></td>
                <td><?php echo htmlspecialchars($row["creado_en"]); ?></td>
                <td>
                    <form method="POST" action="reservas.php" style="display:inline;">
                        <input type="hidden" name="cancelar_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Cancelar esta reserva?');">Cancelar</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/footer.php'; ?>