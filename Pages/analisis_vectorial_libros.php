<?php
// Pages/analisis_vectorial_libros.php
session_start();
// if (empty($_SESSION['usuario_id'])) {
//     header('Location: login.php');
//     exit;
// }

// Ruta al directorio de uploads para Análisis Vectorial
$dir = __DIR__ . '/../uploads/Analisis vectorial/libros';

// Lee los archivos .pdf dentro del directorio
$files = [];
if (is_dir($dir)) {
    foreach (scandir($dir) as $f) {
        if (is_file("$dir/$f") && strtolower(pathinfo($f, PATHINFO_EXTENSION)) === 'pdf') {
            $files[] = $f;
        }
    }
    sort($files, SORT_NATURAL | SORT_FLAG_CASE);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Libros de Análisis Vectorial | Maths Knowledge</title>

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"
    rel="stylesheet"
  />

  <!-- Estilos del sidebar fijo -->
  <link rel="stylesheet" href="../Styles/in.css" />

  <!-- Estilos principales -->
  <link rel="stylesheet" href="../Styles/M.css" />
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
      <li><a href="../indexMath.php"><span>🏠</span><span class="label">Página Principal</span></a></li>
      <li><a href="analisis_vectorial_videos.php"><span>🎥</span><span class="label">Vídeos</span></a></li>
      <li class="active"><a href="analisis_vectorial_libros.php"><span>📖</span><span class="label">Libros</span></a></li>
      <li><a href="analisis_vectorial_ejercicios.php"><span>✏️</span><span class="label">Ejercicios</span></a></li>
      <li><a href="analisis_vectorial_examenes.php"><span>📝</span><span class="label">Exámenes</span></a></li>
      <li><a href="logout.php"><span>🚪</span><span class="label">Cerrar sesión</span></a></li>
    </ul>
  </nav>

  <div class="content">
    <!-- Encabezado de bienvenida -->
    <header>
      <h1>Libros de Análisis Vectorial</h1>
      <p>Explora y descarga los PDFs disponibles.</p>
    </header>

    <!-- Barra de materias (reparto igualitario) -->
    <div class="subject-nav">
      <ul class="subject-menu">
        <li class="dropdown">
          <a href="analisis_vectorial_videos.php">Análisis Vectorial ▾</a>
          <ul class="dropdown-content">
            <li><a href="analisis_vectorial_videos.php">Vídeos</a></li>
            <li><a href="analisis_vectorial_libros.php">Libros</a></li>
            <li><a href="analisis_vectorial_ejercicios.php">Ejercicios</a></li>
            <li><a href="analisis_vectorial_examenes.php">Exámenes</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="videos_discretas.php">Matemáticas Discretas ▾</a>
          <ul class="dropdown-content">
            <li><a href="libros_discretas.php">Libros</a></li>
            <li><a href="videos_discretas.php">Vídeos</a></li>
            <li><a href="ejercicios_discretas.php">Ejercicios</a></li>
            <li><a href="examenes_discretas.php">Exámenes</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="calculo_videos.php">Cálculo ▾</a>
          <ul class="dropdown-content">
            <li><a href="calculo_libros.php">Libros</a></li>
            <li><a href="calculo_videos.php">Vídeos</a></li>
            <li><a href="ejercicios_calculo.php">Ejercicios</a></li>
            <li><a href="examenes_calculo.php">Exámenes</a></li>
          </ul>
        </li>
        <li class="pro">
          <a href="hacerse-pro.php" class="btn-pro">Hacerse PRO</a>
        </li>
      </ul>
    </div>

    <div class="container">
      <!-- Main (65%) con buscador y grid de libros -->
      <main>
        <div class="card">
          <h2>Buscar Libros</h2>
          <input
            type="text"
            id="file-search"
            placeholder="Escribe un título..."
            style="width:100%; padding:8px; font-size:16px;"
          />
        </div>

        <div class="file-grid" style="display:flex; flex-wrap:wrap; gap:1rem; margin-top:1rem;">
          <?php if (empty($files)): ?>
            <div style="width:100%; text-align:center; padding:2rem; background:#fff; border-radius:8px;">
              No se encontraron libros.
            </div>
          <?php else: ?>
            <?php foreach ($files as $file): ?>
              <div
                class="file-item"
                style="flex:1 1 260px; background:#fff; padding:1rem; border-radius:8px; box-shadow:0 2px 4px rgba(0,0,0,0.1); display:flex; flex-direction:column; justify-content:space-between;"
              >
                <h4 style="margin-bottom:0.5rem;"><?= htmlspecialchars(pathinfo($file, PATHINFO_FILENAME)) ?></h4>
                <a
                  href="../uploads/Analisis%20vectorial/<?= rawurlencode($file) ?>"
                  download
                  style="text-decoration:none; color:#3498db; font-weight:500; align-self:flex-end;"
                >
                  Descargar
                </a>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </main>

      <!-- Aside (30%) con info adicional -->
      <aside>
        <div class="card">
          <h3>Información Educativa</h3>
          <p>Encuentra más recursos y tutoriales sobre Análisis Vectorial.</p>
        </div>
      </aside>
    </div>
  </div>

  <!-- Script de toggle y buscador -->
  <script>
    document.getElementById('sidebar-toggle')
      .addEventListener('click', () =>
        document.documentElement.classList.toggle('sidebar-collapsed')
      );
    document.getElementById('file-search')
      .addEventListener('input', e => {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('.file-item').forEach(el => {
          el.style.display = el.querySelector('h4').textContent.toLowerCase().includes(q)
            ? 'flex'
            : 'none';
        });
      });
  </script>
</body>
</html>
