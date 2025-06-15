<?php
session_start();
include '../includes/conexion.php'; // <-- ESTA LÍNEA ES FUNDAMENTAL

if (!isset($_SESSION['usuario_id'])) {
    echo "<p>No has iniciado sesión.</p>";
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actual = $_POST['contrasena_actual'] ?? '';
    $nueva = $_POST['nueva_contrasena'] ?? '';
    $confirmar = $_POST['confirmar_contrasena'] ?? '';

    if ($nueva !== $confirmar) {
        echo "<p>La nueva contraseña y la confirmación no coinciden.</p>";
        exit;
    }

    // Verifica contraseña actual
    $stmt = $conn->prepare("SELECT contrasena FROM Usuario WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || $actual !== $user['contrasena']) {
        echo "<p>La contraseña actual es incorrecta.</p>";
        exit;
    }

    // Actualiza a nueva contraseña
    $stmt_update = $conn->prepare("UPDATE Usuario SET contrasena = ? WHERE id_usuario = ?");
    $stmt_update->bind_param("si", $nueva, $id_usuario);
    $stmt_update->execute();

    echo "<p>Contraseña actualizada con éxito.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cambiar Contraseña</title>
  <link rel="stylesheet" href="../Styles/vista_datos.css">

</head>
<body>
  <h1>Cambiar Contraseña</h1>
  <form method="POST">
    <label>Contraseña Actual:</label>
    <div class="campo-password">
      <input type="password" name="contrasena_actual" id="actual" required>
      <span class="toggle" onclick="verPassword('actual')"></span>
    </div>

    <label>Nueva Contraseña:</label>
    <div class="campo-password">
      <input type="password" name="nueva_contrasena" id="nueva" required>
      <span class="toggle" onclick="verPassword('nueva')"></span>
    </div>

    <label>Confirmar Contraseña:</label>
    <div class="campo-password">
      <input type="password" name="confirmar_contrasena" id="confirmar" required>
      <span class="toggle" onclick="verPassword('confirmar')"></span>
    </div>

    <br><button type="submit">Guardar Cambios</button>
  </form>

  <script>
    function verPassword(id) {
      const campo = document.getElementById(id);
      campo.type = campo.type === 'password' ? 'text' : 'password';
    }
  </script>
</body>
</html>