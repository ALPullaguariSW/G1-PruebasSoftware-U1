<?php

require_once "../includes/verificarSesion.php";
require_once "../includes/db.php";

// Solo permitir acceso a administradores
if ($_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}

$page_title = "Panel de Administración";
$specific_css = "dashboard.css";

include '../includes/headerAdmin.php';

// Estadísticas rápidas
$total_usuarios = $conn->query("SELECT COUNT(*) FROM usuarios")->fetch_row()[0];
$total_reservas = $conn->query("SELECT COUNT(*) FROM reservas")->fetch_row()[0];
$total_habitaciones = $conn->query("SELECT COUNT(*) FROM habitaciones")->fetch_row()[0];

// Próximas reservas (solo muestra las 5 más próximas)
$sql_proximas = "SELECT r.id, r.fecha_inicio, r.fecha_fin, u.nombre AS usuario, h.numero AS habitacion
                 FROM reservas r
                 JOIN usuarios u ON r.usuario_id = u.id
                 JOIN habitaciones h ON r.habitacion_id = h.id
                 WHERE r.fecha_inicio >= CURDATE()
                 ORDER BY r.fecha_inicio ASC
                 LIMIT 5";
$proximas = $conn->query($sql_proximas);
?>

<div class="container">
    <h1>Panel de Administración</h1>
    <div class="dashboard-cards">
        <div class="dashboard-card">
            <h2><?php echo $total_usuarios; ?></h2>
            <p>Usuarios registrados</p>
        </div>
        <div class="dashboard-card">
            <h2><?php echo $total_reservas; ?></h2>
            <p>Reservas totales</p>
        </div>
        <div class="dashboard-card">
            <h2><?php echo $total_habitaciones; ?></h2>
            <p>Habitaciones</p>
        </div>
    </div>

    <h3 style="margin-top:2em;">Próximas reservas</h3>
    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Habitación</th>
                <th>Entrada</th>
                <th>Salida</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $proximas->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo htmlspecialchars($row["usuario"]); ?></td>
                <td><?php echo htmlspecialchars($row["habitacion"]); ?></td>
                <td><?php echo htmlspecialchars($row["fecha_inicio"]); ?></td>
                <td><?php echo htmlspecialchars($row["fecha_fin"]); ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="admin-links" style="margin-top:2em;">
        <a href="reservas.php" class="btn btn-primary">Gestionar Reservas</a>
        <a href="gestionar_espacios.php" class="btn btn-secondary">Gestionar Habitaciones</a>
        <a href="disponibilidad.php" class="btn btn-info">Ver Disponibilidad</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>