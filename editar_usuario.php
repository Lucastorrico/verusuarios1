<?php
require_once 'config.php';

$db = new Database();
$conexion = $db->getConnection();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $uid = sanitizeInput($_POST['uid']);
    $nombre = sanitizeInput($_POST['nombre']);
    $telefono = sanitizeInput($_POST['telefono']);
    $apikey = sanitizeInput($_POST['apikey']);

    $validation_error = validateRequired(array(
        'uid' => $uid,
        'nombre' => $nombre,
        'telefono' => $telefono,
        'apikey' => $apikey
    ));

    if ($validation_error) {
        $error = $validation_error;
    } elseif (!$id) {
        $error = "ID de usuario inválido.";
    } else {
        $stmt = $conexion->prepare("UPDATE usuarios SET uid=?, nombre=?, telefono=?, apikey=? WHERE id=?");
        $stmt->bind_param("ssssi", $uid, $nombre, $telefono, $apikey, $id);

        if ($stmt->execute()) {
            header("Location: ver_usuarios.php?success=updated");
            exit;
        } else {
            $error = "Error al actualizar usuario: " . $conexion->error;
        }
        $stmt->close();
    }
} else {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        die("ID de usuario inválido.");
    }
    
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();
    $stmt->close();
    
    if (!$usuario) {
        die("Usuario no encontrado.");
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Editar Usuario</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo sanitizeInput($usuario['id']); ?>">
            
            <div class="form-group">
                <label>UID:</label>
                <input type="text" name="uid" value="<?php echo sanitizeInput($usuario['uid']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" value="<?php echo sanitizeInput($usuario['nombre']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Teléfono:</label>
                <input type="text" name="telefono" value="<?php echo sanitizeInput($usuario['telefono']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>API Key:</label>
                <input type="text" name="apikey" value="<?php echo sanitizeInput($usuario['apikey']); ?>" required>
            </div>
            
            <button type="submit" class="btn">Actualizar</button>
        </form>
        
        <br>
        <a href="ver_usuarios.php" class="btn">← Volver</a>
    </div>
</body>
</html>
