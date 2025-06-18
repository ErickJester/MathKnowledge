<?php
// Pages/lector.php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

session_start();

// 1) Control de acceso: solo lector (tipo_usuario = 3)
if (empty($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 3) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../includes/conexion.php';

// 2) Datos del lector
$id_lector     = $_SESSION['usuario_id'];
$nombre_lector = $_SESSION['nombre'];

// 3) Construir menú FEPI para lector
$menuItems = [
    ['url'=>'subjects.php',       'icon'=>'📚','label'=>'Asignaturas'],
    ['url'=>'units.php',          'icon'=>'🗂️','label'=>'Unidades'],
    ['url'=>'resources.php',      'icon'=>'💾','label'=>'Recursos'],
    ['url'=>'comments.php',       'icon'=>'💬','label'=>'Mis Comentarios'],
    ['url'=>'ratings.php',        'icon'=>'⭐','label'=>'Mis Valoraciones'],
    ['url'=>'progreso.php',       'icon'=>'📊','label'=>'Mi Progreso'],
    ['url'=>'logout.php',         'icon'=>'🚪','label'=>'Cerrar Sesión'],
];

// 4) Consultas de estadísticas FEPI para este lector
$stats = ['completed_units'=>0,'quizzes_taken'=>0,'comments'=>0,'ratings'=>0];
if (!empty($conn)) {
    // Unidades completadas
    $stmt = $conn->prepare("
      SELECT COUNT(*) AS c
      FROM StudyProgress
      WHERE user_id = ? AND completed = TRUE
    ");
    $stmt->bind_param("i", $id_lector);
    $stmt->execute();
    $stats['completed_units'] = $stmt->get_result()->fetch_assoc()['c'];
    $stmt->close();

    // Cuestionarios tomados
    $stmt = $conn->prepare("
      SELECT COUNT(*) AS c
      FROM QuizResponses
      WHERE user_id = ?
    ");
    $stmt->bind_param("i", $id_lector);
    $stmt->execute();
    $stats['quizzes_taken'] = $stmt->get_result()->fetch_assoc()['c'];
    $stmt->close();

    // Comentarios hechos
    $stmt = $conn->prepare("
      SELECT COUNT(*) AS c
      FROM Comments
      WHERE user_id = ?
    ");
    $stmt->bind_param("i", $id_lector);
    $stmt->execute();
    $stats['comments'] = $stmt->get_result()->fetch_assoc()['c'];
    $stmt->close();

    // Valoraciones dadas
    $stmt = $conn->prepare("
      SELECT COUNT(*) AS c
      FROM Ratings
      WHERE user_id = ?
    ");
    $stmt->bind_param("i", $id_lector);
    $stmt->execute();
    $stats['ratings'] = $stmt->get_result()->fetch_assoc()['c'];
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Bienvenido Lector | Maths Knowledge</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../Styles/in.css">
  <link rel="stylesheet" href="../Styles/M.css">
</head>
<body>
  <!-- Sidebar -->
  <nav class="sidebar">
    <h2>🔢 Maths Knowledge</h2>
    <ul>
      <li>
        <a href="lector.php" class="perfil-link">
          👤 Hola, <?= htmlspecialchars($nombre_lector) ?>
        </a>
      </li>
      <li>
        <a href="../indexMath.php"><span>🏠</span><span class="label">Página Principal</span></a>
      </li>
      <?php foreach ($menuItems as $item): ?>
      <li>
        <a href="<?= htmlspecialchars($item['url']) ?>">
          <?= $item['icon'] ?> <span class="label"><?= htmlspecialchars($item['label']) ?></span>
        </a>
      </li>
      <?php endforeach ?>
    </ul>
  </nav>

  <!-- Contenido principal -->
  <div class="content">
    <header>
      <h1>Panel del Usuario</h1>
      <p>Consulta tus unidades, evalúa tu progreso y revisa tu actividad.</p>
    </header>

    <!-- Estadísticas personales -->
    <div class="stats">
      <div class="card">
        <h3>Unidades completadas</h3>
        <p><strong><?= $stats['completed_units'] ?></strong></p>
      </div>
      <div class="card">
        <h3>Cuestionarios tomados</h3>
        <p><strong><?= $stats['quizzes_taken'] ?></strong></p>
      </div>
      <div class="card">
        <h3>Comentarios</h3>
        <p><strong><?= $stats['comments'] ?></strong></p>
      </div>
      <div class="card">
        <h3>Valoraciones</h3>
        <p><strong><?= $stats['ratings'] ?></strong></p>
      </div>
    </div>

    <!-- Actividad reciente -->
    <div class="activity">
      <h2>Actividad reciente</h2>
      <div class="item">
        <div>
          <strong>Unidad completada</strong><br>
          Análisis Vectorial – Unidad 1
        </div>
        <span>Hace 2 días</span>
      </div>
      <div class="item">
        <div>
          <strong>Cuestionario tomado</strong><br>
          Quiz de Matemáticas Discretas
        </div>
        <span>Hace 4 días</span>
      </div>
      <div class="item">
        <div>
          <strong>Nuevo comentario</strong><br>
          Recursos de Cálculo
        </div>
        <span>Hace 5 días</span>
      </div>
    </div>
  </div>

  <script>
    // Toggle sidebar
    document.getElementById('sidebar-toggle')?.addEventListener('click', () =>
      document.documentElement.classList.toggle('sidebar-collapsed')
    );
  </script>
</body>
</html>
