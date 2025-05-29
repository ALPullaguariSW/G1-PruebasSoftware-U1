<?php
require_once "includes/verificarSesion.php";
require_once "includes/db.php";

$page_title = "Reservar Habitación";
$specific_css = "reservar.css";
$specific_js = "booking.js"; // Para el modal y validaciones de fecha
$usuario_id = $_SESSION["usuario_id"];

$mensaje = "";
$claseMensaje = "";

// Valores del formulario (sticky)
$fecha_inicio_str = isset($_REQUEST["fecha_inicio"]) ? $_REQUEST["fecha_inicio"] : "";
$fecha_fin_str = isset($_REQUEST["fecha_fin"]) ? $_REQUEST["fecha_fin"] : "";
$tipo_seleccionado = isset($_REQUEST["tipo_habitacion"]) ? $_REQUEST["tipo_habitacion"] : "";
$habitacion_id_seleccionada = isset($_POST["habitacion_id"]) ? (int)$_POST["habitacion_id"] : null;


// Obtener tipos de habitación para el filtro
$tipos_habitacion = [];
$sql_tipos = "SELECT DISTINCT tipo FROM habitaciones ORDER BY tipo";
$result_tipos = $conn->query($sql_tipos);
if ($result_tipos) {
    while ($row = $result_tipos->fetch_assoc()) {
        $tipos_habitacion[] = $row["tipo"];
    }
}

$habitaciones_disponibles = [];
$mostrar_catalogo = false;

// Acción: Consultar disponibilidad o Reservar
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["consultar_disponibilidad"])) {
        $mostrar_catalogo = true;
        if (empty($fecha_inicio_str) || empty($fecha_fin_str)) {
            $mensaje = "Por favor, seleccione las fechas de entrada y salida.";
            $claseMensaje = "error";
            $mostrar_catalogo = false;
        } elseif ($fecha_fin_str <= $fecha_inicio_str) {
            $mensaje = "La fecha de salida debe ser posterior a la fecha de entrada.";
            $claseMensaje = "error";
            $mostrar_catalogo = false;
        }
        // La lógica para buscar habitaciones se ejecuta más abajo si $mostrar_catalogo es true
    } elseif (isset($_POST["realizar_reserva"])) {
        $mostrar_catalogo = true; // Mantener el catálogo visible
        if (empty($fecha_inicio_str) || empty($fecha_fin_str)) {
            $mensaje = "Las fechas de entrada y salida son requeridas.";
            $claseMensaje = "error";
        } elseif ($fecha_fin_str <= $fecha_inicio_str) {
            $mensaje = "La fecha de salida debe ser posterior a la fecha de entrada.";
            $claseMensaje = "error";
        } elseif (empty($habitacion_id_seleccionada)) {
            $mensaje = "Debes seleccionar una habitación disponible para reservar.";
            $claseMensaje = "error";
        } else {
            // Verificar nuevamente la disponibilidad de la habitación seleccionada (doble check)
            $sql_check_disp = "SELECT id FROM reservas 
                               WHERE habitacion_id = ? AND NOT (fecha_fin <= ? OR fecha_inicio >= ?)";
            $stmt_check_disp = $conn->prepare($sql_check_disp);
            $stmt_check_disp->bind_param("iss", $habitacion_id_seleccionada, $fecha_inicio_str, $fecha_fin_str);
            $stmt_check_disp->execute();
            $result_check_disp = $stmt_check_disp->get_result();

            if ($result_check_disp->num_rows > 0) {
                $mensaje = "Lo sentimos, la habitación seleccionada acaba de ser reservada por otra persona para esas fechas. Por favor, intente con otra.";
                $claseMensaje = "error";
            } else {
                // Proceder con la reserva
                // **IMPORTANTE: La tabla `reservas` debe tener `habitacion_id` (INT)**
                $sql_insert_reserva = "INSERT INTO reservas (usuario_id, habitacion_id, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)";
                $stmt_insert_reserva = $conn->prepare($sql_insert_reserva);
                $stmt_insert_reserva->bind_param("iiss", $usuario_id, $habitacion_id_seleccionada, $fecha_inicio_str, $fecha_fin_str);
                
                if ($stmt_insert_reserva->execute()) {
                    $mensaje = "¡Reserva registrada con éxito! Puedes ver los detalles en <a href='mis_reservas.php'>Mis Reservas</a>.";
                    $claseMensaje = "success";
                    // Limpiar selección para evitar re-reserva accidental al recargar
                    $habitacion_id_seleccionada = null; 
                    // $mostrar_catalogo = false; // Opcional: ocultar catálogo después de reservar
                } else {
                    // error_log("Error al registrar la reserva: " . $stmt_insert_reserva->error);
                    $mensaje = "Error al registrar la reserva. Por favor, inténtelo de nuevo.";
                    $claseMensaje = "error";
                }
                $stmt_insert_reserva->close();
            }
            $stmt_check_disp->close();
        }
    }
}

