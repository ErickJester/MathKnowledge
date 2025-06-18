<?php
// Pages/crear_recurso.php
session_start();

// 1) Control de acceso: sólo usuarios logueados
if (empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../includes/conexion.php';

// 2) Si viene un POST, guardamos el recurso
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $unit_id    = intval($_POST['unit_id']);
    $title      = $conn->real_escape_string($_POST['title']);
    $file_path  = $conn->real_escape_string($_POST['file_path']);
    $type       = $conn->real_escape_string($_POST['type']);
    $created_by = $_SESSION['usuario_id'];

    $sql = "
      INSERT INTO Resources (unit_id, title, file_path, type, created_by)
      VALUES (?, ?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssi", $unit_id, $title, $file_path, $type, $created_by);
    if ($stmt->execute()) {
        $msg = "Recurso creado con éxito (ID: " . $stmt->insert_id . ")";
    } else {
        $msg = "Error al crear recurso: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Recurso | Maths Knowledge</title>
  <link rel="stylesheet" href="../Styles/in.css">
  <link rel="stylesheet" href="../Styles/M.css">
</head>
<body>
  <div class="content" style="max-width:600px; margin:2rem auto;">
    <h1>Crear Nuevo Recurso</h1>
    <?php if (!empty($msg)): ?>
      <div style="margin-bottom:1rem; color: green;"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <form method="POST">
      <label>Unidad (ID)</label><br>
      <input name="unit_id" type="number" required style="width:100%; padding:8px; margin-bottom:1rem;"><br>
      <label>Título</label><br>
      <input name="title" type="text" required style="width:100%; padding:8px; margin-bottom:1rem;"><br>
      <label>Ruta de archivo / URL</label><br>
      <input name="file_path" type="text" style="width:100%; padding:8px; margin-bottom:1rem;"><br>
      <label>Tipo</label><br>
      <select name="type" required style="width:100%; padding:8px; margin-bottom:1rem;">
        <option value="pdf">PDF</option>
        <option value="video">Video</option>
        <option value="exercise">Ejercicio</option>
        <option value="quiz">Cuestionario</option>
      </select><br>
      <button type="submit" style="padding:0.75rem 1.5rem;">Guardar Recurso</button>
    </form>
  </div>
</body>
</html>
