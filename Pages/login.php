<?php
include '../includes/conexion.php';
session_start();

include '../includes/multas_auto.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['user']);
    $contrasena = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id_usuario, tipo_usuario, contrasena FROM Usuario WHERE code_user = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($fila = $result->fetch_assoc()) {
        if ($contrasena === $fila['contrasena']) {
            $_SESSION['usuario_id'] = $fila['id_usuario'];
            $_SESSION['tipo_usuario'] = $fila['tipo_usuario'];
            $_SESSION['nombre'] = $usuario;

            // Redirigir según tipo
            switch ($fila['tipo_usuario']) {
                case 1:
                    header("Location: admin.php");
                    exit;
                case 2:
                    header("Location: biblio.php");
                    exit;
                case 3:
                    header("Location: lector.php");
                    exit;
            }
        } else {
            echo "<p>Contraseña incorrecta.</p>";
        }
    } else {
        echo "<p>Usuario no encontrado.</p>";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../Styles/login_style.css">
</head>
<body>
    <header><h1>Iniciar sesión</h1></header>
    <form action="" method="post">
        <label for="user">Usuario:</label>
        <input type="text" id="user" name="user" placeholder="Ingresa tu usuario" required><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" placeholder="####" required><br>

        <button type="submit">Enviar</button>
    </form>
</body>
</html>