// Si se deben mostrar habitaciones (después de consultar o si hay un error al reservar)
if ($mostrar_catalogo && !empty($fecha_inicio_str) && !empty($fecha_fin_str) && $fecha_fin_str > $fecha_inicio_str) {
    $sql_habitaciones = "SELECT h.id, h.tipo, h.numero, h.descripcion, h.servicios, h.imagen, h.precio,
                        NOT EXISTS (
                            SELECT 1 FROM reservas r
                            WHERE r.habitacion_id = h.id
                            AND NOT (r.fecha_fin <= ? OR r.fecha_inicio >= ?) 
                        ) AS disponible
                        FROM habitaciones h";
    
    $params_sql = [$fecha_inicio_str, $fecha_fin_str];
    $types_sql = "ss";

    if (!empty($tipo_seleccionado)) {
        $sql_habitaciones .= " WHERE h.tipo = ?";
        $params_sql[] = $tipo_seleccionado;
        $types_sql .= "s";
    }
    $sql_habitaciones .= " ORDER BY h.tipo, h.precio, h.numero";
    
    $stmt_habitaciones = $conn->prepare($sql_habitaciones);
    if ($stmt_habitaciones) {
        $stmt_habitaciones->bind_param($types_sql, ...$params_sql);
        $stmt_habitaciones->execute();
        $result_habitaciones = $stmt_habitaciones->get_result();
        while ($row = $result_habitaciones->fetch_assoc()) {
            $habitaciones_disponibles[] = $row;
        }
        $stmt_habitaciones->close();
    } else {
        // error_log("Error al preparar consulta de habitaciones: " . $conn->error);
        $mensaje = "Error al buscar habitaciones. Intente más tarde.";
        $claseMensaje = "error";
    }
}


