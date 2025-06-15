<?php
include '../../includes/conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<p>ID de usuario no proporcionado.</p>";
    exit;
}

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT * FROM Usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$usuario = $result->fetch_assoc()) {
    echo "<p>Usuario no encontrado.</p>";
    exit;
}

// Obtener dirección del usuario
$stmt_dir = $conn->prepare("SELECT * FROM Direccion WHERE id_usuario = ?");
$stmt_dir->bind_param("i", $id);
$stmt_dir->execute();
$res_dir = $stmt_dir->get_result();
$direccion = $res_dir->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Usuario</title>
    <link rel="stylesheet" href="../../Styles/vista_datos.css">
</head>
<body>
    <h1>Detalle del Usuario</h1>
    <ul>
        <li><strong>Código:</strong> <?= htmlspecialchars($usuario['code_user']) ?></li>
        <li><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre']) ?> <?= htmlspecialchars($usuario['apellidoP']) ?> <?= htmlspecialchars($usuario['apellidoM']) ?></li>
        <li><strong>Tipo (ID):</strong> <?= htmlspecialchars($usuario['tipo_usuario']) ?></li>
        <li><strong>Correo:</strong> <?= htmlspecialchars($usuario['correo']) ?></li>
        <li><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono']) ?></li>
        <li><strong>Fecha de nacimiento:</strong> <?= htmlspecialchars($usuario['fecha_nacimiento']) ?></li>
        <li><strong>Fecha de registro:</strong> <?= htmlspecialchars($usuario['fecha_registro']) ?></li>
    </ul>
    <?php if ($direccion): ?>
    <h2>Dirección</h2>
    <ul>
        <li><strong>Calle:</strong> <?= htmlspecialchars($direccion['calle']) ?></li>
        <li><strong>Colonia:</strong> <?= htmlspecialchars($direccion['colonia']) ?></li>
        <li><strong>Código Postal:</strong> <?= htmlspecialchars($direccion['codigo_postal']) ?></li>
        <li><strong>País:</strong> <?= htmlspecialchars($direccion['pais']) ?></li>
    </ul>
    <?php else: ?>
    <p>Dirección no registrada.</p>
    <?php endif; ?>

    <form action="editar_datos.php" method="GET">
        <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">
        <button type="submit">Editar</button>
    </form>
</body>
</html>