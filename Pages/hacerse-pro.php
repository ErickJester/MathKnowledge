<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hacerse PRO | Maths Knowledge</title>

  <link rel="stylesheet" href="../Styles/in.css">
  <link rel="stylesheet" href="../Styles/M.css">
  <link rel="stylesheet" href="../Styles/hacerse-pro.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
      <li><a href="ver-pdfs.php"><span>📖</span><span class="label">Ver PDFs</span></a></li>
      <li><a href="login.php"><span>🔑</span><span class="label">Inicio de sesión</span></a></li>
      <li><a href="subir-pdf.php"><span>📤</span><span class="label">Subir PDF Nuevo</span></a></li>
      <li><a href="registro.php"><span>⚙️</span><span class="label">Registro</span></a></li>
      <li><a href="logout.php"><span>🚪</span><span class="label">Cerrar sesión</span></a></li>
    </ul>
  </nav>

  <div class="content">
    <header>
      <h1>Hazte Usuario PRO</h1>
      <p>Obtén acceso completo a todos los beneficios por solo $50 MXN.</p>
    </header>

    <div class="pro-container">
      <!-- Formulario de pago -->
      <div class="payment-form">
        <h2>Método de pago</h2>

        <label>Información de la tarjeta</label>
        <input type="text" placeholder="1234 1234 1234 1234" maxlength="19">
        <div style="display: flex; gap: 1rem;">
          <input type="text" placeholder="MM/AA" maxlength="5">
          <input type="text" placeholder="CVC" maxlength="4">
        </div>
        <div class="card-icons">
          <img src="https://img.icons8.com/color/48/visa.png" alt="Visa">
          <img src="https://img.icons8.com/color/48/mastercard.png" alt="MasterCard">
          <img src="https://img.icons8.com/color/48/amex.png" alt="AmEx">
          <img src="https://img.icons8.com/color/48/discover.png" alt="Discover">
        </div>

        <label>Nombre del titular de tarjeta</label>
        <input type="text" placeholder="Nombre completo">

        <label>Dirección de facturación</label>
        <select>
          <option>México</option>
          <option>Otro país</option>
        </select>
        <input type="text" placeholder="Ingresar dirección">

        <button class="register-button">Registrar</button>
      </div>

      <!-- Info de usuario PRO -->
        <div class="pro-info">
          <div class="pro-content">
            <h2>Usuario PRO</h2>
            <ul>
              <li>✓ Exámenes virtuales con retroalimentación de un docente</li>
              <li>✓ Registro de tu progreso</li>
              <li>✓ Foro virtual de preguntas y respuestas</li>
            </ul>
            <h2 style="margin-top: 1rem; font-size: 2rem;">$50 MXN</h2>
          </div>
        </div>


  <script>
    document.getElementById('sidebar-toggle').addEventListener('click', () => {
      document.documentElement.classList.toggle('sidebar-collapsed');
    });
  </script>
</body>
</html>
