<?php
session_start();
// Redirigir si ya está logueado
if (isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

require_once "includes/db.php";

$page_title = "Iniciar Sesión";
$specific_css = "login_register.css"; // CSS combinado para login y registro
$mensaje = "";
$correo = ""; // Para repoblar el campo

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST["correo"]);
    $contraseña = $_POST["contraseña"];

    if (empty($correo) || empty($contraseña)) {
        $mensaje = "Por favor, complete todos los campos.";
        $claseMensaje = "error";
    } else {
        $sql = "SELECT id, nombre, contraseña, rol FROM usuarios WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows === 1) {
                $usuario = $resultado->fetch_assoc();
                if (password_verify($contraseña, $usuario["contraseña"])) {
                    // Regenerar ID de sesión por seguridad
                    session_regenerate_id(true);
                    $_SESSION["usuario_id"] = $usuario["id"];
                    $_SESSION["usuario_nombre"] = $usuario["nombre"];
                    $_SESSION["rol"] = $usuario["rol"];
                    
                    header("Location: index.php");
                    exit;
                } else {
                    $mensaje = "Correo o contraseña incorrecta.";
                    $claseMensaje = "error";
                }
            } else {
                $mensaje = "Correo o contraseña incorrecta."; // Mensaje genérico por seguridad
                $claseMensaje = "error";
            }
            $stmt->close();
        } else {
            // error_log("Error en la preparación de la consulta de login: " . $conn->error);
            $mensaje = "Error del sistema. Intente más tarde.";
            $claseMensaje = "error";
        }
    }
    // $conn->close(); // Se cierra en footer.php
}

// Incluir header
include 'includes/header.php';
?>

<div class="container">
    <div class="form-container auth-form-container">
        <div class="logo-icon"></div> <!-- Placeholder para icono -->
        <h2>Iniciar Sesión</h2>

        <?php if (!empty($mensaje)): ?>
            <p class="message message-<?php echo $claseMensaje; ?>"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php" novalidate>
            <div class="form-group">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" class="form-control" value="<?php echo htmlspecialchars($correo); ?>" required placeholder="ejemplo@dominio.com">
            </div>

            <div class="form-group">
                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" class="form-control" required placeholder="Tu contraseña">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </div>
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>
</div>

<?php
// Incluir footer
include 'includes/footer.php';
?>