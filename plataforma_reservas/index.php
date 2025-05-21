<?php
require_once "includes/verificarSesion.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Sistema de Reservas</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="formulario-contenedor">
        <h2>¡Bienvenido, <?php echo $_SESSION["usuario_nombre"]; ?>!</h2>
        <p><a href="reservar.php">Realizar una reserva</a></p>
        <p><a href="mis_reservas.php">Ver mis reservas</a></p>
        <p><a href="logout.php">Cerrar sesión</a></p>
    </div>
</body>
</html>
