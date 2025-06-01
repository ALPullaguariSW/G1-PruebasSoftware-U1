<?php
require_once "../includes/verificarSesion.php";
require_once "../includes/db.php";

// Solo permitir acceso a administradores
if ($_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit;
}

$page_title = "Gestionar Habitaciones";
$specific_css = "gestionar_espacios.css";

include '../includes/headerAdmin.php';

// Mensajes
$mensaje = "";
$claseMensaje = "";

// Crear o editar habitación
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $numero = trim($_POST["numero"]);
    $tipo = trim($_POST["tipo"]);
    $descripcion = trim($_POST["descripcion"]);
    $servicios = trim($_POST["servicios"]);
    $imagen = trim($_POST["imagen"]);
    $precio = trim($_POST["precio"]);
    $id = isset($_POST["id"]) ? intval($_POST["id"]) : null;

    if (empty($numero) || empty($tipo) || empty($precio)) {
        $mensaje = "El número, tipo y precio son obligatorios.";
        $claseMensaje = "message-error";
    } else {
        if ($id) {
            // Editar
            $sql = "UPDATE habitaciones SET numero=?, tipo=?, descripcion=?, servicios=?, imagen=?, precio=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssd", $numero, $tipo, $descripcion, $servicios, $imagen, $precio, $id);
            $stmt->execute();
            $mensaje = "Habitación actualizada correctamente.";
            $claseMensaje = "message-success";
            $stmt->close();
        } else {
            // Crear
            $sql = "INSERT INTO habitaciones (numero, tipo, descripcion, servicios, imagen, precio) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $numero, $tipo, $descripcion, $servicios, $imagen, $precio);
            $stmt->execute();
            $mensaje = "Habitación creada correctamente.";
            $claseMensaje = "message-success";
            $stmt->close();
        }
    }
}

// Eliminar habitación
if (isset($_GET["eliminar"])) {
    $id_eliminar = intval($_GET["eliminar"]);
    $conn->query("DELETE FROM habitaciones WHERE id = $id_eliminar");
    $mensaje = "Habitación eliminada.";
    $claseMensaje = "message-success";
}

// Si se va a editar, obtener datos
$editar = null;
if (isset($_GET["editar"])) {
    $id_editar = intval($_GET["editar"]);
    $res = $conn->query("SELECT * FROM habitaciones WHERE id = $id_editar");
    $editar = $res->fetch_assoc();
}

// Filtros de búsqueda
$filtro_numero = isset($_GET['filtro_numero']) ? trim($_GET['filtro_numero']) : '';
$filtro_tipo = isset($_GET['filtro_tipo']) ? trim($_GET['filtro_tipo']) : '';

// Listar habitaciones con filtros
$habitaciones = [];
$where = [];
$params = [];
$types = "";

if ($filtro_numero !== "") {
    $where[] = "numero LIKE ?";
    $params[] = "%$filtro_numero%";
    $types .= "s";
}
if ($filtro_tipo !== "") {
    $where[] = "tipo LIKE ?";
    $params[] = "%$filtro_tipo%";
    $types .= "s";
}

$sql = "SELECT * FROM habitaciones";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY numero";

$stmt = $conn->prepare($sql);
if ($where) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $habitaciones[] = $row;
}
$stmt->close();
?>

