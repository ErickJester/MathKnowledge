<?php
// index.php
session_start();
// Si el usuario ha iniciado sesión, tomamos su nombre, si no, 'Invitado'
$saludo = !empty($_SESSION['nombre'])
    ? htmlspecialchars($_SESSION['nombre'])
    : 'Invitado';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Maths Knowledge</title>

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"
    rel="stylesheet"
  />

  <!-- Estilos del sidebar fijo -->
  <link rel="stylesheet" href="Styles/in.css" />

  <!-- Estilos principales -->
  <link rel="stylesheet" href="Styles/M.css" />
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
      <!-- Saludo dinámico -->
      <li class="greeting">
        <a href="Pages/lector.php" class="perfil-link">
          <span>👤</span>
          <span class="label">Hola, <?= $saludo ?></span>
        </a>
      </li>

      <li class="active">
        <a href="index.php">
          <span>🏠</span>
          <span class="label">Página Principal</span>
        </a>
      </li>
      
     
      
      <li>
        <a href="Pages/registro.html">
          <span>⚙️</span>
          <span class="label">Registro</span>
        </a>
      </li>
      <li>
        <a href="Pages/login.php">
          <span>🔑</span>
          <span class="label">Inicio de sesión</span>
        </a>
      </li> 
      <li>
        <a href="Pages/logout.php">
          <span>🚪</span>
          <span class="label">Cerrar sesión</span>
        </a>
      </li>
    </ul>
  </nav>

  <div class="content">
    <!-- Encabezado de bienvenida -->
    <header>
      <h1>Bienvenido a Maths Knowledge</h1>
      <p>Explora nuestros recursos y mejora tus habilidades en matemáticas.</p>
    </header>

    <!-- Barra de materias y PRO -->
    <div class="subject-nav">
      <ul class="subject-menu">
        <li class="dropdown">
          <a href="#">Análisis Vectorial ▾</a>
          <ul class="dropdown-content">
            <li><a href="Pages/analisis_vectorial_libros.php">Libros</a></li>
            <li><a href="Pages/analisis_vectorial_videos.php">Vídeos</a></li>
            <li><a href="#">Ejercicios</a></li>
            <li><a href="#">Exámenes</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#">Matemáticas Discretas ▾</a>
          <ul class="dropdown-content">
            <li><a href="Pages/libros_discretas.php">Libros</a></li>
            <li><a href="Pages/videos_discretas.php">Vídeos</a></li>
            <li><a href="#">Ejercicios</a></li>
            <li><a href="#">Exámenes</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#">Cálculo ▾</a>
          <ul class="dropdown-content">
            <li><a href="Pages/calculo_libros.php">Libros</a></li>
            <li><a href="Pages/calculo_videos.php">Vídeos</a></li>
            <li><a href="#">Ejercicios</a></li>
            <li><a href="#">Exámenes</a></li>
          </ul>
        </li>
        <li class="pro">
          <a href="Pages/hacerse-pro.php" class="btn-pro">Hacerse PRO</a>
        </li>
      </ul>
    </div>

    <div class="container">
      <main>
        <div class="card">
          <h2>Próximos Eventos</h2>
          <p>Espacio para imágenes y descripción de eventos y actividades de Maths Knowledge.</p>
        </div>
        <div class="card">
          <h2>Novedades</h2>
          <p>Últimas incorporaciones de contenido y materiales destacados.</p>
        </div>
      </main>

      <aside>
        <div class="card">
          <h3>Información Educativa</h3>
          <p>Es un texto de ejemplo</p>
        </div>
      </aside>
    </div>
  </div>

  <!-- Script de colapso del sidebar -->
  <script>
    document
      .getElementById('sidebar-toggle')
      .addEventListener('click', () => {
        document.documentElement.classList.toggle('sidebar-collapsed');
      });
  </script>
</body>
</html>
