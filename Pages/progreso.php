<?php
// Pages/progreso.php
session_start();

// Si no hay sesión, redirige a login
if (empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../includes/conexion.php';
$user_id = $_SESSION['usuario_id'];

// 1) Calcular progreso por asignatura
$sql = "
  SELECT 
    s.subject_id,
    s.name,
    COUNT(u.unit_id)              AS total_units,
    COALESCE(SUM(sp.completed),0) AS completed_units
  FROM Subjects s
  LEFT JOIN Units u 
    ON u.subject_id = s.subject_id
  LEFT JOIN StudyProgress sp 
    ON sp.unit_id = u.unit_id 
   AND sp.user_id = ? 
   AND sp.completed = TRUE
  GROUP BY s.subject_id, s.name
  ORDER BY s.subject_id
";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();

$subjects = [];
while ($row = $res->fetch_assoc()) {
    $pct = $row['total_units']
         ? round($row['completed_units'] * 100 / $row['total_units'])
         : 0;
    $subjects[] = [
      'id'      => $row['subject_id'],
      'name'    => $row['name'],
      'percent' => $pct
    ];
}

$stmt->close();
$conn->close();

// textos e imágenes de ejemplo
$bubbles = [
  1 => '¡Vas bien!',
  2 => '¡Recta final!',
  3 => '¡Échale ganas!'
];
$images = [
  1 => 'assets/img/chibi1.png',
  2 => 'assets/img/chibi2.png',
  3 => 'assets/img/chibi3.png'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi progreso | Maths Knowledge</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../Styles/in.css">
  <link rel="stylesheet" href="../Styles/M.css">
  <style>
    /* Ajustes específicos para progreso */
    .content header {
      margin-bottom: 2rem; /* espacio suficiente debajo */
    }
    .progress-header {
      background: linear-gradient(135deg,#2c3e50,#3498db);
      color: #fff;
      padding: 1.5rem;
      border-radius: 8px 8px 0 0;
      text-align: center;
      font-size: 2rem;
      margin-bottom: 0;
    }
    .progress-list {
      max-width: 900px;
      margin: 0 auto 4rem;
    }
    .progress-item {
      display: flex;
      align-items: center;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      margin-bottom: 1.5rem;
      overflow: hidden;
    }
    .progress-chart {
      flex: 1;
      padding: 1.5rem;
    }
    .progress-chart h2 {
      margin-bottom: 0.75rem;
      color: #2c3e50;
      font-size: 1.5rem;
    }
    .bar-container {
      position: relative;
      height: 20px;
      background: #e0e0e0;
      border-radius: 10px;
      overflow: hidden;
    }
    .bar-fill {
      height: 100%;
      width: 0;
      background-image: linear-gradient(to right,#3498db,#2ecc71);
      transition: width 1s ease;
    }
    .bar-pointer {
      position: absolute;
      top: -26px;
      transform: translateX(-50%);
      background: #3498db;
      color: #fff;
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 0.9rem;
      white-space: nowrap;
      transition: left 1s ease;
    }
    .progress-extra {
      width: 120px;
      text-align: center;
      padding: 1rem;
    }
    .progress-extra img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      margin-bottom: 0.5rem;
    }
    .speech-bubble {
      display: inline-block;
      background: #ecf9ff;
      color: #3498db;
      padding: 0.5rem 1rem;
      border-radius: 16px;
      font-style: italic;
      font-size: 0.9rem;
      position: relative;
    }
    .speech-bubble::after {
      content: '';
      position: absolute;
      bottom: 100%;
      left: 20%;
      border-width: 10px;
      border-style: solid;
      border-color: transparent transparent #ecf9ff transparent;
    }
  </style>
</head>
<body>
  <!-- Botón hamburguesa -->
  <button id="sidebar-toggle" aria-label="Toggle sidebar">
    <div class="hamburger"></div>
  </button>

  <!-- Sidebar colapsable -->
  <nav class="sidebar">
    <h2>🔢 Maths Knowledge</h2>
    <ul>
      <li><a href="lector.php"><span>👤</span><span class="label">Hola, <?= htmlspecialchars($_SESSION['nombre']) ?></span></a></li>
      <li><a href="../indexMath.php"><span>🏠</span><span class="label">Página Principal</span></a></li>
      <li><a href="subjects.php"><span>📚</span><span class="label">Asignaturas</span></a></li>
      <li><a href="units.php"><span>🗂️</span><span class="label">Unidades</span></a></li>
      <li><a href="resources.php"><span>💾</span><span class="label">Recursos</span></a></li>
      <li><a href="comments.php"><span>💬</span><span class="label">Comentarios</span></a></li>
      <li><a href="ratings.php"><span>⭐</span><span class="label">Valoraciones</span></a></li>
      <li><a href="logout.php"><span>🚪</span><span class="label">Cerrar Sesión</span></a></li>
    </ul>
  </nav>

  <!-- Contenido -->
  <div class="content">
    <div class="progress-header">Mi perfil</div>

    <div class="progress-list">
      <?php foreach ($subjects as $sub): ?>
      <div class="progress-item">
        <div class="progress-chart">
          <h2><?= htmlspecialchars($sub['name']) ?></h2>
          <div class="bar-container" data-percent="<?= $sub['percent'] ?>">
            <div class="bar-fill" style="width: <?= $sub['percent'] ?>%;"></div>
            <div class="bar-pointer" style="left: <?= $sub['percent'] ?>%;"><?= $sub['percent'] ?>%</div>
          </div>
        </div>
        <div class="progress-extra">
          <img src="../<?= $images[$sub['id']] ?>" alt="">
          <div class="speech-bubble"><?= htmlspecialchars($bubbles[$sub['id']]) ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <script>
    // Toggle sidebar
    document.getElementById('sidebar-toggle')
      .addEventListener('click', () =>
        document.documentElement.classList.toggle('sidebar-collapsed')
      );
  </script>
</body>
</html>
