<?php
header("Content-Type: application/json");
include 'connection.php'; 

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

// Recibir datos del formulario
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$usuario = trim($_POST['usuario'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$fechaNacimiento = $_POST['fechaNacimiento'] ?? '';

// Validar que no falten datos
if (!$nombre || !$apellido || !$usuario || !$email || !$password || !$fechaNacimiento) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos']);
    exit;
}

try {
    // 1️⃣ Verificar si el email ya está registrado
    $query = $conn->prepare("SELECT id_usuario FROM usuario WHERE email = ?");
    $query->execute([$email]);
    if ($query->rowCount() > 0) {
        echo json_encode(['success' => false, 'error' => 'El email ya está registrado']);
        exit;
    }

    // 2️⃣ Insertar en tabla usuario
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO usuario (email, contrasena, fecha_registro, id_estado) VALUES (?, ?, CURRENT_DATE(), 1)");
    $stmt->execute([$email, $hash]);
    $id_usuario = $conn->lastInsertId();

    // 3️⃣ Insertar en tabla organizador
    $stmt2 = $conn->prepare("INSERT INTO organizador (id_usuario, nombre, apellido, id_estado) VALUES (?, ?, ?, 1)");
    $stmt2->execute([$id_usuario, $nombre, $apellido]);

    // 4️⃣ Asignar rol organizador en usuario_rol
    $id_rol_organizador = 3; // 3 = organizador
    $stmt3 = $conn->prepare("INSERT INTO usuario_rol (id_usuario, id_rol) VALUES (?, ?)");
    $stmt3->execute([$id_usuario, $id_rol_organizador]);

    echo json_encode(['success' => true, 'message' => 'Organizador registrado correctamente']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>