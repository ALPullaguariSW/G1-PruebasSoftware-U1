<?php
require_once "includes/verificarSesion.php";
require_once "includes/db.php";

$mensaje = "";
$fecha_inicio = isset($_POST["fecha_inicio"]) ? $_POST["fecha_inicio"] : "";
$fecha_fin = isset($_POST["fecha_fin"]) ? $_POST["fecha_fin"] : "";
$habitacion_id = isset($_POST["habitacion_id"]) ? $_POST["habitacion_id"] : "";
$usuario_id = $_SESSION["usuario_id"];
$tipo_seleccionado = isset($_POST["tipo_habitacion"]) ? $_POST["tipo_habitacion"] : "";

// Obtener tipos de habitación para el filtro
$tipos_habitacion = [];
$sql_tipos = "SELECT DISTINCT tipo FROM habitaciones ORDER BY tipo";
$result_tipos = $conn->query($sql_tipos);
while ($row = $result_tipos->fetch_assoc()) {
    $tipos_habitacion[] = $row["tipo"];
}

// Reservar habitación
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["reservar"])) {
    // Validación básica
    if ($fecha_fin <= $fecha_inicio) {
        $mensaje = "La fecha de salida debe ser posterior a la fecha de entrada.";
    } elseif (empty($habitacion_id)) {
        $mensaje = "Debes seleccionar una habitación disponible.";
    } else {
        // Verificar disponibilidad de la habitación seleccionada
        $sql = "SELECT * FROM reservas WHERE habitacion = ? AND (
                    (fecha_inicio <= ? AND fecha_fin > ?) OR
                    (fecha_inicio < ? AND fecha_fin >= ?) OR
                    (? <= fecha_inicio AND ? >= fecha_fin)
                )";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $habitacion_id, $fecha_inicio, $fecha_inicio, $fecha_fin, $fecha_fin, $fecha_inicio, $fecha_fin);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $mensaje = "La habitación ya está reservada para esas fechas.";
        } else {
            $sql = "INSERT INTO reservas (usuario_id, habitacion, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $usuario_id, $habitacion_id, $fecha_inicio, $fecha_fin);
            if ($stmt->execute()) {
                $mensaje = "Reserva registrada con éxito.";
            } else {
                $mensaje = "Error al registrar la reserva.";
            }
        }
        $stmt->close();
    }
}

