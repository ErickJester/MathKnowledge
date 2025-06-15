<?php
include('../../includes/conexion.php');

// Directorio uploads
$uploadsDir = __DIR__ . '/../../uploads/';

// 1) Procesar subida del PDF
if (!isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] !== UPLOAD_ERR_OK) {
    die("Error: No se recibió el PDF o hubo un problema en la subida.");
}
$tmpPath  = $_FILES['pdf_file']['tmp_name'];
$origName = basename($_FILES['pdf_file']['name']);
$mimeType = $_FILES['pdf_file']['type'];

// Validar PDF
if ($mimeType !== 'application/pdf') {
    die("Error: Solo se permiten archivos PDF.");
}

// Nombre único
$safeName  = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $origName);
$destPath  = $uploadsDir . $safeName;
if (!move_uploaded_file($tmpPath, $destPath)) {
    die("Error: No se pudo mover el PDF al directorio uploads.");
}

// 2) Recoger datos del formulario
$titulo   = $_POST['titulo'];
$autor    = $_POST['autor'];
$fecha    = $_POST['fecha_registro'];
$cantidad = $_POST['cantidad'];
$genero   = $_POST['genero'];

$conn->begin_transaction();

try {
    // 3) Insertar en Material
    $stmt1 = $conn->prepare("
        INSERT INTO Material (titulo, autor, fecha_registro, cantidad, genero)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt1->bind_param("sssds", $titulo, $autor, $fecha, $cantidad, $genero);
    $stmt1->execute();
    $id_material = $conn->insert_id;
    $stmt1->close();

    // 4) Insertar en Digital con ruta del PDF
    $stmt2 = $conn->prepare("
        INSERT INTO Digital (id_digital, archivo_pdf)
        VALUES (?, ?)
    ");
    $stmt2->bind_param("is", $id_material, $safeName);
    $stmt2->execute();
    $stmt2->close();

    $conn->commit();
    echo "Medio digital registrado correctamente. PDF guardado como: $safeName";
} catch (Exception $e) {
    $conn->rollback();
    echo "Error en la transacción: " . $e->getMessage();
}
?>
