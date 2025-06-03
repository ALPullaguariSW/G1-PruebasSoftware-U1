<?php
class funcionalidadesReservar {
    private $conn;

    public function __construct($conexion_db) {
        $this->conn = $conexion_db;
    }

    public function consultarDisponibilidad($fecha_inicio, $fecha_fin, $tipo = "") {
        if (empty($fecha_inicio) || empty($fecha_fin)) {
            throw new InvalidArgumentException("Fechas no pueden estar vacías");
        }
        if ($fecha_fin <= $fecha_inicio) {
            throw new InvalidArgumentException("Fecha fin debe ser posterior a inicio");
        }

        $sql = "SELECT h.id, h.tipo, h.numero, h.descripcion, h.servicios, h.imagen, h.precio,
            NOT EXISTS (
                SELECT 1 FROM reservas r
                WHERE r.habitacion_id = h.id
                AND NOT (r.fecha_fin <= ? OR r.fecha_inicio >= ?) 
            ) AS disponible
            FROM habitaciones h";

        $params = [$fecha_inicio, $fecha_fin];
        $types = "ss";

        if (!empty($tipo)) {
            $sql .= " WHERE h.tipo = ?";
            $params[] = $tipo;
            $types .= "s";
        }

        $sql .= " ORDER BY h.tipo, h.precio, h.numero";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) throw new RuntimeException("Error preparando consulta: " . $this->conn->error);
        
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $habitaciones = [];
        while ($row = $result->fetch_assoc()) {
            $habitaciones[] = $row;
        }

        $stmt->close();
        return $habitaciones;
    }

    public function realizarReserva($usuario_id, $habitacion_id, $fecha_inicio, $fecha_fin) {
        if (empty($usuario_id) || empty($habitacion_id) || empty($fecha_inicio) || empty($fecha_fin)) {
            throw new InvalidArgumentException("Parámetros insuficientes");
        }

        // Verificar disponibilidad
        $sql_check = "SELECT id FROM reservas WHERE habitacion_id = ? AND NOT (fecha_fin <= ? OR fecha_inicio >= ?)";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->bind_param("iss", $habitacion_id, $fecha_inicio, $fecha_fin);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $stmt_check->close();
            return false; // No disponible
        }
        $stmt_check->close();

        // Insertar reserva
        $sql_insert = "INSERT INTO reservas (usuario_id, habitacion_id, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)";
        $stmt_insert = $this->conn->prepare($sql_insert);
        $stmt_insert->bind_param("iiss", $usuario_id, $habitacion_id, $fecha_inicio, $fecha_fin);

        $resultado = $stmt_insert->execute();
        $stmt_insert->close();

        return $resultado;
    }
}
?>
