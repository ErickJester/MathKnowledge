<?php
// Pages/detalles_usuario.php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/conexion.php';

// 1) Obtener y validar el ID del usuario
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die("<p>Parámetro inválido. <a href=\"listar_usuarios.php\">Volver a la lista</a></p>");
}

// 2) Preparar y ejecutar consulta con JOIN a Dirección
$stmt = $conn->prepare(
    "SELECT
       u.id_usuario,
       u.code_user,
       u.nombre,
       u.apellidoP,
       u.apellidoM,
       CASE u.tipo_usuario
         WHEN 1 THEN 'Administrador'
         WHEN 2 THEN 'Bibliotecario'
         WHEN 3 THEN 'Lector'
       END AS tipo,
       u.telefono,
       u.correo,
       u.fecha_nacimiento,
       u.fecha_registro,
       d.calle,
       d.colonia,
       d.codigo_postal,
       d.pais
     FROM Usuario u
     LEFT JOIN Direccion d ON u.id_usuario = d.id_usuario
     WHERE u.id_usuario = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("<p>Usuario no encontrado. <a href=\"listar_usuarios.php\">Volver a la lista</a></p>");
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle de Usuario <?= htmlspecialchars($user['code_user'], ENT_QUOTES) ?></title>
  <link rel="stylesheet" href="../Styles/main.css">
  <style>
    dl { width: 600px; }
    dt { font-weight: bold; margin-top: 1em; }
    dd { margin: 0 0 0.5em 1em; }
  </style>
</head>
<body>
  <h1>Detalle de Usuario</h1>
  <p><a href="listar_usuarios.php">« Volver a la lista de usuarios</a></p>

  <dl>
    <dt>ID de usuario</dt>
      <dd><?= htmlspecialchars($user['id_usuario'], ENT_QUOTES) ?></dd>

    <dt>Código</dt>
      <dd><?= htmlspecialchars($user['code_user'], ENT_QUOTES) ?></dd>

    <dt>Nombre completo</dt>
      <dd><?= htmlspecialchars(
            $user['nombre'] . ' ' . 
            $user['apellidoP'] . ' ' . 
            $user['apellidoM'],
            ENT_QUOTES
          ) ?></dd>

    <dt>Tipo</dt>
      <dd><?= htmlspecialchars($user['tipo'], ENT_QUOTES) ?></dd>

    <dt>Teléfono</dt>
      <dd><?= htmlspecialchars($user['telefono']  ?? '-', ENT_QUOTES) ?></dd>

    <dt>Correo electrónico</dt>
      <dd><?= htmlspecialchars($user['correo']    ?? '-', ENT_QUOTES) ?></dd>

    <dt>Fecha de nacimiento</dt>
      <dd><?= htmlspecialchars($user['fecha_nacimiento'], ENT_QUOTES) ?></dd>

    <dt>Fecha de registro</dt>
      <dd><?= htmlspecialchars($user['fecha_registro'], ENT_QUOTES) ?></dd>

    <dt>Dirección</dt>
      <dd>
        <?= htmlspecialchars($user['calle']          ?? '-', ENT_QUOTES) ?><br>
        <?= htmlspecialchars($user['colonia']        ?? '-', ENT_QUOTES) ?><br>
        <?= htmlspecialchars($user['codigo_postal']  ?? '-', ENT_QUOTES) ?><br>
        <?= htmlspecialchars($user['pais']           ?? '-', ENT_QUOTES) ?>
      </dd>
  </dl>
</body>
</html>
