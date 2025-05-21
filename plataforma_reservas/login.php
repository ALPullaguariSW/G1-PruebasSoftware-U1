<?php
session_start();
require_once "includes/db.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST["correo"]);
    $contraseña = $_POST["contraseña"];

    $sql = "SELECT id, nombre, contraseña, rol FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($contraseña, $usuario["contraseña"])) {
           $_SESSION["usuario_id"] = $usuario["id"];
           $_SESSION["usuario_nombre"] = $usuario["nombre"];
           $_SESSION["rol"] = $usuario["rol"]; 

           
            header("Location: index.php");
            exit;
        } else {
            $mensaje = "Contraseña incorrecta.";
        }
    } else {
        $mensaje = "Usuario no encontrado.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<div class="formulario-contenedor">
    <h2>Iniciar Sesión</h2>
    <?php if ($mensaje): ?>
        <p class="mensaje"><?php echo $mensaje; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="correo">Correo:</label>
        <input type="email" name="correo" required>

        <label for="contraseña">Contraseña:</label>
        <input type="password" name="contraseña" required>

        <button type="submit">Entrar</button>
    </form>
    <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
</div>
</body>
</html>