// Obtener habitaciones y disponibilidad
$habitaciones = [];
$parametros = [];
$tiposql = "";
if (!empty($fecha_inicio) && !empty($fecha_fin) && $fecha_fin > $fecha_inicio) {
    $sql = "SELECT h.id, h.tipo, h.numero, h.descripcion, h.servicios, h.imagen, h.precio,
            NOT EXISTS (
                SELECT 1 FROM reservas r
                WHERE r.habitacion = h.id
                AND (
                    (r.fecha_inicio <= ? AND r.fecha_fin > ?) OR
                    (r.fecha_inicio < ? AND r.fecha_fin >= ?) OR
                    (? <= r.fecha_inicio AND ? >= r.fecha_fin)
                )
            ) AS disponible
            FROM habitaciones h";
    $parametros = [$fecha_inicio, $fecha_inicio, $fecha_fin, $fecha_fin, $fecha_inicio, $fecha_fin];
    if (!empty($tipo_seleccionado)) {
        $sql .= " WHERE h.tipo = ?";
        $parametros[] = $tipo_seleccionado;
    }
    $sql .= " ORDER BY h.tipo, h.numero";
    $stmt = $conn->prepare($sql);
    if (!empty($tipo_seleccionado)) {
        $stmt->bind_param("sssssss", $fecha_inicio, $fecha_inicio, $fecha_fin, $fecha_fin, $fecha_inicio, $fecha_fin, $tipo_seleccionado);
    } else {
        $stmt->bind_param("ssssss", $fecha_inicio, $fecha_inicio, $fecha_fin, $fecha_fin, $fecha_inicio, $fecha_fin);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $habitaciones[] = $row;
    }
    $stmt->close();
} else {
    // Mostrar todas las habitaciones sin disponibilidad si no hay fechas
    $sql = "SELECT id, tipo, numero, descripcion, servicios, imagen, precio FROM habitaciones";
    if (!empty($tipo_seleccionado)) {
        $sql .= " WHERE tipo = '" . $conn->real_escape_string($tipo_seleccionado) . "'";
    }
    $sql .= " ORDER BY tipo, numero";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $row['disponible'] = null;
        $habitaciones[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservar habitación</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/reservar.css">
    <link rel="stylesheet" href="css/catalogo_habitaciones.css">
</head>
<body>
<div class="formulario-contenedor">
    <h2>Reservar Habitación</h2>
    <?php if ($mensaje): ?>
        <p class="mensaje"><?php echo $mensaje; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="fecha_inicio">Fecha de entrada:</label>
        <input type="date" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>" required min="<?php echo date('Y-m-d'); ?>">

        <label for="fecha_fin">Fecha de salida:</label>
        <input type="date" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>" required min="<?php echo date('Y-m-d'); ?>">

        <label for="tipo_habitacion">Tipo de habitación:</label>
        <select name="tipo_habitacion">
            <option value="">Todos los tipos</option>
            <?php foreach ($tipos_habitacion as $tipo): ?>
                <option value="<?php echo htmlspecialchars($tipo); ?>" <?php if ($tipo == $tipo_seleccionado) echo 'selected'; ?>><?php echo htmlspecialchars($tipo); ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" name="consultar">Consultar disponibilidad</button>
    </form>

    <h3>Catálogo de espacios y disponibilidad</h3>
    <form method="POST">
        <input type="hidden" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
        <input type="hidden" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>">
        <input type="hidden" name="tipo_habitacion" value="<?php echo htmlspecialchars($tipo_seleccionado); ?>">
        <table class="catalogo-habitaciones">
            <tr>
                <th>Tipo</th>
                <th>Número</th>
                <th>Precio</th>
                <th>Características</th>
                <th>Disponibilidad</th>
                <th>Seleccionar</th>
            </tr>
            <?php foreach ($habitaciones as $hab): ?>
                <tr>
                    <td><?php echo htmlspecialchars($hab['tipo']); ?></td>
                    <td><?php echo htmlspecialchars($hab['numero']); ?></td>
                    <td>$<?php echo number_format($hab['precio'], 2); ?></td>
                    <td>
                        <button type="button" class="info-btn" onclick='mostrarInfoHabitacion(<?php echo json_encode($hab, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>ℹ️</button>
                    </td>
                    <td>
                        <?php if ($hab['disponible'] === null): ?>
                            <span style="color:#b0b3b8">Seleccione fechas</span>
                        <?php elseif ($hab['disponible']): ?>
                            <span style="color:#00e696;font-weight:bold">Disponible</span>
                        <?php else: ?>
                            <span style="color:#ff0050;font-weight:bold">Ocupada</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($hab['disponible']): ?>
                            <input type="radio" name="habitacion_id" value="<?php echo $hab['id']; ?>" required>
                        <?php else: ?>
                            <input type="radio" disabled>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php if (!empty($fecha_inicio) && !empty($fecha_fin) && $fecha_fin > $fecha_inicio): ?>
            <button type="submit" name="reservar">Reservar</button>
        <?php endif; ?>
    </form>
    <p><a href="index.php">Volver al inicio</a></p>
</div>

<div id="modalHabitacion">
    <div class="modal-content" id="modalContenido">
        
        <button class="close-modal" id="cerrarModalHabitacion">&times;</button>
    </div>
</div>

<script>
function mostrarInfoHabitacion(habObj) {
    var hab = typeof habObj === 'string' ? JSON.parse(habObj) : habObj;
    const modal = document.getElementById('modalHabitacion');
    const contenido = document.getElementById('modalContenido');
    contenido.innerHTML = `
        <img src="${hab.imagen || ''}" alt="Habitación ${hab.numero}" style="width:100%;max-width:320px;border-radius:10px;margin-bottom:1rem;box-shadow:0 2px 12px #00e6ff33;">
        <h4>Habitación ${hab.numero} (${hab.tipo})</h4>
        <p><strong>Precio:</strong> $${parseFloat(hab.precio).toFixed(2)}</p>
        <p><strong>Descripción:</strong> ${hab.descripcion || 'Sin descripción.'}</p>
        <p><strong>Servicios:</strong> ${hab.servicios || 'Sin servicios.'}</p>
        <button class="close-modal" id="cerrarModalHabitacion">&times;</button>
    `;
    modal.classList.add('active');
    document.getElementById('cerrarModalHabitacion').onclick = cerrarModalHabitacion;
}
function cerrarModalHabitacion() {
    document.getElementById('modalHabitacion').classList.remove('active');
}
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('modalHabitacion').onclick = function(e) {
        if (e.target === this) cerrarModalHabitacion();
    };
});
</script>
</body>
</html>
