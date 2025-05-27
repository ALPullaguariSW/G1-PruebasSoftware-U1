<?php
require_once "includes/verificarSesion.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Sistema de Reservas</title>
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/inicio.css">
</head>
<body>
    <div class="formulario-contenedor">
        <h2>¡Bienvenido, <?php echo $_SESSION["usuario_nombre"]; ?>!</h2>
        <div class="menu-links">
            <a class="menu-link" href="reservar.php">
                <span class="icon"><img src="https://cdn-icons-png.flaticon.com/512/854/854878.png" alt="Reservar" width="28" height="28"></span>
                Realizar una reserva
            </a>
            <a class="menu-link" href="mis_reservas.php">
                <span class="icon"><img src="https://cdn-icons-png.flaticon.com/512/1828/1828919.png" alt="Mis reservas" width="28" height="28"></span>
                Ver mis reservas
            </a>
            <a class="menu-link" href="logout.php">
                <span class="icon"><img src="https://cdn-icons-png.flaticon.com/512/1828/1828479.png" alt="Cerrar sesión" width="28" height="28"></span>
                Cerrar sesión
            </a>
        </div>
    </div>
</body>
</html>
