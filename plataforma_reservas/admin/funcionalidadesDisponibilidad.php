    <?php
class funcionalidadesDisponibilidad {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function buscarHabitacionesDisponibles($fecha_inicio, $fecha_fin) {
        if (empty($fecha_inicio) || empty($fecha_fin) || $fecha_fin <= $fecha_inicio) {
            return [];
        }

        $sql = "SELECT h.id, h.numero, h.tipo, h.descripcion
                FROM habitaciones h
                WHERE h.id NOT IN (
                    SELECT habitacion_id FROM reservas
                    WHERE (fecha_inicio < ? AND fecha_fin > ?)
                       OR (fecha_inicio >= ? AND fecha_inicio < ?)
                )
                ORDER BY h.numero";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("ssss", $fecha_fin, $fecha_inicio, $fecha_inicio, $fecha_fin);
        $stmt->execute();

        $result = $stmt->get_result();
        $habitaciones = [];

        while ($row = $result->fetch_assoc()) {
            $habitaciones[] = $row;
        }

        $stmt->close();
        return $habitaciones;
    }
}
