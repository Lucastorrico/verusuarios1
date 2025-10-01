<?php
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_GET['uid']) || empty($_GET['uid'])) {
    echo json_encode(array("error" => "UID requerido"));
    exit;
}

$uid = sanitizeInput($_GET['uid']);

$db = new Database();
$conexion = $db->getConnection();

$stmt = $conexion->prepare("SELECT nombre, telefono, apikey FROM usuarios WHERE uid = ?");
$stmt->bind_param("s", $uid);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    echo json_encode(array(
        "nombre" => $fila['nombre'],
        "telefono" => $fila['telefono'],
        "apikey" => $fila['apikey']
    ));
} else {
    echo json_encode(array(
        "error" => "UID no registrado"
    ));
}

$stmt->close();
$db->closeConnection();
?>


