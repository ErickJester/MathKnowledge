<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 2) {
    header("Location: login.php");
    exit;
}

$nombre_biblio = $_SESSION['nombre'];
$id_biblio = $_SESSION['usuario_id'];

include("../includes/conexion.php"); // asegúrate de la ruta

$libros = 0;
$prestamos = 0;
$multas = 0;

if (isset($conn)) {
    // Libros existentes
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total FROM Libro");
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    if ($row1 = $result1->fetch_assoc()) {
        $libros = $row1['total'];
    }

    // Préstamos activos
    $stmt2 = $conn->prepare("SELECT COUNT(*) AS total FROM Prestamo WHERE estado = 'activo'");
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    if ($row2 = $result2->fetch_assoc()) {
        $prestamos = $row2['total'];
    }

    // Multas registradas
    $stmt3 = $conn->prepare("SELECT COUNT(*) AS total FROM Multa");
    $stmt3->execute();
    $result3 = $stmt3->get_result();
    if ($row3 = $result3->fetch_assoc()) {
        $multas = $row3['total'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliotecario</title>
    <link rel="stylesheet" href="../Styles/inicio.css">
</head>
<body>
    <div class="sidebar">
        <h2>📚 Biblioteca</h2>
        <ul>
            <li><?= htmlspecialchars($nombre_biblio) ?> </li>
            <li><a href="registrar_libro.html">➕ Registrar Libro</a></li>
            <li><a href="registrar_revista.html">➕ Registrar Revista</a></li>
            <li><a href="registrar_digital.html">➕ Registrar Digital</a></li>
            <li><a href="prestamos_gestion.php">📅 Préstamos</a></li>
            <li><a href="gestion_multas.php">💸 Multas</a></li>
            <li><a href="cambio_contra.php">Cambiar contraseña</a></li>
            <li><a href="Funciones/detalle_usuario.php?id=<?= $id_biblio ?>">Ver info</a></li>
            <li><a href="../index.html">🔒 Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="header">
            <h1>Panel del Bibliotecario</h1>
            <p>Gestiona el material, controla los préstamos y multas desde este panel.</p>
        </div>

        <div class="stats">
            <div class="card">
                <h3>Libros</h3>
                <p><strong><?php echo $libros; ?></strong></p>
            </div>
            <div class="card">
                <h3>Préstamos activos</h3>
                <p><strong><?php echo $prestamos; ?></strong></p>
            </div>
            <div class="card">
                <h3>Multas registradas</h3>
                <p><strong><?php echo $multas; ?></strong></p>
            </div>
        </div>

        <div class="activity">
            <h2>Últimos movimientos</h2>
            <div class="item">
                <div>
                    <strong>Nuevo préstamo</strong><br>
                    Libro: La sombra del viento<br>
                    Usuario: Laura Mendoza
                </div>
                <span>Hace 1 hora</span>
            </div>
            <div class="item">
                <div>
                    <strong>Multa aplicada</strong><br>
                    Usuario: Pedro Solís<br>
                    Motivo: Retraso
                </div>
                <span>Hace 3 horas</span>
            </div>
        </div>
    </div>
</body>
</html>
