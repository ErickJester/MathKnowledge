<?php
// Pages/admin.php
session_start();
if (empty($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 1) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../includes/conexion.php';

// Estadísticas reales de baseMath
$stats = ['libros'=>0,'usuarios'=>0,'lectores'=>0,'multas'=>0];
if ($conn) {
    $r = $conn->query("SELECT COUNT(*) AS c FROM Libro");
    if ($r) $stats['libros'] = intval($r->fetch_assoc()['c']);

    $r = $conn->query("SELECT COUNT(*) AS c FROM Usuario");
    if ($r) $stats['usuarios'] = intval($r->fetch_assoc()['c']);

    $r = $conn->query("SELECT COUNT(*) AS c FROM Usuario WHERE tipo_usuario=3");
    if ($r) $stats['lectores'] = intval($r->fetch_assoc()['c']);

    $r = $conn->query("SELECT COUNT(*) AS c FROM Multa");
    if ($r) $stats['multas'] = intval($r->fetch_assoc()['c']);
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
    .stats {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      margin-bottom: 2rem;
    }
    .stats .card {
      flex: 1;
      min-width: 150px;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <nav class="sidebar">
    <h2>🔢 Maths Knowledge</h2>
    <ul>
      <!-- Saludo -->
      <li>
        <a href="admin.php">
          <span>👤</span>
          <span class="label">Hola, <?= htmlspecialchars($_SESSION['nombre']) ?></span>
        </a>
      </li>
      <!-- Registrar Usuario -->
      <li>
        <a href="usuario_register.php">
          <span>➕</span>
          <span class="label">Registrar Usuario</span>
        </a>
      </li>
      <!-- Gestionar Usuarios -->
      <li>
        <a href="usuarios_biblio.php">
          <span>👥</span>
          <span class="label">Gestionar Usuarios</span>
        </a>
      </li>
      <!-- PDFs -->
      <li>
        <a href="libros.php">
          <span>📘</span>
          <span class="label">PDFs</span>
        </a>
      </li>
      <li>
        <a href="subir_libro.php">
          <span>➕</span>
          <span class="label">Subir PDF</span>
        </a>
      </li>
      <!-- Vídeos -->
      <li>
        <a href="revistas.php">
          <span>📚</span>
          <span class="label">Vídeos</span>
        </a>
      </li>
      <li>
        <a href="revista_register.php">
          <span>➕</span>
          <span class="label">Subir Vídeo</span>
        </a>
      </li>
      <!-- Ejercicios -->
      <li>
        <a href="digital.php">
          <span>💾</span>
          <span class="label">Ejercicios</span>
        </a>
      </li>
      <li>
        <a href="digital_register.php">
          <span>➕</span>
          <span class="label">Subir Ejercicio</span>
        </a>
      </li>
      <!-- Tareas -->
      <li>
        <a href="prestamos_gestion.php">
          <span>📅</span>
          <span class="label">Tareas</span>
        </a>
      </li>
      <!-- Exámenes -->
      <li>
        <a href="gestion_multas.php">
          <span>💸</span>
          <span class="label">Exámenes</span>
        </a>
      </li>
      <!-- Reportes -->
      <li>
        <a href="reportes.php">
          <span>📄</span>
          <span class="label">Reportes</span>
        </a>
      </li>
      <!-- Cerrar sesión -->
      <li>
        <a href="logout.php">
          <span>🔒</span>
          <span class="label">Cerrar Sesión</span>
        </a>
      </li>
      <!-- Página principal -->
      <li>
        <a href="../indexMath.php">
          <span>🏠</span>
          <span class="label">Página Principal</span>
        </a>
      </li>
    </ul>
  </nav>

  <!-- Contenido principal -->
  <div class="content">
    <header>
      <h1>Panel del Administrador</h1>
      <p>Gestiona tu contenido y consulta estadísticas de la plataforma.</p>
    </header>

    <div class="stats">
      <div class="card">
        <h3>Total de Libros</h3>
        <p><strong><?= $stats['libros'] ?></strong></p>
      </div>
      <div class="card">
        <h3>Usuarios Registrados</h3>
        <p><strong><?= $stats['usuarios'] ?></strong></p>
      </div>
      <div class="card">
        <h3>Lectores</h3>
        <p><strong><?= $stats['lectores'] ?></strong></p>
      </div>
      <div class="card">
        <h3>Multas</h3>
        <p><strong><?= $stats['multas'] ?></strong></p>
      </div>
    </div>
  </div>
</body>
</html>
