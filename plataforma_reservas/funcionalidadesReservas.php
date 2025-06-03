<?php
function cancelarReserva($conn, $reserva_id, $usuario_id) {
    $sql_check = "SELECT id FROM reservas WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("ii", $reserva_id, $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $stmt->close();
        $sql_delete = "DELETE FROM reservas WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $reserva_id);
        $ejecutado = $stmt_delete->execute();
        $stmt_delete->close();
        return $ejecutado;
    }
    $stmt->close();
    return false;
}

function obtenerReservasUsuario($conn, $usuario_id) {
    $sql = "SELECT r.id, r.fecha_inicio, r.fecha_fin, r.creado_en, 
                   h.tipo AS habitacion_tipo, h.numero AS habitacion_numero, h.imagen AS habitacion_imagen, h.precio AS habitacion_precio
            FROM reservas r
            JOIN habitaciones h ON r.habitacion_id = h.id
            WHERE r.usuario_id = ? 
            ORDER BY r.fecha_inicio DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $reservas = [];
    while ($row = $result->fetch_assoc()) {
        $reservas[] = $row;
    }

    $stmt->close();
    return $reservas;
}
