<?php
session_start();
include('../../includes/conexion.php');

$mensaje = '';
$bloqueado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id && is_numeric($id)) {
        // Obtener tipo de usuario
        $stmt = $conn->prepare("SELECT tipo_usuario FROM Usuario WHERE id_usuario = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $usuario = $res->fetch_assoc();
        $tipo = $usuario['tipo_usuario'] ?? null;
        $stmt->close();

        if (!$usuario) {
            $mensaje = "El usuario no existe.";
            $bloqueado = true;
        } elseif ((int)$tipo === 1) {
            $mensaje = "No se puede eliminar a un administrador.";
            $bloqueado = true;
        } else {
            // Verificar préstamos activos
            $res = $conn->query("SELECT 1 FROM Prestamo WHERE id_usuario = $id AND estado != 'devuelto' LIMIT 1");
            if ($res->num_rows > 0) {
                $mensaje = "Este usuario tiene préstamos activos y no puede ser eliminado.";
                $bloqueado = true;
            }

            // Verificar multas activas
            $res = $conn->query("SELECT 1 FROM Multa WHERE id_usuario = $id AND pagada = 0 LIMIT 1");
            if ($res->num_rows > 0) {
                $mensaje = "Este usuario tiene multas sin pagar y no puede ser eliminado.";
                $bloqueado = true;
            }

            // Eliminar si todo está correcto
            if (!$bloqueado) {
                $stmt = $conn->prepare("DELETE FROM Usuario WHERE id_usuario = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    $mensaje = "Usuario eliminado correctamente.";
                } else {
                    $mensaje = "Error al intentar eliminar el usuario.";
                }
                $stmt->close();
            }
        }
    } else {
        $mensaje = "ID de usuario inválido.";
        $bloqueado = true;
    }
} else {
    header("Location: ../usuarios.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminación de usuario</title>
    <link rel="stylesheet" href="../Styles/usuarios.css">
</head>
<body>
    <h1><?= htmlspecialchars($mensaje) ?></h1>
    <p><a href="../usuarios.php">Continuar</a></p>
</body>
</html>
