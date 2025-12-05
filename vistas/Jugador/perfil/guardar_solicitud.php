<?php
header("Content-Type: application/json");
require_once(__DIR__ . '/../../includes/clases/permisos.php'); 
require_once "../../connection.php";
session_start();

$id_usuario = $_SESSION["user"]["id"] ?? null;
if (!$id_usuario) {
    echo json_encode(["error" => "Usuario no logueado"]);
    exit;
}

if (!Permisos::tienePermiso('solicitar_membresia', $usuario_id)) {
    echo json_encode(['success' => false, 'error' => 'No tenés permiso para editar el perfil']);
    exit;
}


if (!isset($_POST["id_membresia"])) {
    echo json_encode(["error" => "Falta tipo de membresía"]);
    exit;
}

$id_membresia = $_POST["id_membresia"];

// Validación de archivo
if (!isset($_FILES["comprobante"])) {
    echo json_encode(["error" => "No llegó comprobante"]);
    exit;
}

$archivo = $_FILES["comprobante"];

// Carpeta donde se guardarán los comprobantes
$carpeta = __DIR__ . "/uploads/";
if (!is_dir($carpeta)) {
    mkdir($carpeta, 0777, true);
}

$nombreArchivo = time() . "_" . basename($archivo["name"]);
$rutaFinal = $carpeta . $nombreArchivo;

// Guardar archivo
if (!move_uploaded_file($archivo["tmp_name"], $rutaFinal)) {
    echo json_encode(["error" => "Error al guardar el archivo"]);
    exit;
}


$sql = "INSERT INTO solicitud_membresia (id_membresia, id_usuario, membresia, estado, comprobante) 
        VALUES (?, ?, ?, 'pendiente', ?)";

$stmt = $conn->prepare($sql); 
$descripcion = ""; 
$descQuery = $conn->prepare("SELECT descripcion FROM membresia WHERE id_membresia = ?");
$descQuery->execute([$id_membresia]);
$descripcion = $descQuery->fetchColumn();

$stmt->execute([$id_membresia, $id_usuario, $descripcion, $nombreArchivo]);

echo json_encode(["ok" => true]);
