<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = sanitizeInput($_POST['nombre']);
    $uid = sanitizeInput($_POST['uid']);
    $telefono = sanitizeInput($_POST['telefono']);
    $apikey = sanitizeInput($_POST['apikey']);

    $validation_error = validateRequired(array(
        'nombre' => $nombre,
        'uid' => $uid,
        'telefono' => $telefono,
        'apikey' => $apikey
    ));

    if ($validation_error) {
        $error = $validation_error;
    } else {
        $db = new Database();
        $conexion = $db->getConnection();

        $stmt_check = $conexion->prepare("SELECT id FROM usuarios WHERE uid = ?");
        $stmt_check->bind_param("s", $uid);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $error = "El UID ya existe. Por favor, utiliza un UID diferente.";
            $stmt_check->close();
        } else {
            $stmt_check->close();
            
            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, uid, telefono, apikey) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $uid, $telefono, $apikey);

            if ($stmt->execute()) {
                header("Location: ver_usuarios.php?success=created");
                exit;
            } else {
                $error = "Error al crear usuario: " . $conexion->error;
            }
            $stmt->close();
        }
        $db->closeConnection();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Crear Nuevo Usuario</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?php echo isset($_POST['nombre']) ? sanitizeInput($_POST['nombre']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label>UID:</label>
                <input type="text" name="uid" value="<?php echo isset($_POST['uid']) ? sanitizeInput($_POST['uid']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Teléfono:</label>
                <input type="text" name="telefono" value="<?php echo isset($_POST['telefono']) ? sanitizeInput($_POST['telefono']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label>API Key:</label>
                <input type="text" name="apikey" value="<?php echo isset($_POST['apikey']) ? sanitizeInput($_POST['apikey']) : ''; ?>" required>
            </div>
            
            <button type="submit" class="btn">Guardar</button>
        </form>
        
        <br>
        <a href="ver_usuarios.php" class="btn">← Volver</a>
    </div>
</body>
</html>
