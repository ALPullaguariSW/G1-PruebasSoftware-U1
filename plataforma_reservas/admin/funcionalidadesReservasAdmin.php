<?php
class funcionalidadesReservasAdmin {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function cancelarReserva(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM reservas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function obtenerTodasLasReservas(): array {
        $sql = "SELECT r.id, r.fecha_inicio, r.fecha_fin, r.creado_en, 
                       u.nombre AS usuario, u.correo, 
                       h.tipo AS habitacion_tipo, h.numero AS habitacion_numero
                FROM reservas r
                JOIN usuarios u ON r.usuario_id = u.id
                JOIN habitaciones h ON r.habitacion_id = h.id
                ORDER BY r.fecha_inicio DESC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
