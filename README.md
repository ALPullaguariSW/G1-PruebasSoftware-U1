# HotelSys - Sistema de Reservas de Hotel

HotelSys es un sistema web básico para la gestión de reservas de habitaciones de hotel, desarrollado como proyecto para la materia de Pruebas de Software. Permite a los usuarios registrarse, iniciar sesión, buscar habitaciones disponibles por fecha y tipo, realizar reservas y ver sus reservas existentes.

## Características Principales

*   **Autenticación de Usuarios:**
    *   Registro de nuevos usuarios.
    *   Inicio de sesión seguro con contraseñas hasheadas.
    *   Cierre de sesión.
*   **Gestión de Reservas (Usuario):**
    *   Búsqueda de habitaciones por rango de fechas y tipo de habitación.
    *   Visualización de catálogo de habitaciones con detalles (descripción, servicios, precio, imagen).
    *   Indicador de disponibilidad en tiempo real (basado en fechas seleccionadas).
    *   Modal con información detallada de cada habitación.
    *   Proceso de reserva seleccionando una habitación disponible.
    *   Visualización de "Mis Reservas" con opción de cancelar reservas futuras.
*   **Diseño:**
    *   Interfaz de usuario moderna y responsiva.
    *   Tema oscuro con acentos de color.
    *   Uso de Google Fonts (Poppins) para una tipografía legible.
*   **Backend:**
    *   Desarrollado en PHP.
    *   Interacción con base de datos MySQL/MariaDB.
    *   Uso de sentencias preparadas para prevenir inyección SQL.
    *   Validaciones tanto del lado del cliente (JavaScript) como del servidor (PHP).

## Tecnologías Utilizadas

*   **Frontend:**
    *   HTML5
    *   CSS3 (con variables CSS, Flexbox, Grid)
    *   JavaScript (Vanilla JS para interacciones y validaciones)
*   **Backend:**
    *   PHP 8.x
*   **Base de Datos:**
    *   MySQL / MariaDB
*   **Entorno de Desarrollo:**
    *   XAMPP (Apache, MySQL, PHP)

## Estructura del Proyecto
proyecto/
├── css/ # Hojas de estilo CSS
│ ├── main.css # Estilos globales y variables
│ ├── login_register.css # Estilos para login y registro
│ ├── dashboard.css # Estilos para el dashboard (index.php)
│ ├── mis_reservas.css # Estilos para la página de mis reservas
│ └── reservar.css # Estilos para la página de reservar y catálogo
├── images/ # Imágenes del sitio (logo, favicon, etc.)
│ ├── logo.png
│ ├── favicon.png
│ └── default_room.jpg # Imagen por defecto para habitaciones
├── includes/ # Archivos PHP reutilizables
│ ├── db.php # Configuración y conexión a la BD
│ ├── verificarSesion.php # Verifica si el usuario ha iniciado sesión
│ ├── header.php # Cabecera HTML común
│ └── footer.php # Pie de página HTML común
├── js/ # Archivos JavaScript
│ ├── main.js # Scripts globales (ej. menú móvil)
│ ├── auth_validations.js # Validaciones para formularios de autenticación
│ └── booking.js # Scripts para la página de reservas (modal, fechas)
├── admin/ # (Futuro) Carpeta para el panel de administración
├── index.php # Dashboard principal del usuario logueado
├── login.php # Página de inicio de sesión
├── logout.php # Script para cerrar sesión
├── mis_reservas.php # Página para ver las reservas del usuario
├── registro.php # Página de registro de usuarios
├── reservar.php # Página para buscar y realizar reservas
└── README.md # Este archivo

## Instalación y Configuración

1.  **Clonar el Repositorio (si aplica) o Descargar los Archivos:**
    ```bash
    # git clone https://tu-repositorio.com/proyecto.git
    # cd proyecto
    ```
    Si no usas Git, simplemente copia la carpeta `proyecto` a tu directorio `htdocs` de XAMPP.

2.  **Configurar la Base de Datos:**
    *   Asegúrate de tener XAMPP instalado y los servicios de Apache y MySQL iniciados.
    *   Abre phpMyAdmin (normalmente en `http://localhost/phpmyadmin`).
    *   Puedes crear una base de datos manualmente llamada `reservas` (con cotejamiento `utf8mb4_general_ci` o `utf8mb4_unicode_ci`).
    *   Importa el archivo SQL proporcionado (`tu_archivo_sql.sql` o el script completo en el código fuente) en la base de datos `reservas`. Este archivo creará las tablas necesarias (`usuarios`, `habitaciones`, `reservas`) y algunos datos de ejemplo.
        *Alternativamente, el script SQL proporcionado puede incluir `DROP DATABASE IF EXISTS reservas; CREATE DATABASE reservas; USE reservas;` lo que simplifica la importación inicial.*

