<?php
// Pages/subir_libro.php
session_start();
// if (empty($_SESSION['usuario_id'])) {
//     header('Location: login.php');
//     exit;
// }
$mensaje_exito = isset($_GET['ok']) ? '¡El libro se ha subido correctamente!' : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Subir Libro | Maths Knowledge</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../Styles/in.css">
  <link rel="stylesheet" href="../Styles/M.css">
</head>
<body>
  <!-- Botón hamburguesa -->
  <button id="sidebar-toggle" aria-label="Toggle sidebar">
    <div class="hamburger"></div>
  </button>

  <!-- Sidebar -->
  <nav class="sidebar">
    <h2>🔢 Maths Knowledge</h2>
    <ul>
      <li><a href="../indexMath.php"><span>🏠</span><span class="label">Página Principal</span></a></li>
      <li class="active"><a href="subir_libro.php"><span>📤</span><span class="label">Subir Libro</span></a></li>
      <li><a href="analisis_vectorial_libros.php"><span>📖</span><span class="label">Libros A. Vectorial</span></a></li>
      <li><a href="libros_discretas.php"><span>📖</span><span class="label">Libros Discretas</span></a></li>
      <li><a href="libros_calculo.php"><span>📖</span><span class="label">Libros Cálculo</span></a></li>
      <li><a href="logout.php"><span>🚪</span><span class="label">Cerrar sesión</span></a></li>
    </ul>
  </nav>

  <div class="content">
    <!-- Encabezado -->
    <header>
      <h1>Subir Nuevo Libro</h1>
      <p>Rellena el nombre, elige materia y selecciona un PDF.</p>
    </header>

    <div class="container">
      <main style="flex:1 0 100%">
        <div class="card">
          <!-- Mensaje de éxito -->
          <?php if ($mensaje_exito): ?>
          <div class="alert-success" style="
               background: #d4edda;
               color: #155724;
               border: 1px solid #c3e6cb;
               padding: 1rem;
               border-radius: 4px;
               margin-bottom: 1.5rem;
               text-align: center;
             ">
            <?= htmlspecialchars($mensaje_exito) ?>
          </div>
          <?php endif; ?>

          <form action="Funciones/procesar_libro.php" method="POST" enctype="multipart/form-data">
            <div class="form-group" style="margin-bottom:1rem;">
              <label for="nombre_libro" style="display:block; font-weight:500; margin-bottom:0.5rem;">
                Nombre del Libro:
              </label>
              <input
                type="text"
                id="nombre_libro"
                name="nombre_libro"
                required
                style="width:100%; padding:0.75rem; border:1px solid #ccc; border-radius:4px;"
              >
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
              <label for="materia" style="display:block; font-weight:500; margin-bottom:0.5rem;">
                Materia:
              </label>
              <select
                id="materia"
                name="materia"
                required
                style="width:100%; padding:0.75rem; border:1px solid #ccc; border-radius:4px;"
              >
                <option value="">-- Selecciona materia --</option>
                <option value="Analisis Vectorial">Análisis Vectorial</option>
                <option value="Matematicas Discretas">Matemáticas Discretas</option>
                <option value="Calculo">Cálculo</option>
              </select>
            </div>
            <div class="form-group" style="margin-bottom:1.5rem;">
              <label for="pdf_file" style="display:block; font-weight:500; margin-bottom:0.5rem;">
                Archivo PDF:
              </label>
              <input
                type="file"
                id="pdf_file"
                name="pdf_file"
                accept="application/pdf"
                required
                style="width:100%;"
              >
            </div>
            <button
              type="submit"
              class="btn-pro"
              style="display:inline-block; padding:0.75rem 1.5rem; border:none; background:#1a75bb; color:#fff; border-radius:8px; font-size:1rem; cursor:pointer;"
            >
              Subir Libro
            </button>
          </form>
        </div>
      </main>
    </div>
  </div>

  <script>
    document.getElementById('sidebar-toggle')
      .addEventListener('click', () =>
        document.documentElement.classList.toggle('sidebar-collapsed')
      );
  </script>
</body>
</html>
