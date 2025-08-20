<?php
$conexion = new mysqli("localhost", "root", "", "asistencia");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $uid = $_POST['uid'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $apikey = $_POST['apikey'];

    $stmt = $conexion->prepare("UPDATE usuarios SET uid=?, nombre=?, telefono=?, apikey=? WHERE id=?");
    $stmt->bind_param("ssssi", $uid, $nombre, $telefono, $apikey, $id);

    if ($stmt->execute()) {
        header("Location: ver_usuarios.php");
        exit;
    } else {
        echo "Error al actualizar usuario.";
    }
} else {
    $id = $_GET['id'];
    $resultado = $conexion->query("SELECT * FROM usuarios WHERE id=$id");
    $usuario = $resultado->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
</head>
<body>
    <h1>Editar Usuario</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
        <label>UID:</label><br>
        <input type="text" name="uid" value="<?= $usuario['uid'] ?>" required><br><br>
        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?= $usuario['nombre'] ?>" required><br><br>
        <label>Teléfono:</label><br>
        <input type="text" name="telefono" value="<?= $usuario['telefono'] ?>" required><br><br>
        <label>API Key:</label><br>
        <input type="text" name="apikey" value="<?= $usuario['apikey'] ?>" required><br><br>
        <button type="submit">Actualizar</button>
    </form>
    <br>
    <a href="ver_usuarios.php">← Volver</a>
</body>
</html>
