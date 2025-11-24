<?php
header('Content-Type: application/json; charset=utf-8');
include __DIR__ . '/../conexion.php';

$usuario = trim($_POST['usuario'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$usuario || !$email || !$password) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos requeridos']);
    exit;
}

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Email inválido']);
    exit;
}

// Verificar que no exista el email
$check = $conexion->prepare('SELECT id_usuario FROM usuario WHERE email = ? LIMIT 1');
$check->bind_param('s', $email);
$check->execute();
$res = $check->get_result();
if ($res && $res->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => 'El correo ya está registrado']);
    exit;
}


$stmt = $conexion->prepare('INSERT INTO usuario (email, contraseña, fecha_registro, id_estado) VALUES (?, ?, CURDATE(), 1)');


if (!function_exists('password_hash')) {
    echo json_encode(['success' => false, 'error' => 'El servidor PHP no soporta password_hash.']);
    exit;
}
$hash = password_hash($password, PASSWORD_BCRYPT);
if ($hash === false) {
    echo json_encode(['success' => false, 'error' => 'Error al procesar la contraseña']);
    exit;
}

$stmt->bind_param('ss', $email, $hash);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'error' => 'Error al crear usuario']);
    exit;
}

$id_usuario = $conexion->insert_id;

$q = $conexion->prepare('SELECT id_rol FROM rol WHERE nombre_rol = ? LIMIT 1');
$roleName = 'organizador';
$q->bind_param('s', $roleName);
$q->execute();
$r = $q->get_result();
if ($r && $r->num_rows > 0) {
    $id_rol = $r->fetch_assoc()['id_rol'];
} else {
    echo json_encode(['success' => false, 'error' => 'Rol organizador no encontrado en la base de datos']);
    exit;
}


$insUR = $conexion->prepare('INSERT INTO usuario_rol (id_usuario, id_rol) VALUES (?, ?)');
$insUR->bind_param('ii', $id_usuario, $id_rol);
if (!$insUR->execute()) {
    echo json_encode(['success' => false, 'error' => 'Error al asignar rol']);
    exit;
}


$insOrg = $conexion->prepare('INSERT INTO organizador (id_usuario, nombre, apellido) VALUES (?, ?, ?)');
$apellido = '';
$insOrg->bind_param('iss', $id_usuario, $usuario, $apellido);
if (!$insOrg->execute()) {
    echo json_encode(['success' => false, 'error' => 'Error al crear organizador']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Organizador creado correctamente']);
exit;
?>
