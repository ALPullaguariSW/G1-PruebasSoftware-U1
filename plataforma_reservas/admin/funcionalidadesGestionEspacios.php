
<?php
class funcionalidadesGestionEspacios {
    private $conn;

    public function __construct($conexion) {
        $this->conn = $conexion;
    }

    public function crearHabitacion($datos) {
        if (empty($datos['numero']) || empty($datos['tipo']) || empty($datos['precio'])) {
            return ["mensaje" => "El número, tipo y precio son obligatorios.", "clase" => "message-error"];
        }

        $sql = "INSERT INTO habitaciones (numero, tipo, descripcion, servicios, imagen, precio) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssssss",
            $datos['numero'],
            $datos['tipo'],
            $datos['descripcion'],
            $datos['servicios'],
            $datos['imagen'],
            $datos['precio']
        );
        $stmt->execute();
        $stmt->close();

        return ["mensaje" => "Habitación creada correctamente.", "clase" => "message-success"];
    }

    public function editarHabitacion($id, $datos) {
        $sql = "UPDATE habitaciones SET numero=?, tipo=?, descripcion=?, servicios=?, imagen=?, precio=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssssssd",
            $datos['numero'],
            $datos['tipo'],
            $datos['descripcion'],
            $datos['servicios'],
            $datos['imagen'],
            $datos['precio'],
            $id
        );
        $stmt->execute();
        $stmt->close();

        return ["mensaje" => "Habitación actualizada correctamente.", "clase" => "message-success"];
    }

    public function eliminarHabitacion($id) {
        $sql = "DELETE FROM habitaciones WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        return ["mensaje" => "Habitación eliminada.", "clase" => "message-success"];
    }

    public function obtenerHabitacionPorId($id) {
        $sql = "SELECT * FROM habitaciones WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $habitacion = $res->fetch_assoc();
        $stmt->close();
        return $habitacion;
    }

    public function listarHabitaciones($filtro_numero = "", $filtro_tipo = "") {
        $where = [];
        $params = [];
        $types = "";

        if (!empty($filtro_numero)) {
            $where[] = "numero LIKE ?";
            $params[] = "%$filtro_numero%";
            $types .= "s";
        }

        if (!empty($filtro_tipo)) {
            $where[] = "tipo LIKE ?";
            $params[] = "%$filtro_tipo%";
            $types .= "s";
        }

        $sql = "SELECT * FROM habitaciones";
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY numero";

        $stmt = $this->conn->prepare($sql);
        if ($where) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $res = $stmt->get_result();
        $habitaciones = [];
        while ($row = $res->fetch_assoc()) {
            $habitaciones[] = $row;
        }

        $stmt->close();
        return $habitaciones;
    }
}
