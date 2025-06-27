<?php
// Pages/Funciones/procesar_libro.php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Pages/subir_libro.php');
    exit;
}

$nombre  = trim($_POST['nombre_libro'] ?? '');
$materia = $_POST['materia'] ?? '';
$file    = $_FILES['pdf_file'] ?? null;

// Validar campos
if (!$file || $nombre === '' || $materia === '') {
    die('Faltan datos.');
}

if ($file['error'] !== UPLOAD_ERR_OK) {
    die("Error de subida (code {$file['error']}).");
}

// Solo PDF
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if ($ext !== 'pdf') {
    die('Solo PDF permitido.');
}

$map = [
    'Analisis Vectorial'     => 'Analisis Vectorial/Libros',
    'Matematicas Discretas'  => 'Matematicas Discretas/Libros',
    'Calculo'                => 'Calculo/Libros',
];
if (!isset($map[$materia])) {
    die('Materia inválida.');
}

$uploadDir = __DIR__ . '/../../uploads/' . $map[$materia] . '/';

// ** DEBUG **
var_dump($file);
echo "Destino: $uploadDir\n";
echo "is_dir: " . (is_dir($uploadDir) ? 'sí' : 'no') . "\n";
echo "is_writable: " . (is_writable($uploadDir) ? 'sí' : 'no') . "\n";
// ** FIN DEBUG **

if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
    die('No se pudo crear carpeta.');
}

$safeName  = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nombre);
$finalName = $safeName . '_' . time() . '.pdf';
$targetPath= $uploadDir . $finalName;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    $err = error_get_last();
    var_dump($err);
    die('Error al guardar el archivo. Checa los detalles.');
}

header('Location: ../subir_libro.php?ok=1');
exit;
