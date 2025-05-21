<?php
require_once "includes/verificarSesion.php";
require_once "includes/db.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fecha_inicio = $_POST["fecha_inicio"];
    $fecha_fin = $_POST["fecha_fin"];
    $habitacion = $_POST["habitacion"];
    $usuario_id = $_SESSION["usuario_id"];

    // Validación básica
    if ($fecha_fin <= $fecha_inicio) {
        $mensaje = "La fecha de salida debe ser posterior a la fecha de entrada.";
    } else {
        // Verificar disponibilidad
        $sql = "SELECT * FROM reservas 
                WHERE habitacion = ?
                AND (
                    (fecha_inicio <= ? AND fecha_fin > ?) OR
                    (fecha_inicio < ? AND fecha_fin >= ?) OR
                    (? <= fecha_inicio AND ? >= fecha_fin)
                )";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $habitacion, $fecha_inicio, $fecha_inicio, $fecha_fin, $fecha_fin, $fecha_inicio, $fecha_fin);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $mensaje = "La habitación ya está reservada para esas fechas.";
        } else {
            $sql = "INSERT INTO reservas (usuario_id, habitacion, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $usuario_id, $habitacion, $fecha_inicio, $fecha_fin);
            if ($stmt->execute()) {
                $mensaje = "Reserva registrada con éxito.";
            } else {
                $mensaje = "Error al registrar la reserva.";
            }
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservar habitación</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<div class="formulario-contenedor">
    <h2>Reservar Habitación</h2>
    <?php if ($mensaje): ?>
        <p class="mensaje"><?php echo $mensaje; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="habitacion">Tipo de habitación:</label>
        <select name="habitacion" required>
            <option value="">Seleccione una</option>
            <option value="Simple">Simple</option>
            <option value="Doble">Doble</option>
            <option value="Suite">Suite</option>
        </select>

        <label for="fecha_inicio">Fecha de entrada:</label>
        <input type="date" name="fecha_inicio" required>

        <label for="fecha_fin">Fecha de salida:</label>
        <input type="date" name="fecha_fin" required>

        <button type="submit">Reservar</button>
    </form>
    <p><a href="index.php">Volver al inicio</a></p>
</div>
</body>
</html>
