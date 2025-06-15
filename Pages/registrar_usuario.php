<?php
// Pages/registrar_usuario.php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<p>Acceso no permitido.</p>";
    exit;
}

// 1) Leer y validar entradas
$tipo_str      = trim($_POST['tipo_usuario']         ?? '');
$nombre        = trim($_POST['nombre']               ?? '');
$apellidoP     = trim($_POST['apellidoP']            ?? '');
$apellidoM     = trim($_POST['apellidoM']            ?? '');
$telefono      = trim($_POST['telefono']             ?? '');
$correo        = trim($_POST['correo']               ?? '');
$fecha_nac     = trim($_POST['fecha_nacimiento']     ?? '');
$calle         = trim($_POST['calle']                ?? '');
$colonia       = trim($_POST['colonia']              ?? '');
$codigo_postal = trim($_POST['codigo_postal']        ?? '');
$pais          = trim($_POST['pais']                 ?? '');

function generarContrasena($longitud = 8) {
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $contrasena = '';
        for ($i = 0; $i < $longitud; $i++) {
            $contrasena .= $caracteres[random_int(0, strlen($caracteres) - 1)];
        }
        return $contrasena;
    }

    $contrasena = generarContrasena();

// Mapear cadena → entero para la FK tipo_usuario
$mapTipo = [
    'Administrador' => 1,
    'Bibliotecario' => 2,
    'Lector'        => 3,
];
if (!isset($mapTipo[$tipo_str])) {
    die("<p>Error: tipo de usuario inválido.</p>");
}
$tipo = $mapTipo[$tipo_str];

// Validar los campos obligatorios
if ($nombre === '' || $apellidoP === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("<p>Error: faltan campos obligatorios o correo inválido.</p>");
}

$conn->begin_transaction();
try {
    // 2) Verificar existencia en Tipo_usuario
    $stmtTipo = $conn->prepare("SELECT 1 FROM Tipo_usuario WHERE tipo_usuario = ?");
    $stmtTipo->bind_param("i", $tipo);
    $stmtTipo->execute();
    $stmtTipo->store_result();
    if ($stmtTipo->num_rows === 0) {
        throw new Exception("Tipo de usuario no existe en la base.");
    }
    $stmtTipo->close();

    // 3) Unicidad de correo
    $stmtCheck = $conn->prepare("SELECT id_usuario FROM Usuario WHERE correo = ?");
    $stmtCheck->bind_param("s", $correo);
    $stmtCheck->execute();
    $stmtCheck->store_result();
    if ($stmtCheck->num_rows > 0) {
        throw new Exception("El correo ya está registrado.");
    }
    $stmtCheck->close();

    // 4) Generar code_user
    $prefijos = ['Administrador'=>'A','Bibliotecario'=>'B','Lector'=>'C'];
    $prefijo  = $prefijos[$tipo_str];
    $like     = $prefijo . '%';
    $stmtMax  = $conn->prepare(
        "SELECT MAX(code_user) AS max_code
           FROM Usuario
          WHERE code_user LIKE ?"
    );
    $stmtMax->bind_param("s", $like);
    $stmtMax->execute();
    $resMax = $stmtMax->get_result();
    $rowMax = $resMax->fetch_assoc();
    $stmtMax->close();
    
    $numero = 1;
    if (!empty($rowMax['max_code'])) {
        $numero = (int)substr($rowMax['max_code'], 1) + 1;
    }
    // Lectores usan 4 dígitos, otros 3
    $formato   = $tipo === 3 ? '%s%04d' : '%s%03d';
    $code_user = sprintf($formato, $prefijo, $numero);

    // 5) Insertar en Usuario
    $hash = $contrasena;
    $stmt = $conn->prepare(
        "INSERT INTO Usuario
         (tipo_usuario, contrasena, code_user, nombre,
          apellidoP, apellidoM, telefono, correo,
          fecha_nacimiento, fecha_registro)
         VALUES (?,?,?,?,?,?,?,?,?, NOW())"
    );
    $stmt->bind_param(
        "issssssss",
        $tipo,
        $hash,
        $code_user,
        $nombre,
        $apellidoP,
        $apellidoM,
        $telefono,
        $correo,
        $fecha_nac
    );
    if (!$stmt->execute()) {
        throw new Exception("Error al insertar usuario: " . $stmt->error);
    }
    $id_usuario = $conn->insert_id;
    $stmt->close();

    // 6) Insertar en tabla de rol
    $sqlRol = [
        1 => "INSERT INTO Administrador  (id_admin) VALUES (?)",
        2 => "INSERT INTO Bibliotecario (id_biblio) VALUES (?)",
        3 => "INSERT INTO Lector       (id_lector) VALUES (?)",
    ][$tipo];
    $stmtRol = $conn->prepare($sqlRol);
    $stmtRol->bind_param("i", $id_usuario);
    if (!$stmtRol->execute()) {
        throw new Exception("Error al asignar rol: " . $stmtRol->error);
    }
    $stmtRol->close();

    // 7) Insertar dirección
    $stmtDir = $conn->prepare(
        "INSERT INTO Direccion
         (id_usuario, calle, colonia, codigo_postal, pais)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmtDir->bind_param(
        "issss",
        $id_usuario,
        $calle,
        $colonia,
        $codigo_postal,
        $pais
    );
    if (!$stmtDir->execute()) {
        throw new Exception("Error al insertar dirección: " . $stmtDir->error);
    }
    $stmtDir->close();

    $conn->commit();
    echo "<p>Usuario registrado con éxito. Código: "
       . htmlspecialchars($code_user, ENT_QUOTES, 'UTF-8')
       . "</p>";
    echo "<p>Contraseña generada: <strong>"
   . htmlspecialchars($contrasena, ENT_QUOTES, 'UTF-8')
   . "</strong></p>";


} catch (Exception $e) {
    $conn->rollback();
    echo "<p>Error en el registro: "
       . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
       . "</p>";
}

$conn->close();