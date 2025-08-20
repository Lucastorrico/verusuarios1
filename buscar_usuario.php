<?php
$uid = $_GET['uid'];

$conexion = new mysqli("localhost", "root", "", "asistencia");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "SELECT nombre, telefono, apikey FROM usuarios WHERE uid = '$uid'";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    echo json_encode([
        "nombre" => $fila['nombre'],
        "telefono" => $fila['telefono'],
        "apikey" => $fila['apikey']
    ]);
} else {
    echo json_encode([
        "error" => "UID no registrado"
    ]);
}

$conexion->close();
?>


