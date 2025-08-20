<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conexion = new mysqli("localhost", "root", "", "asistencia");

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $nombre = $_POST['nombre'];
    $uid = $_POST['uid'];
    $telefono = $_POST['telefono'];
    $apikey = $_POST['apikey'];

    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, uid, telefono, apikey) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $uid, $telefono, $apikey);

    if ($stmt->execute()) {
        header("Location: ver_usuarios.php");
        exit;
    } else {
        echo "Error al crear usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
</head>
<body>
    <h1>Crear Nuevo Usuario</h1>
    <form method="POST">
        
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>
        <label>UID:</label><br>
        <input type="text" name="uid" required><br><br>
        <label>Teléfono:</label><br>
        <input type="text" name="telefono" required><br><br>
        <label>API Key:</label><br>
        <input type="text" name="apikey" required><br><br>
        <button type="submit">Guardar</button>
    </form>
    <br>
    <a href="ver_usuarios.php">← Volver</a>
</body>
</html>