<div class="container">
    <h1>Gestionar Habitaciones</h1>

    <?php if ($mensaje): ?>
        <p class="message <?php echo $claseMensaje; ?>"><?php echo $mensaje; ?></p>
    <?php endif; ?>

   

    <!-- Formulario de alta/edición -->
    <form method="post" class="form-habitacion" style="margin-bottom:2em;">
        <input type="hidden" name="id" value="<?php echo $editar ? $editar["id"] : ""; ?>">

        <div class="form-row">
            <div class="form-group">
                <label>Número:</label>
                <input type="text" name="numero" required value="<?php echo $editar ? htmlspecialchars($editar["numero"]) : ""; ?>" placeholder="Ej: 101">
            </div>
            <div class="form-group">
                <label>Tipo:</label>
                <select name="tipo" required>
                    <option value="">Selecciona tipo</option>
                    <option value="Simple" <?php if($editar && $editar["tipo"]=="Simple") echo "selected"; ?>>Simple</option>
                    <option value="Doble" <?php if($editar && $editar["tipo"]=="Doble") echo "selected"; ?>>Doble</option>
                    <option value="Suite" <?php if($editar && $editar["tipo"]=="Suite") echo "selected"; ?>>Suite</option>
                    <option value="Familiar" <?php if($editar && $editar["tipo"]=="Familiar") echo "selected"; ?>>Familiar</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Precio (€):</label>
                <input type="number" step="0.01" name="precio" required value="<?php echo $editar ? htmlspecialchars($editar["precio"]) : ""; ?>" placeholder="Ej: 45.00">
            </div>
            <div class="form-group">
                <label>Imagen (URL):</label>
                <input type="text" name="imagen" value="<?php echo $editar ? htmlspecialchars($editar["imagen"]) : ""; ?>" placeholder="URL de la imagen">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group" style="flex:1;">
                <label>Descripción:</label>
                <textarea name="descripcion" rows="4" placeholder="Describe la habitación..."><?php echo $editar ? htmlspecialchars($editar["descripcion"]) : ""; ?></textarea>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Servicios:</label>
                <textarea name="servicios" rows="4" placeholder="Ej: Aire acondicionado, WiFi, TV..."><?php echo $editar ? htmlspecialchars($editar["servicios"]) : ""; ?></textarea>
            </div>
        </div>

        <div style="text-align:center; margin-top:1rem;">
            <button type="submit" class="btn btn-primary"><?php echo $editar ? "Actualizar" : "Crear"; ?></button>
            <?php if ($editar): ?>
                <a href="gestionar_espacios.php" class="btn btn-secondary">Cancelar</a>
            <?php endif; ?>
        </div>
    </form>
     <!-- Filtros de búsqueda -->
    <form method="get" class="form-filtros" style="margin-bottom:2em; display:flex; gap:1rem; flex-wrap:wrap; align-items:center;">
        <input type="text" name="filtro_numero" placeholder="Buscar por número" value="<?php echo htmlspecialchars($filtro_numero); ?>" style="padding:0.4rem 0.7rem; border-radius:8px; border:1px solid #2e3644; background:#1a2233; color:#f1f3fa;">
        <select name="filtro_tipo" style="padding:0.4rem 0.7rem; border-radius:8px; border:1px solid #2e3644; background:#1a2233; color:#f1f3fa;">
            <option value="">Todos los tipos</option>
            <option value="Simple" <?php if($filtro_tipo==="Simple") echo "selected"; ?>>Simple</option>
            <option value="Doble" <?php if($filtro_tipo==="Doble") echo "selected"; ?>>Doble</option>
            <option value="Suite" <?php if($filtro_tipo==="Suite") echo "selected"; ?>>Suite</option>
            <option value="Familiar" <?php if($filtro_tipo==="Familiar") echo "selected"; ?>>Familiar</option>
        </select>
        <button type="submit" class="btn btn-primary">Filtrar</button>
        <a href="gestionar_espacios.php" class="btn btn-secondary">Limpiar</a>
    </form>

    <table class="styled-table">
        <thead>
            <tr>
                <th>Número</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Servicios</th>
                <th>Imagen</th>
                <th>Precio (€)</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($habitaciones as $hab): ?>
            <tr>
                <td><?php echo htmlspecialchars($hab["numero"]); ?></td>
                <td><?php echo htmlspecialchars($hab["tipo"]); ?></td>
                <td><?php echo htmlspecialchars($hab["descripcion"]); ?></td>
                <td><?php echo htmlspecialchars($hab["servicios"]); ?></td>
                <td>
                    <?php if (!empty($hab["imagen"])): ?>
                        <img src="<?php echo htmlspecialchars($hab["imagen"]); ?>" alt="Imagen habitación" style="max-width:70px; border-radius:6px;">
                    <?php endif; ?>
                </td>
                <td><?php echo number_format($hab["precio"], 2); ?></td>
                <td>
                    <a href="?editar=<?php echo $hab["id"]; ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="?eliminar=<?php echo $hab["id"]; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta habitación?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>