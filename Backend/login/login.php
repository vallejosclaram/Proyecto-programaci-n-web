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


$stored = $usuario['contraseña'] ?? '';
$password_ok = false;
$migrate_plain_to_hash = false;
if ($stored === $password) {
    
    $password_ok = true;
    $migrate_plain_to_hash = true;
} else {
    
    if (function_exists('password_verify') && password_verify($password, $stored)) {
        $password_ok = true;
    }
}
if (!$password_ok) {
    echo json_encode(["error" => "Contraseña incorrecta"]);
    exit;
}


if ($migrate_plain_to_hash) {
    if (function_exists('password_hash')) {
        $newHash = password_hash($password, PASSWORD_BCRYPT);
        if ($newHash !== false) {
            $uupd = $conn->prepare('UPDATE usuario SET contraseña = ? WHERE id_usuario = ? LIMIT 1');
            if ($uupd) {
                $uupd->bind_param('si', $newHash, $usuario['id_usuario']);
                $uupd->execute();
            }
        }
    }
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
// Intentar también obtener id_organizador si existe
$id_organizador = null;
if ($rol === 'organizador') {
    $qIdOrg = "SELECT id_organizador FROM organizador WHERE id_usuario = ? LIMIT 1";
    $stmtIdOrg = $conn->prepare($qIdOrg);
    $stmtIdOrg->bind_param("i", $usuario['id_usuario']);
    $stmtIdOrg->execute();
    $resIdOrg = $stmtIdOrg->get_result();
    if ($resIdOrg && $resIdOrg->num_rows > 0) {
        $id_organizador = $resIdOrg->fetch_assoc()['id_organizador'];
        $_SESSION['id_organizador'] = $id_organizador;
    }
}

echo json_encode([
    "success" => true,
    "rol" => $rol,
    "id_usuario" => $usuario['id_usuario'],
    "id_organizador" => $id_organizador,
    "nombre" => $_SESSION['nombre'] ?? '',
    "apellido" => $_SESSION['apellido'] ?? ''
]);
?>
