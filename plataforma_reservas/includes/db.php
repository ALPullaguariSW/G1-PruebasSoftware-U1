<?php
$host = "localhost";
$user = "root";
$password = ""; // Tu contraseña de XAMPP MySQL si la tienes
$database = "reservas"; // Asegúrate que este es el nombre de tu BD

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    // En producción, loguea el error en lugar de mostrarlo directamente
    // error_log("Error de conexión: " . $conn->connect_error);
    die("Error de conexión a la base de datos. Por favor, inténtelo más tarde.");
}
// Es buena práctica establecer el charset después de la conexión
if (!$conn->set_charset("utf8mb4")) {
    // error_log("Error al establecer el charset UTF-8: " . $conn->error);
    // No es necesario un die() aquí, pero sí loguearlo.
}
?>