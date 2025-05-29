<?php
require_once "includes/verificarSesion.php"; // Ya inicia la sesión
$page_title = "Dashboard";
$specific_css = "dashboard.css"; // CSS específico para el dashboard

// Puedes añadir lógica aquí para obtener datos para el dashboard,
// como la próxima reserva, etc.

include 'includes/header.php';
?>

<div class="dashboard-header">
    <div class="container">
        <h1>Bienvenido a HotelSys</h1>
        <p class="welcome-message">Hola, <?php echo htmlspecialchars($_SESSION["usuario_nombre"]); ?>. ¿Qué te gustaría hacer hoy?</p>
    </div>
</div>

<div class="container">
    <div class="dashboard-menu">
        <a class="menu-card" href="reservar.php">
            <div class="icon-wrapper">
                <img src="https://cdn-icons-png.flaticon.com/512/854/854878.png" alt="Reservar" width="48" height="48">
            </div>
            <h3>Realizar una Reserva</h3>
            <p>Busca y reserva tu habitación ideal.</p>
        </a>
        <a class="menu-card" href="mis_reservas.php">
            <div class="icon-wrapper">
                <img src="https://cdn-icons-png.flaticon.com/512/1828/1828919.png" alt="Mis reservas" width="48" height="48">
            </div>
            <h3>Ver Mis Reservas</h3>
            <p>Consulta y gestiona tus reservas activas.</p>
        </a>
        <a class="menu-card" href="logout.php"> <!-- O podrías llevar a una página de perfil si la tuvieras -->
             <div class="icon-wrapper">
                <img src="https://cdn-icons-png.flaticon.com/512/1828/1828479.png" alt="Cerrar sesión" width="48" height="48">
            </div>
            <h3>Cerrar Sesión</h3>
            <p>Finaliza tu sesión de forma segura.</p>
        </a>
        <!-- Puedes añadir más tarjetas aquí para otras funcionalidades -->
    </div>
</div>

<?php
include 'includes/footer.php';
?>