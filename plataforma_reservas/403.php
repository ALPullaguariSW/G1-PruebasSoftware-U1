<?php
http_response_code(403); // Importante: Enviar el código de estado HTTP correcto
$page_title = "Acceso Denegado (403)";
// No necesitamos CSS específico aquí a menos que quieras algo muy particular para la página 403
// $specific_css = "403.css";

// Si tu header/footer necesitan la conexión a BD, inclúyela
// require_once "includes/db.php"; // Probablemente no sea necesario

include 'includes/header.php'; // Usamos el header general
?>

<div class="container text-center" style="padding-top: 4rem; padding-bottom: 4rem;">
    <img src="<?php echo $base_url; ?>images/403_illustration.svg" alt="Ilustración de error 403 Acceso Denegado" style="max-width: 300px; margin-bottom: 2rem;">
    <h1>Acceso Denegado</h1>
    <p class="lead" style="font-size: 1.2rem; color: var(--text-muted-color);">Lo sentimos, no tienes permiso para acceder a este recurso.</p>
    <p>Esto puede ocurrir si intentas acceder a un directorio sin una página de inicio o a un área restringida.</p>
    <ul style="list-style: none; padding-left: 0; margin-bottom: 2rem;">
        <li>Asegúrate de haber iniciado sesión con los permisos correctos si intentas acceder a un área de administración.</li>
        <li>Verifica la URL para asegurarte de que esté escrita correctamente.</li>
        <li>Volver a la <a href="<?php echo $base_url; ?>index.php">página de inicio</a>.</li>
    </ul>
    <a href="<?php echo $base_url; ?>index.php" class="btn btn-primary">Volver al Inicio</a>
</div>

<?php
include 'includes/footer.php'; // Usamos el footer general
?>