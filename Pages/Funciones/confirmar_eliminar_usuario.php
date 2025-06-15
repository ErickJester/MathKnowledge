<?php
session_start();
include('../../includes/conexion.php');

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("ID de usuario inválido.");
}

// Obtener usuario
$stmt = $conn->prepare("SELECT nombre, apellidoP, code_user FROM Usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if (!$usuario = $result->fetch_assoc()) {
    die("Usuario no encontrado.");
}
$nombreCompleto = "{$usuario['nombre']} {$usuario['apellidoP']}";
$codigo = $usuario['code_user'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar eliminación</title>
    <link rel="stylesheet" href="../../Styles/confirm_style.css">
</head>
<body>
    <h1>¿Eliminar usuario?</h1>
    <p>¿Estás seguro de que deseas eliminar al usuario <strong><?= htmlspecialchars($nombreCompleto) ?></strong> con código <strong><?= htmlspecialchars($codigo) ?></strong>?</p>

    <form method="post" action="eliminar_usuario.php" style="display:inline;">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit">Confirmar eliminación</button>
    </form>

    <button onclick="history.back()">Cancelar</button>
</body>
</html>
