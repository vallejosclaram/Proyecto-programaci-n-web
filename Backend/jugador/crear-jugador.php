<?php
header("Content-Type: application/json");
include __DIR__ . '/../../vistas/connection.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$nombre = trim($data['nombre'] ?? '');
$apellido = trim($data['apellido'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['contrasena'] ?? '';
$fechaNacimiento = $data['fechaNacimiento'] ?? '';
$pais = $data['pais'] ?? '';
$id_rol = 2; 
$id_membresia = 1;

if (!$nombre || !$apellido || !$email || !$password || !$fechaNacimiento || !$pais) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'error' => 'El email ya está registrado']);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuario (email, contrasena, fecha_registro, id_estado) VALUES (?, ?, CURRENT_DATE(), 1)");
    $stmt->execute([$email, $hash]);
    $id_usuario = $conn->lastInsertId();

    $stmt2 = $conn->prepare("INSERT INTO jugador (id_usuario, nombre, apellido, pais, fecha_nacimiento, puntaje, id_rol, id_membresia) VALUES (?, ?, ?, ?, ?, 0, ?, ?)");
    $stmt2->execute([$id_usuario, $nombre, $apellido, $pais, $fechaNacimiento, $id_rol, $id_membresia]);

    echo json_encode(['success' => true, 'mensaje' => 'Jugador registrado correctamente']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>