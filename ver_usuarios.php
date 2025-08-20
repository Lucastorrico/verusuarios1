<?php
$conexion = new mysqli("localhost", "root", "", "asistencia");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$busqueda = isset($_GET['busqueda']) ? $conexion->real_escape_string($_GET['busqueda']) : "";

$sql = "SELECT * FROM usuarios";
if (!empty($busqueda)) {
    $sql .= " WHERE uid LIKE '%$busqueda%' OR nombre LIKE '%$busqueda%' OR telefono LIKE '%$busqueda%' OR apikey LIKE '%$busqueda%'";
}
$sql .= " ORDER BY id ASC";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            text-align: center;
           
        }
        .top-bar {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 6px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            padding: 8px 16px;
            margin: 5px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .btn.edit {
            background-color: #2196F3;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            margin: auto;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Usuarios Registrados</h1>

    <div class="top-bar">
        <form method="GET" style="display: inline-block;">
            <input type="text" name="busqueda" placeholder="Buscar por nombre, UID, teléfono o API key" value="<?= htmlspecialchars($busqueda) ?>">
            <button class="btn" type="submit">🔍 Buscar</button>
        </form>
        <a href="crear_usuario.php" class="btn">➕ Crear Usuario</a>
    </div>

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
                <td><?= $fila['id'] ?></td>
                <td><?= $fila['uid'] ?></td>
                <td><?= $fila['nombre'] ?></td>
                <td><?= $fila['telefono'] ?></td>
                <td><?= $fila['apikey'] ?></td>
                <td>
                    <a href="editar_usuario.php?id=<?= $fila['id'] ?>" class="btn edit">✏️ Editar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>