3.  **Configurar la Conexión a la Base de Datos en PHP:**
    *   Edita el archivo `includes/db.php`.
    *   Verifica que los siguientes parámetros sean correctos para tu configuración de XAMPP:
        ```php
        $host = "localhost";
        $user = "root";
        $password = ""; // Tu contraseña de MySQL en XAMPP (usualmente vacía por defecto)
        $database = "reservas";
        ```

4.  **Configurar la URL Base (si es necesario):**
    *   Edita el archivo `includes/header.php` (y `includes/footer.php` si también usa la variable).
    *   Ajusta la variable `$base_url` según dónde hayas colocado el proyecto:
        ```php
        $base_url = "/proyecto/"; // Si accedes a través de http://localhost/proyecto/
        // $base_url = "/";       // Si has configurado un VirtualHost y es la raíz del dominio
        ```
    Esto asegura que los enlaces a CSS, JS e imágenes funcionen correctamente.

5.  **Acceder al Proyecto:**
    *   Abre tu navegador y ve a `http://localhost/proyecto/` (o la URL que corresponda según tu configuración).
    *   Deberías ver la página de inicio de sesión o registro.

## Uso

*   **Registro:** Crea una nueva cuenta desde la página de registro.
    *   Usuario de prueba 1: `carlos.r@example.com` / `password123`
    *   Usuario de prueba 2: `cristian.o@example.com` / `password456`
    *   Usuario Admin de prueba: `ana.admin@example.com` / `adminpass` (Funcionalidad de admin no implementada en el frontend aún).
*   **Login:** Inicia sesión con tus credenciales.
*   **Dashboard:** Accede a las opciones principales: Realizar Reserva, Ver Mis Reservas.
*   **Reservar:**
    1.  Selecciona fechas de entrada y salida, y opcionalmente un tipo de habitación.
    2.  Haz clic en "Consultar" para ver las habitaciones disponibles.
    3.  Explora el catálogo, usa el botón "Más Info" para ver detalles en un modal.
    4.  Selecciona una habitación disponible usando el radio button.
    5.  Haz clic en "Reservar Selección".
*   **Mis Reservas:** Revisa tus reservas activas. Puedes cancelar reservas cuya fecha de inicio aún no haya pasado.

## Pruebas de Software

Este proyecto está diseñado para ser un sujeto de pruebas. Algunos escenarios a considerar para las pruebas:

*   **Pruebas Funcionales:**
    *   Registro de usuario (campos vacíos, correo inválido, correo duplicado, contraseñas no coinciden, registro exitoso).
    *   Login (credenciales incorrectas, credenciales correctas).
    *   Flujo de reserva completo (selección de fechas, filtros, selección de habitación, confirmación).
    *   Disponibilidad de habitaciones (verificar que no se puedan reservar habitaciones ocupadas).
    *   Cancelación de reservas.
    *   Visualización de "Mis Reservas".
    *   Funcionamiento de `logout`.
*   **Pruebas de Usabilidad:**
    *   Claridad de la interfaz.
    *   Facilidad de navegación.
    *   Mensajes de error y confirmación.
    *   Responsividad en diferentes tamaños de pantalla.
*   **Pruebas de Seguridad (Básicas):**
    *   Intentos de inyección SQL (verificar que las sentencias preparadas funcionen).
    *   XSS (verificar que la salida se sanitice con `htmlspecialchars`).
    *   Protección de rutas (no se puede acceder a páginas protegidas sin login).
*   **Pruebas de Rendimiento (Básicas):**
    *   Tiempos de carga de página.
    *   Respuesta de consultas a la base de datos (especialmente en la búsqueda de disponibilidad).
*   **Pruebas de Accesibilidad (A11y):**
    *   Navegación por teclado.
    *   Contraste de colores.
    *   Uso de atributos ARIA.

## Posibles Mejoras Futuras

*   Panel de Administración (CRUD para habitaciones, gestión de usuarios, ver todas las reservas).
    *   Gestión de tipos de habitación.
    *   Establecer precios y disponibilidad de forma más granular.
*   Envío de correos electrónicos de confirmación (registro, reserva, cancelación).
*   Paginación para listas largas (ej. catálogo de habitaciones, mis reservas).
*   Mejoras en la búsqueda (filtros por precio, servicios, etc.).
*   Integración de un sistema de pago (simulado o real).
*   Internacionalización (i18n) y localización (l10n).
*   Gestión de perfiles de usuario.
*   Sistema de reseñas y calificaciones de habitaciones.

## Contribuciones

Este es un proyecto académico. Si tienes sugerencias o encuentras errores, por favor informa al equipo del proyecto.

---

Hecho con ❤️ para Pruebas de Software.