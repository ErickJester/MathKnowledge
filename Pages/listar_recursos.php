<?php
// Pages/listar_recursos.php
session_start();
require_once __DIR__ . '/../includes/conexion.php';

$unit_id = intval($_GET['unit_id'] ?? 1);
$sql = "SELECT resource_id, title, type, file_path, created_at FROM Resources WHERE unit_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $unit_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Recursos de Unidad <?= $unit_id ?></title>
  <link rel="stylesheet" href="../Styles/in.css">
  <link rel="stylesheet" href="../Styles/M.css">
</head>
<body>
  <div class="content" style="max-width:800px; margin:2rem auto;">
    <h1>Recursos de la Unidad <?= $unit_id ?></h1>
    <ul style="list-style:none; padding:0;">
      <?php while ($row = $result->fetch_assoc()): ?>
      <li style="background:#fff; margin-bottom:1rem; padding:1rem; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
        <strong>[<?= htmlspecialchars($row['type']) ?>]</strong>
        <?= htmlspecialchars($row['title']) ?><br>
        <?php if ($row['type']==='video'): ?>
          <a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank">Ver video</a>
        <?php else: ?>
          <a href="../<?= htmlspecialchars($row['file_path']) ?>" download>Descargar</a>
        <?php endif; ?>
        <span style="float:right; color:#666;"><?= $row['created_at'] ?></span>
      </li>
      <?php endwhile; ?>
    </ul>
  </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
