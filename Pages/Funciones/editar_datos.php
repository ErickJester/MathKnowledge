<?php
include '../../includes/conexion.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<p>ID de usuario no proporcionado.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $telefono = $_POST['telefono'] ?? '';
    $calle = $_POST['calle'] ?? '';
    $colonia = $_POST['colonia'] ?? '';
    $codigo_postal = $_POST['codigo_postal'] ?? '';
    $pais = $_POST['pais'] ?? '';

    $stmt_user = $conn->prepare("UPDATE Usuario SET telefono = ? WHERE id_usuario = ?");
    $stmt_user->bind_param("si", $telefono, $id);
    $stmt_user->execute();

    $check = $conn->prepare("SELECT id_direccion FROM Direccion WHERE id_usuario = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $res = $check->get_result();

    if ($res->fetch_assoc()) {
        $stmt_dir = $conn->prepare("UPDATE Direccion SET calle = ?, colonia = ?, codigo_postal = ?, pais = ? WHERE id_usuario = ?");
        $stmt_dir->bind_param("ssssi", $calle, $colonia, $codigo_postal, $pais, $id);
    } else {
        $stmt_dir = $conn->prepare("INSERT INTO Direccion (id_usuario, calle, colonia, codigo_postal, pais) VALUES (?, ?, ?, ?, ?)");
        $stmt_dir->bind_param("issss", $id, $calle, $colonia, $codigo_postal, $pais);
    }
    $stmt_dir->execute();

    echo "<p>Datos actualizados con éxito.</p>";
    echo "<a href='detalle_usuario.php?id=$id'>Volver al detalle</a>";
    exit;
}

$stmt = $conn->prepare("SELECT telefono FROM Usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

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
  <title>Editar Datos</title>
  <link rel="stylesheet" href="../../Styles/vista_datos.css">
</head>
<body>
  <h1>Editar Teléfono y Dirección</h1>
  <form method="POST">
    <label>Teléfono:</label>
    <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" required><br><br>

    <label>Calle:</label>
    <input type="text" name="calle" value="<?= htmlspecialchars($direccion['calle'] ?? '') ?>" required><br>
    <label>Colonia:</label>
    <input type="text" name="colonia" value="<?= htmlspecialchars($direccion['colonia'] ?? '') ?>" required><br>
    <label>Código Postal:</label>
    <input type="text" name="codigo_postal" value="<?= htmlspecialchars($direccion['codigo_postal'] ?? '') ?>" required><br>
    <label>País:</label>
    <input type="text" name="pais" value="<?= htmlspecialchars($direccion['pais'] ?? '') ?>" required><br><br>

    <button type="submit">Guardar Cambios</button>
  </form>
</body>
</html>