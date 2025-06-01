<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$base_url = "/plataforma_reservas/";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : "Admin"; ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/main.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/admin_header.css">
    <?php if (isset($specific_css)): ?>
        <link rel="stylesheet" href="<?php echo $base_url; ?>css/<?php echo htmlspecialchars($specific_css); ?>">
    <?php endif; ?>
    
</head>
<body>
    <header class="admin-header" style="background: var(--primary-color); color: #fff; padding: 1rem 0;">
        <div class="container" style="display:flex; align-items:center; justify-content:space-between;">
            <div style="font-size:1.5rem; font-weight:bold;">
                <a href="<?php echo $base_url; ?>admin/dashboard.php" style="color:#fff; text-decoration:none;">HotelSys Admin</a>
            </div>
            <nav>
                <a href="<?php echo $base_url; ?>admin/dashboard.php" class="admin-nav-link">Dashboard</a>
                <a href="<?php echo $base_url; ?>admin/disponibilidad.php" class="admin-nav-link">Disponibilidad</a>
                <a href="<?php echo $base_url; ?>admin/gestionar_espacios.php" class="admin-nav-link">Gestionar Habitaciones</a>
                <a href="<?php echo $base_url; ?>admin/reservas.php" class="admin-nav-link">Reservas</a>
                <a href="<?php echo $base_url; ?>logout.php" class="admin-nav-link" style="color:var(--secondary-color);">Cerrar sesión</a>
            </nav>
        </div>
    </header>
    <main>
