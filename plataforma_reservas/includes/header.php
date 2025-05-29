<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$base_url = "/proyecto/"; // Ajusta esto si tu proyecto está en una subcarpeta diferente de htdocs
                         // Si está en la raíz de htdocs, usa "/"
                         // Si usas XAMPP y accedes como localhost/proyecto/, entonces "/proyecto/" es correcto.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' : ''; ?>Hotel Reserva System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/main.css">
    <?php if (isset($specific_css) && is_array($specific_css)): ?>
        <?php foreach ($specific_css as $css_file): ?>
            <link rel="stylesheet" href="<?php echo $base_url; ?>css/<?php echo htmlspecialchars($css_file); ?>">
        <?php endforeach; ?>
    <?php elseif (isset($specific_css)): ?>
        <link rel="stylesheet" href="<?php echo $base_url; ?>css/<?php echo htmlspecialchars($specific_css); ?>">
    <?php endif; ?>
    <link rel="icon" href="<?php echo $base_url; ?>images/favicon.png" type="image/png">
</head>
<body>
    <div class="site-wrapper">
        <header class="site-header">
            <div class="container header-container">
                <a href="<?php echo $base_url; ?><?php echo isset($_SESSION["usuario_id"]) ? 'index.php' : 'login.php'; ?>" class="logo">
                    <img src="<?php echo $base_url; ?>images/logo.png" alt="HotelSys Logo" height="40">
                    <span>HotelSys</span>
                </a>
                <nav class="main-nav">
                    <ul>
                        <?php if (isset($_SESSION["usuario_id"])): ?>
                            <li><a href="<?php echo $base_url; ?>index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Dashboard</a></li>
                            <li><a href="<?php echo $base_url; ?>reservar.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reservar.php' ? 'active' : ''; ?>">Reservar</a></li>
                            <li><a href="<?php echo $base_url; ?>mis_reservas.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'mis_reservas.php' ? 'active' : ''; ?>">Mis Reservas</a></li>
                            <li><a href="<?php echo $base_url; ?>logout.php">Cerrar Sesión (<?php echo htmlspecialchars($_SESSION["usuario_nombre"]); ?>)</a></li>
                        <?php else: ?>
                            <li><a href="<?php echo $base_url; ?>login.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : ''; ?>">Iniciar Sesión</a></li>
                            <li><a href="<?php echo $base_url; ?>registro.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'registro.php' ? 'active' : ''; ?>">Registrarse</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <button class="menu-toggle" aria-label="Toggle menu" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </header>
        <main class="site-main">
            <!-- El .container se moverá dentro de cada página para mayor flexibilidad -->