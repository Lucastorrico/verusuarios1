<?php
require_once 'config.php';

$db = new Database();
$conexion = $db->getConnection();

$busqueda = isset($_GET['busqueda']) ? sanitizeInput($_GET['busqueda']) : "";
$success_message = '';

if (isset($_GET['success'])) {
    switch ($_GET['success']) {
        case 'created':
            $success_message = 'Usuario creado exitosamente.';
            break;
        case 'updated':
            $success_message = 'Usuario actualizado exitosamente.';
            break;
    }
}

if (!empty($busqueda)) {
    $sql = "SELECT * FROM usuarios WHERE uid LIKE ? OR nombre LIKE ? OR telefono LIKE ? OR apikey LIKE ? ORDER BY id ASC";
    $search_param = "%$busqueda%";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $sql = "SELECT * FROM usuarios ORDER BY id ASC";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Usuarios Registrados</h1>

        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <div class="top-bar">
            <form method="GET" style="display: inline-block;">
                <input type="text" name="busqueda" placeholder="Buscar por nombre, UID, teléfono o API key" value="<?php echo sanitizeInput($busqueda); ?>">
                <button class="btn" type="submit">🔍 Buscar</button>
            </form>
            <a href="crear_usuario.php" class="btn">➕ Crear Usuario</a>
        </div>

        <?php if ($resultado->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>UID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>API Key</th>
                    <th>Acciones</th>
                </tr>
                <?php while($fila = $resultado->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo sanitizeInput($fila['id']); ?></td>
                        <td><?php echo sanitizeInput($fila['uid']); ?></td>
                        <td><?php echo sanitizeInput($fila['nombre']); ?></td>
                        <td><?php echo sanitizeInput($fila['telefono']); ?></td>
                        <td><?php echo sanitizeInput($fila['apikey']); ?></td>
                        <td>
                            <a href="editar_usuario.php?id=<?php echo sanitizeInput($fila['id']); ?>" class="btn edit">✏️ Editar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No se encontraron usuarios<?php echo !empty($busqueda) ? ' que coincidan con la búsqueda' : ''; ?>.</p>
        <?php endif; ?>
    </div>
</body>
</html>