include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Encuentra tu Estancia Perfecta</h1>
    </div>

    <?php if (!empty($mensaje)): ?>
        <p class="message message-<?php echo $claseMensaje; ?>"><?php echo $mensaje; /* Permite HTML para enlaces */ ?></p>
    <?php endif; ?>

    <div class="form-container reserva-form">
        <h2>Busca Disponibilidad</h2>
        <form method="POST" action="reservar.php" id="formConsultaDisponibilidad">
            <div class="filters-grid">
                <div class="form-group">
                    <label for="fecha_inicio">Fecha de Entrada:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?php echo htmlspecialchars($fecha_inicio_str); ?>" required>
                </div>
                <div class="form-group">
                    <label for="fecha_fin">Fecha de Salida:</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" value="<?php echo htmlspecialchars($fecha_fin_str); ?>" required>
                </div>
                <div class="form-group">
                    <label for="tipo_habitacion">Tipo de Habitación:</label>
                    <select id="tipo_habitacion" name="tipo_habitacion" class="form-control">
                        <option value="">Todos los tipos</option>
                        <?php foreach ($tipos_habitacion as $tipo): ?>
                            <option value="<?php echo htmlspecialchars($tipo); ?>" <?php if ($tipo == $tipo_seleccionado) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($tipo); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                     <label> </label> <!-- Para alinear el botón -->
                    <button type="submit" name="consultar_disponibilidad" class="btn btn-primary btn-block">Consultar</button>
                </div>
            </div>
        </form>
    </div>

    <?php if ($mostrar_catalogo && ($claseMensaje !== "error" || !empty($habitaciones_disponibles))): ?>
    <section class="catalogo-section">
        <h3>Habitaciones Disponibles</h3>
        <?php if (count($habitaciones_disponibles) > 0): ?>
            <form method="POST" action="reservar.php" id="formSeleccionHabitacion">
                <!-- Campos ocultos para reenviar los filtros al reservar -->
                <input type="hidden" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio_str); ?>">
                <input type="hidden" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin_str); ?>">
                <input type="hidden" name="tipo_habitacion" value="<?php echo htmlspecialchars($tipo_seleccionado); ?>">
                
                <div class="habitaciones-grid">
                    <?php foreach ($habitaciones_disponibles as $hab): ?>
                        <div class="habitacion-card">
                            <div class="habitacion-card-imagen">
                                <img src="<?php echo !empty($hab['imagen']) ? htmlspecialchars($hab['imagen']) : $base_url . 'images/default_room.jpg'; ?>" alt="Habitación <?php echo htmlspecialchars($hab['numero']); ?>">
                            </div>
                            <div class="habitacion-card-contenido">
                                <h4><?php echo htmlspecialchars($hab['tipo']); ?> - N° <?php echo htmlspecialchars($hab['numero']); ?></h4>
                                <p class="precio">$<?php echo number_format($hab['precio'], 2); ?> <small>/ noche</small></p>
                                
                                <p class="habitacion-card-estado">
                                    <?php if ($hab['disponible']): ?>
                                        <span class="estado-disponible">Disponible</span>
                                    <?php else: ?>
                                        <span class="estado-ocupada">Ocupada en estas fechas</span>
                                    <?php endif; ?>
                                </p>
                                <div class="habitacion-card-acciones">
                                    <?php if ($hab['disponible']): ?>
                                        <input type="radio" name="habitacion_id" value="<?php echo $hab['id']; ?>" id="hab_<?php echo $hab['id']; ?>" 
                                               <?php if ($habitacion_id_seleccionada == $hab['id']) echo 'checked'; ?> required>
                                        <label for="hab_<?php echo $hab['id']; ?>" class="sr-only">Seleccionar habitación <?php echo htmlspecialchars($hab['numero']); ?></label>
                                    <?php else: ?>
                                        <input type="radio" disabled>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-secondary btn-sm info-btn-modal" 
                                            data-habitacion='<?php echo htmlspecialchars(json_encode($hab), ENT_QUOTES, 'UTF-8'); ?>'>
                                        Más Info
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" name="realizar_reserva" class="btn btn-primary btn-lg boton-reservar-seleccion">Reservar Selección</button>
            </form>
        <?php elseif (isset($_POST["consultar_disponibilidad"])): // Se consultó pero no hay resultados ?>
             <p class="message message-info">No hay habitaciones disponibles que coincidan con tus criterios de búsqueda para las fechas seleccionadas. Prueba con otras fechas o tipos de habitación.</p>
        <?php endif; ?>
    </section>
    <?php elseif ($mostrar_catalogo && $claseMensaje === "error" && empty($habitaciones_disponibles)): ?>
        <!-- No se muestra nada extra si hubo error en fechas y no hay catalogo que mostrar -->
    <?php elseif (!isset($_POST["consultar_disponibilidad"]) && empty($mensaje)): ?>
        <p class="message message-info text-center">Por favor, selecciona las fechas y el tipo de habitación para ver la disponibilidad.</p>
    <?php endif; ?>

    <p class="text-center mt-3"><a href="index.php" class="btn btn-secondary">Volver al Dashboard</a></p>
</div>


<!-- Modal para Información de Habitación -->
<div id="modalHabitacionInfo" class="modal">
    <div class="modal-content" id="modalContenidoInfo">
        <!-- Contenido se carga por JS -->
    </div>
</div>

<?php
include 'includes/footer.php';
?>