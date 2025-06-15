<?php
session_start();

// 1) Si no hay sesión válida o no es lector, redirige al login
if (empty($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 3) {
    header('Location: login.php');
    exit;
}

include("../includes/conexion.php");

// 2) Obtén datos del lector
$nombre_lector = $_SESSION['nombre'];
$id_lector     = $_SESSION['usuario_id'];

$prestamos = 0;
$multas    = 0;

if ($id_lector && isset($conn)) {
    // Préstamos activos
    $stmt1 = $conn->prepare("
        SELECT COUNT(*) AS total
        FROM Prestamo
        WHERE id_usuario = ? AND estado = 'activo'
    ");
    $stmt1->bind_param("i", $id_lector);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    if ($row1 = $result1->fetch_assoc()) {
        $prestamos = $row1['total'];
    }

    // Multas sin pagar
    $stmt2 = $conn->prepare("
        SELECT COALESCE(SUM(monto), 0) AS total
        FROM Multa
        WHERE id_usuario = ? AND pagada = FALSE
    ");
    $stmt2->bind_param("i", $id_lector);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    if ($row2 = $result2->fetch_assoc()) {
        $multas = $row2['total'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bienvenido Lector</title>
  <link rel="stylesheet" href="../Styles/inicio.css">
</head>
<body>
  <div class="sidebar">
    <h2>📚 Biblioteca</h2>
    <ul>
      <li><?= htmlspecialchars($nombre_lector) ?></li>
      <li><a href="libros_lect.php">📖 Libros</a></li>
      <li><a href="revistas_lect.php">📚 Revistas</a></li>
      <li><a href="digital_lect.php">💾 Digitales</a></li>
      <li><a href="vista_prest.php">📅 Mis Préstamos</a></li>
      <li><a href="vista_multas.php">💸 Mis Multas</a></li>
      <li><a href="cambio_contra.php">🔄 Cambiar contraseña</a></li>
      <li>
        <a href="Funciones/detalle_usuario.php?id=<?= $id_lector ?>">
          ℹ️ Ver info
        </a>
      </li>
      <li>
        <a href="logout.php">
          🔒 Cerrar Sesión
        </a>
      </li>
    </ul>
  </div>

  <div class="main">
    <div class="header">
      <h1>Bienvenido Lector</h1>
      <p>Consulta tus préstamos, califica materiales y revisa tu historial.</p>
    </div>

    <div class="stats">
      <div class="card">
        <h3>Préstamos activos</h3>
        <p><strong><?= $prestamos ?></strong></p>
      </div>
      <div class="card">
        <h3>Multas</h3>
        <p><strong>$<?= $multas ?></strong></p>
      </div>
    </div>

    <div class="activity">
      <h2>Actividad reciente</h2>
      <div class="item">
        <div>
          <strong>Préstamo solicitado</strong><br>
          Libro: 1984 de George Orwell
        </div>
        <span>Hace 1 día</span>
      </div>
      <div class="item">
        <div>
          <strong>Calificación enviada</strong><br>
          Revista: Ciencia UNAM
        </div>
        <span>Hace 3 días</span>
      </div>
    </div>
  </div>
</body>
</html>
