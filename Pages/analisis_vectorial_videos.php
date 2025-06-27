<?php
// Pages/analisis_vectorial_videos.php
session_start();
// if (empty($_SESSION['usuario_id'])) {
//     header('Location: login.php'); exit;
// }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Videos de Análisis Vectorial | Maths Knowledge</title>

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
      
      <li><a href="login.php"><span>🔑</span><span class="label">Inicio de sesión</span></a></li>
      
      
      <li><a href="registro.php"><span>⚙️</span><span class="label">Registro</span></a></li>
      <li><a href="logout.php"><span>🚪</span><span class="label">Cerrar sesión</span></a></li>
    </ul>
  </nav>

  <div class="content">
    <!-- Encabezado de bienvenida -->
    <header>
      <h1>Videos de Análisis Vectorial</h1>
      <p>Explora y busca videos sobre los conceptos de vectores.</p>
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
          <a href="videos_calculo.php">Cálculo ▾</a>
          <ul class="dropdown-content">
            <li><a href="libros_calculo.php">Libros</a></li>
            <li><a href="videos_calculo.php">Vídeos</a></li>
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
      <!-- Main (65%) con buscador y videos -->
      <main>
        <div class="card">
          <h2>Buscar Videos</h2>
          <input type="text" id="video-search" placeholder="Escribe un título..." style="width:100%; padding:8px; font-size:16px;" />
        </div>
        <div class="video-grid" style="display:flex; flex-wrap:wrap; gap:1rem; margin-top:1rem;">
          <div class="video-item" style="flex:1 1 260px;">
            <h4>Qué es un vector y sus características</h4>
            <iframe width="100%" height="200" src="https://www.youtube.com/embed/IrTeyyzerjI" frameborder="0" allowfullscreen></iframe>
          </div>
          <div class="video-item" style="flex:1 1 260px;">
            <h4>Representación gráfica de vectores</h4>
            <iframe width="100%" height="200" src="https://www.youtube.com/embed/eJyqrR6eBTE" frameborder="0" allowfullscreen></iframe>
          </div>
          <div class="video-item" style="flex:1 1 260px;">
            <h4>Suma y resta de vectores</h4>
            <iframe width="100%" height="200" src="https://www.youtube.com/embed/nQnxMF1Jwso" frameborder="0" allowfullscreen></iframe>
          </div>
          <div class="video-item" style="flex:1 1 260px;">
            <h4>Multiplicación de un vector por un escalar</h4>
            <iframe width="100%" height="200" src="https://www.youtube.com/embed/fjizt35knGs" frameborder="0" allowfullscreen></iframe>
          </div>
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

  <script>
    // Toggle sidebar
    document.getElementById('sidebar-toggle').addEventListener('click', () => {
      document.documentElement.classList.toggle('sidebar-collapsed');
    });
    // Filtrar videos por título
    document.getElementById('video-search').addEventListener('input', (e) => {
      const q = e.target.value.toLowerCase();
      document.querySelectorAll('.video-item').forEach(item => {
        item.style.display = item.querySelector('h4').textContent.toLowerCase().includes(q)
          ? 'block'
          : 'none';
      });
    });
  </script>
</body>
</html>
