<?php
http_response_code(404); // Importante: Enviar el código de estado HTTP correcto
$page_title = "Página no Encontrada (404)";
// No necesitamos CSS específico aquí a menos que quieras algo muy particular para la página 404
// $specific_css = "404.css";

// Si tu header/footer necesitan la conexión a BD, inclúyela
// require_once "includes/db.php"; // Probablemente no sea necesario para una simple página 404

include 'includes/header.php';
?>

<div class="container text-center" style="padding-top: 4rem; padding-bottom: 4rem;">
    <img src="<?php echo $base_url; ?>images/404_illustration.svg" alt="Ilustración de error 404" style="max-width: 300px; margin-bottom: 2rem;">
    <h1>Oops! Página no Encontrada</h1>
    <p class="lead" style="font-size: 1.2rem; color: var(--text-muted-color);">Lo sentimos, la página que estás buscando no existe o ha sido movida.</p>
    <p>Puedes intentar lo siguiente:</p>
    <ul style="list-style: none; padding-left: 0; margin-bottom: 2rem;">
        <li>Verificar la URL para asegurarte de que esté escrita correctamente.</li>
        <li>Volver a la <a href="<?php echo $base_url; ?>index.php">página de inicio</a>.</li>
        <?php if (isset($_SESSION["usuario_id"])): ?>
            <li>Ir a tu <a href="<?php echo $base_url; ?>indx.php">Dashboard</a>.</li>
        <?php else: ?>
            <li><a href="<?php echo $base_url; ?>login.php">Iniciar sesión</a> si tienes una cuenta.</li>
        <?php endif; ?>
    </ul>
    <a href="<?php echo $base_url; ?>index.php" class="btn btn-primary">Volver al Inicio</a>
</div>

<?php
include 'includes/footer.php';
?>