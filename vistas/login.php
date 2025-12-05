<?php
require 'connection.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log("ROL DEL USUARIO: $rol"); 
    echo json_encode(['success' => false, 'error' => 'Método inválido']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    error_log("ROL DEL USUARIO: $rol"); 
    echo json_encode(['success' => false, 'error' => 'Faltan datos']);
    exit;
}

// 1️⃣ Buscar usuario
$stmt = $conn->prepare("SELECT id_usuario, email, contrasena, id_estado FROM usuario WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    error_log("ROL DEL USUARIO: $rol"); 
    echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
    exit;
}

if ($usuario['id_estado'] != 1) { // Suponiendo 1 = activo
    error_log("ROL DEL USUARIO: $rol"); 
    echo json_encode(['success' => false, 'error' => 'Usuario inactivo']);
    exit;
}

// 2️⃣ Verificar contraseña
if (!password_verify($password, $usuario['contrasena'])) {
    error_log("ROL DEL USUARIO: $rol"); 
    echo json_encode(['success' => false, 'error' => 'Contraseña incorrecta']);
    exit;
}

// 3️⃣ Obtener rol del usuario
$stmt = $conn->prepare("
    SELECT r.nombre_rol 
    FROM rol r
    JOIN usuario_rol ur ON ur.id_rol = r.id_rol
    WHERE ur.id_usuario = ?
    LIMIT 1
");
$stmt->execute([$usuario['id_usuario']]);
$rol = $stmt->fetchColumn();

// 4️⃣ Obtener datos adicionales si es organizador
$nombreCompleto = '';
if ($rol === 'organizador') {
    $stmt = $conn->prepare("SELECT nombre, apellido FROM organizador WHERE id_usuario = ?");
    $stmt->execute([$usuario['id_usuario']]);
    $org = $stmt->fetch(PDO::FETCH_ASSOC);
    $nombreCompleto = $org ? $org['nombre'] . ' ' . $org['apellido'] : '';
} 
// Obtener datos adicionales si es jugador
$nombreCompleto = '';
if ($rol === 'jugador') {
    $stmt = $conn->prepare("SELECT nombre, apellido FROM jugador WHERE id_usuario = ?");
    $stmt->execute([$usuario['id_usuario']]);
    $jug = $stmt->fetch(PDO::FETCH_ASSOC);
    $nombreCompleto = $jug ? $jug['nombre'] . ' ' . $jug['apellido'] : '';
} 

// 5️⃣ Guardar datos en sesión
$_SESSION['id_usuario'] = $usuario['id_usuario'];
$_SESSION['rol'] = $rol;
$_SESSION['nombre'] = $nombreCompleto ?: $usuario['email']; // si no tiene nombre, usamos email
$_SESSION['email'] = $usuario['email'];

echo json_encode([
    'success' => true,
    'rol' => $rol,
    'nombre' => $_SESSION['nombre']
]);
?>
