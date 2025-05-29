<?php
session_start();
// Redirigir si ya está logueado
if (isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}
require_once "includes/db.php";

$page_title = "Registro de Usuario";
$specific_css = "login_register.css";
$specific_js = "auth_validations.js"; // Para validaciones JS
$mensaje = "";
$claseMensaje = "";

// Variables para repoblar el formulario
$nombre = "";
$correo = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $contraseña = $_POST["contraseña"];
    $confirm_contraseña = $_POST["confirm_contraseña"];

    // Validaciones básicas del lado del servidor
    if (empty($nombre) || empty($correo) || empty($contraseña) || empty($confirm_contraseña)) {
        $mensaje = "Todos los campos son obligatorios.";
        $claseMensaje = "error";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "El formato del correo electrónico no es válido.";
        $claseMensaje = "error";
    } elseif (strlen($contraseña) < 5) {
        $mensaje = "La contraseña debe tener al menos 5 caracteres.";
        $claseMensaje = "error";
    } elseif (!preg_match('/[a-z]/', $contraseña)) {
        $mensaje = "La contraseña debe contener al menos una letra minúscula.";
        $claseMensaje = "error";
    } elseif (!preg_match('/[^A-Za-z0-9]/', $contraseña)) {
        $mensaje = "La contraseña debe contener al menos un carácter especial.";
        $claseMensaje = "error";
    } elseif ($contraseña !== $confirm_contraseña) {
        $mensaje = "Las contraseñas no coinciden.";
        $claseMensaje = "error";
    } else {
        // Verificar si el correo ya existe
        $sql_check = "SELECT id FROM usuarios WHERE correo = ?";
        $stmt_check = $conn->prepare($sql_check);
        if ($stmt_check) {
            $stmt_check->bind_param("s", $correo);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $mensaje = "El correo electrónico ya está registrado. Intenta con otro o <a href='login.php'>inicia sesión</a>.";
                $claseMensaje = "error";
            } else {
                // Proceder con el registro
                $contraseña_hashed = password_hash($contraseña, PASSWORD_DEFAULT);
                // Por defecto, el rol es 'usuario' según la BD, no es necesario insertarlo explícitamente aquí.
                $sql_insert = "INSERT INTO usuarios (nombre, correo, contraseña) VALUES (?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                if ($stmt_insert) {
                    $stmt_insert->bind_param("sss", $nombre, $correo, $contraseña_hashed);
                    if ($stmt_insert->execute()) {
                        $mensaje = "¡Registro exitoso! Ya puedes <a href='login.php'>iniciar sesión</a>.";
                        $claseMensaje = "success";
                        // Limpiar campos después de registro exitoso
                        $nombre = "";
                        $correo = "";
                    } else {
                        // error_log("Error al registrar usuario: " . $stmt_insert->error);
                        $mensaje = "Error al registrar el usuario. Por favor, inténtelo de nuevo.";
                        $claseMensaje = "error";
                    }
                    $stmt_insert->close();
                } else {
                    // error_log("Error en preparación de inserción de registro: " . $conn->error);
                    $mensaje = "Error del sistema. Intente más tarde.";
                    $claseMensaje = "error";
                }
            }
            $stmt_check->close();
        } else {
            // error_log("Error en preparación de chequeo de correo: " . $conn->error);
            $mensaje = "Error del sistema. Intente más tarde.";
            $claseMensaje = "error";
        }
    }
    // $conn->close(); // Se cierra en footer.php
}

include 'includes/header.php';
?>

<div class="container">
    <div class="form-container auth-form-container">
        <div class="logo-icon"></div>
        <h2>Crear una Cuenta</h2>

        <?php if (!empty($mensaje)): ?>
            <p class="message message-<?php echo $claseMensaje; ?>"><?php echo $mensaje; /* Permite HTML para enlaces */ ?></p>
        <?php endif; ?>
        
        <!-- Div para mensajes de validación del lado del cliente -->
        <!-- Se llenará desde auth_validations.js -->

        <form id="formRegistro" method="POST" action="registro.php" novalidate>
            <div class="form-group">
                <label for="nombre">Nombre Completo:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($nombre); ?>" required placeholder="Tu nombre y apellido">
            </div>

            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" class="form-control" value="<?php echo htmlspecialchars($correo); ?>" required placeholder="ejemplo@dominio.com">
            </div>

            <div class="form-group">
                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" class="form-control" required placeholder="Mín. 5 caracteres, 1 minúscula, 1 especial">
                <!-- Podrías añadir un div para mostrar la fortaleza de la contraseña aquí si lo deseas -->
            </div>

            <div class="form-group">
                <label for="confirm_contraseña">Confirmar Contraseña:</label>
                <input type="password" id="confirm_contraseña" name="confirm_contraseña" class="form-control" required placeholder="Repite tu contraseña">
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
            </div>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
</div>

<?php
include 'includes/footer.php';
?>