<?php
// Pages/admin.php
session_start();
if (empty($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 1) {
    header('Location: login.php'); exit;
}
require_once __DIR__ . '/../includes/conexion.php';

// 1) Nuevas estadísticas para Maths Knowledge
$stats = [
  'subjects'   => 0,
  'units'      => 0,
  'resources'  => 0,
  'comments'   => 0,
  'ratings'    => 0,
  'quizzes'    => 0
];
if ($conn) {
  $r = $conn->query("SELECT COUNT(*) AS c FROM Subjects");
  if ($r) $stats['subjects']  = (int)$r->fetch_assoc()['c'];

  $r = $conn->query("SELECT COUNT(*) AS c FROM Units");
  if ($r) $stats['units']     = (int)$r->fetch_assoc()['c'];

  $r = $conn->query("SELECT COUNT(*) AS c FROM Resources");
  if ($r) $stats['resources'] = (int)$r->fetch_assoc()['c'];

  $r = $conn->query("SELECT COUNT(*) AS c FROM Comments");
  if ($r) $stats['comments']  = (int)$r->fetch_assoc()['c'];

  $r = $conn->query("SELECT COUNT(*) AS c FROM Ratings");
  if ($r) $stats['ratings']   = (int)$r->fetch_assoc()['c'];

  $r = $conn->query("SELECT COUNT(*) AS c FROM Quizzes");
  if ($r) $stats['quizzes']   = (int)$r->fetch_assoc()['c'];
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Admin | Maths Knowledge</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../Styles/in.css">
  <link rel="stylesheet" href="../Styles/M.css">
  <style>
    .stats { display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:2rem; }
    .stats .card { flex:1; min-width:150px; }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <nav class="sidebar">
    <h2>🔢 Maths Knowledge</h2>
    <ul>
      <li><a href="admin.php">👤 Hola, <?= htmlspecialchars($_SESSION['nombre']) ?></a></li>
      <li><a href="subjects.php">📚 Gestionar Asignaturas</a></li>
      <li><a href="units.php">🗂️ Gestionar Unidades</a></li>
      <li><a href="resources.php">📄 Gestionar Recursos</a></li>
      <li><a href="comments.php">💬 Gestionar Comentarios</a></li>
      <li><a href="ratings.php">⭐ Gestionar Valoraciones</a></li>
      <li><a href="quizzes.php">📝 Gestionar Cuestionarios</a></li>
      <li><a href="reportes.php">📊 Reportes</a></li>
      <li><a href="logout.php">🚪 Cerrar Sesión</a></li>
      <li><a href="../indexMath.php">🏠 Página Principal</a></li>
    </ul>
  </nav>

  <!-- Contenido -->
  <div class="content">
    <header>
      <h1>Panel del Administrador</h1>
      <p>Administra tu plataforma de recursos de matemáticas.</p>
    </header>

    <div class="stats">
      <div class="card">
        <h3>Asignaturas</h3>
        <p><strong><?= $stats['subjects'] ?></strong></p>
      </div>
      <div class="card">
        <h3>Unidades</h3>
        <p><strong><?= $stats['units'] ?></strong></p>
      </div>
      <div class="card">
        <h3>Recursos</h3>
        <p><strong><?= $stats['resources'] ?></strong></p>
      </div>
      <div class="card">
        <h3>Comentarios</h3>
        <p><strong><?= $stats['comments'] ?></strong></p>
      </div>
      <div class="card">
        <h3>Valoraciones</h3>
        <p><strong><?= $stats['ratings'] ?></strong></p>
      </div>
      <div class="card">
        <h3>Cuestionarios</h3>
        <p><strong><?= $stats['quizzes'] ?></strong></p>
      </div>
    </div>
  </div>
</body>
</html>
