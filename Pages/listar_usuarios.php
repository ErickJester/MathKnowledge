<?php
// Pages/listar_usuarios.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/conexion.php';

// 1) Captura filtros
$tipo   = isset($_GET['tipo_usuario']) ? (int)$_GET['tipo_usuario'] : '';
$nombre = trim($_GET['nombre']   ?? '');
$codigo = trim($_GET['codigo']   ?? '');

// 2) Construye condiciones dinámicas
$where  = [];
$params = [];
$types  = '';

if ($tipo !== '') {
    $where[]  = 'u.tipo_usuario = ?';
    $params[] = $tipo;
    $types   .= 'i';
}

if ($nombre !== '') {
    $where[]  = '(u.nombre LIKE ? OR u.apellidoP LIKE ? OR u.apellidoM LIKE ?)';
    $params[] = "%{$nombre}%";
    $params[] = "%{$nombre}%";
    $params[] = "%{$nombre}%";
    $types   .= 'sss';
}

if ($codigo !== '') {
    $where[]  = 'u.code_user LIKE ?';
    $params[] = "%{$codigo}%";
    $types   .= 's';
}

// 3) Arma la consulta SQL
$sql = "
    SELECT
      u.id_usuario    AS id,
      u.code_user,
      u.nombre,
      u.apellidoP,
      u.apellidoM,
      CASE u.tipo_usuario
        WHEN 1 THEN 'Administrador'
        WHEN 2 THEN 'Bibliotecario'
        WHEN 3 THEN 'Lector'
      END AS tipo,
      u.fecha_nacimiento,
      u.fecha_registro,
      u.telefono,
      u.correo
    FROM Usuario u
";

if (!empty($where)) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}

$sql .= ' ORDER BY u.id_usuario DESC';

// 4) Ejecuta la consulta
$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Listado de Usuarios</title>
  <link rel="stylesheet" href="../Styles/main.css">
</head>
<body>
  <h1>Gestión de Usuarios</h1>

  <form method="get" action="">
    <label>Tipo de usuario:</label>
    <select name="tipo_usuario">
      <option value="">— Todos —</option>
      <option value="1" <?= $tipo === 1 ? 'selected' : '' ?>>Administrador</option>
      <option value="2" <?= $tipo === 2 ? 'selected' : '' ?>>Bibliotecario</option>
      <option value="3" <?= $tipo === 3 ? 'selected' : '' ?>>Lector</option>
    </select>

    <label>Nombre:</label>
    <input type="text" name="nombre" placeholder="Nombre" value="<?= htmlspecialchars($nombre, ENT_QUOTES) ?>">

    <label>Código:</label>
    <input type="text" name="codigo" placeholder="Código" value="<?= htmlspecialchars($codigo, ENT_QUOTES) ?>">

    <button type="submit">Filtrar</button>
  </form>

  <?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="4" cellspacing="0">
      <thead>
        <tr>
          <th>ID</th>
          <th>Código</th>
          <th>Nombre</th>
          <th>Apellidos</th>
          <th>Tipo</th>
          <th>Fecha Nac.</th>
          <th>Fecha Reg.</th>
          <th>Teléfono</th>
          <th>Correo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php while ($u = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($u['id'], ENT_QUOTES) ?></td>
          <td><?= htmlspecialchars($u['code_user'], ENT_QUOTES) ?></td>
          <td><?= htmlspecialchars($u['nombre'], ENT_QUOTES) ?></td>
          <td><?= htmlspecialchars($u['apellidoP'] . ' ' . $u['apellidoM'], ENT_QUOTES) ?></td>
          <td><?= htmlspecialchars($u['tipo'], ENT_QUOTES) ?></td>
          <td><?= htmlspecialchars($u['fecha_nacimiento'], ENT_QUOTES) ?></td>
          <td><?= htmlspecialchars($u['fecha_registro'], ENT_QUOTES) ?></td>
          <td><?= htmlspecialchars($u['telefono'], ENT_QUOTES) ?></td>
          <td><?= htmlspecialchars($u['correo'], ENT_QUOTES) ?></td>
          <td>
            <a href="detalles_usuario.php?id=<?= urlencode($u['id']) ?>">Ver</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No se encontraron usuarios.</p>
  <?php endif; ?>

<?php
$conn->close();
?>
</body>
</html>
