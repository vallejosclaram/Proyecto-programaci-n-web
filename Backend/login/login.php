<?php
session_start();
include("../conexion.php");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(["error" => "Faltan datos"]);
    exit;
}

// Buscar usuario base
$query = "SELECT * FROM usuario WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(["error" => "Usuario no encontrado"]);
    exit;
}

$usuario = $result->fetch_assoc();

if ($usuario['contraseña'] !== $password) {
    echo json_encode(["error" => "Contraseña incorrecta"]);
    exit;
}

// Buscar el rol del usuario
$queryRol = "SELECT r.nombre_rol 
             FROM usuario_rol ur 
             JOIN rol r ON ur.id_rol = r.id_rol 
             WHERE ur.id_usuario = ?";
$stmtRol = $conn->prepare($queryRol);
$stmtRol->bind_param("i", $usuario['id_usuario']);
$stmtRol->execute();
$resRol = $stmtRol->get_result();
$rol = $resRol->fetch_assoc()['nombre_rol'] ?? '';

$_SESSION['id_usuario'] = $usuario['id_usuario'];
$_SESSION['rol'] = $rol;

// Si es organizador, buscar su nombre
if ($rol === 'organizador') {
    $qOrg = "SELECT nombre, apellido FROM organizador WHERE id_usuario = ?";
    $stmtOrg = $conn->prepare($qOrg);
    $stmtOrg->bind_param("i", $usuario['id_usuario']);
    $stmtOrg->execute();
    $resOrg = $stmtOrg->get_result();
    $org = $resOrg->fetch_assoc();
    $_SESSION['nombre'] = $org['nombre'];
    $_SESSION['apellido'] = $org['apellido'];
}

echo json_encode([
    "success" => true,
    "rol" => $rol,
    "nombre" => $_SESSION['nombre'] ?? '',
    "apellido" => $_SESSION['apellido'] ?? ''
]);
?>
