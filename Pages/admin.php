<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 1) {
    header('Location: login.php');
    exit;
}

include("../includes/conexion.php");

$libros = 0;
$usuarios = 0;
$lectores = 0;
$multas = 0;

if (isset($conn)) {
    // Total de libros
    $stmt1 = $conn->prepare("SELECT COUNT(*) AS total FROM Libro");
    $stmt1->execute();
    $res1 = $stmt1->get_result();
    if ($r1 = $res1->fetch_assoc()) {
        $libros = $r1['total'];
    }

    // Total de usuarios
    $stmt2 = $conn->prepare("SELECT COUNT(*) AS total FROM Usuario");
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    if ($r2 = $res2->fetch_assoc()) {
        $usuarios = $r2['total'];
    }

    // Total de lectores
    $stmt3 = $conn->prepare("SELECT COUNT(*) AS total FROM Usuario WHERE tipo_usuario = 3");
    $stmt3->execute();
    $res3 = $stmt3->get_result();
    if ($r3 = $res3->fetch_assoc()) {
        $lectores = $r3['total'];
    }

    // Total de multas
    $stmt4 = $conn->prepare("SELECT COUNT(*) AS total FROM Multa");
    $stmt4->execute();
    $res4 = $stmt4->get_result();
    if ($r4 = $res4->fetch_assoc()) {
        $multas = $r4['total'];
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link rel="stylesheet" href="../Styles/inicio.css"> <!-- Nueva hoja de estilo moderna -->
</head>
<body>
    <div class="sidebar">
        <h2>📚 Biblioteca</h2>
        <ul>
            <li><a href="usuarios.php">👥 Gestionar Usuarios</a></li>
            <li><a href="registro_formulario_php.html">➕ Registrar Usuario</a></li>
            <li><a href="libros.php">📘 Libros</a></li>
            <li><a href="revistas.php">📚 Revistas</a></li>
            <li><a href="digital.php">💾 Digitales</a></li>
            <li><a href="registrar_libro.html">➕ Registrar Libro</a></li>
            <li><a href="registrar_revista.html">➕ Registrar Revista</a></li>
            <li><a href="registrar_digital.html">➕ Registrar Digital</a></li>
            <li><a href="prestamos_gestion.php">📅 Préstamos</a></li>
            <li><a href="gestion_multas.php">💸 Multas</a></li>
            <li><a href="Pages/reportes.html">📄 Reportes Mensuales</a></li>
            <li><a href="logout.php">🔒 Cerrar Sesión</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="header">
            <h1>Panel del Administrador</h1>
            <p>Accede a reportes automáticos, gestiona usuarios, libros y sanciones conforme a las reglas establecidas.</p>
        </div>

        <div class="stats">
            <div class="card">
                <h3>Total de Libros</h3>
                <p><strong><?php echo $libros; ?></strong></p>
            </div>
            <div class="card">
                <h3>Usuarios Registrados</h3>
                <p><strong><?php echo $usuarios; ?></strong></p>
            </div>
            <div class="card">
                <h3>Lectores</h3>
                <p><strong><?php echo $lectores; ?></strong></p>
            </div>
            <div class="card">
                <h3>Multas</h3>
                <p><strong><?php echo $multas; ?></strong></p>
            </div>
        </div>

        <div class="activity">
            <h2>Últimas Acciones</h2>
            <div class="item">
                <div>
                    <strong>Nuevo préstamo</strong><br>
                    Libro: Cien años de soledad<br>
                    Usuario: María González
                </div>
                <span>Hace 2 horas</span>
            </div>
            <div class="item">
                <div>
                    <strong>Devolución</strong><br>
                    Libro: El Quijote<br>
                    Usuario: Carlos Ruiz
                </div>
                <span>Hace 3 horas</span>
            </div>
            <div class="item">
                <div>
                    <strong>Nuevo usuario</strong><br>
                    Usuario: Ana Martínez
                </div>
                <span>Hace 5 horas</span>
            </div>
        </div>

        <div class="activity" style="margin-top: 30px;">
            <h2>Soporte Técnico</h2>
            <p>📧 Correo: soporte@biblioteca.edu</p>
            <p>📞 Extensión: 101</p>
        </div>
    </div>
</body>
</html>
