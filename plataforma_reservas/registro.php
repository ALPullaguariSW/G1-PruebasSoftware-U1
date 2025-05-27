<?php
require_once "includes/db.php";

$mensaje = "";
$claseMensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $contraseña = password_hash($_POST["contraseña"], PASSWORD_DEFAULT);

    // Verificar si el correo ya existe
    $sql_check = "SELECT id FROM usuarios WHERE correo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $correo);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $mensaje = "El correo ya está registrado. Intenta con otro o inicia sesión.";
        $claseMensaje = "error";
    } else {
        $sql = "INSERT INTO usuarios (nombre, correo, contraseña) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nombre, $correo, $contraseña);

        if ($stmt->execute()) {
            $mensaje = "Registro exitoso. Ya puedes iniciar sesión.";
            $claseMensaje = "exito";
        } else {
            $mensaje = "Error al registrar: " . $stmt->error;
            $claseMensaje = "error";
        }
        $stmt->close();
    }
    $stmt_check->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Reservas Hotel</title>
    <link rel="stylesheet" href="css/estilos.css">
     <link rel="stylesheet" href="css/estilosRegistro.css">
</head>
<body>
    <div class="formulario-contenedor">
        <h2>Registro de Usuario</h2>
        <?php if ($mensaje): ?>
            <p class="mensaje <?php echo $claseMensaje; ?>"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form id="formRegistro" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required>

            <label for="correo">Correo electrónico:</label>
            <input type="email" name="correo" required>

            <label for="contraseña">Contraseña:</label>
            <input type="password" name="contraseña" required>

            <button type="submit">Registrarse</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>
    <script src="js\validaciones.js" defer></script>
</body>
</html>

