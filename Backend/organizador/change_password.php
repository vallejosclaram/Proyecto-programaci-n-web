<?php
session_start();
include '../../vistas/connection.php';

$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
    header('Location: ../../Frontend/auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../vistas/Organizador/perfil/configuracion.php');
    exit;
}

$current = trim($_POST['current'] ?? '');
$new = trim($_POST['new'] ?? '');
$confirm = trim($_POST['confirm'] ?? '');

if ($new === '' || $current === '') {
    header('Location: ../../vistas/Organizador/perfil/configuracion.php?error=empty');
    exit;
}

if ($new !== $confirm) {
    header('Location: ../../vistas/Organizador/perfil/configuracion.php?error=match');
    exit;
}

try {
    // Obtener contraseña actual
    $stmt = $conn->prepare("SELECT contrasena FROM usuario WHERE id_usuario = ? LIMIT 1");
    $stmt->execute([$id_usuario]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        header('Location: ../../vistas/Organizador/perfil/configuracion.php?error=nouser');
        exit;
    }

    $hash = $row['contrasena'];

    // Validar contraseña actual
    if (!password_verify($current, $hash)) {
        header('Location: ../../vistas/Organizador/perfil/configuracion.php?error=current');
        exit;
    }

    // Nueva contraseña hasheada
    $newHash = password_hash($new, PASSWORD_BCRYPT);

    // Actualizar
    $update = $conn->prepare("UPDATE usuario SET contrasena = ? WHERE id_usuario = ?");
    $update->execute([$newHash, $id_usuario]);

    header("Location: ../../vistas/Organizador/perfil/configuracion.php?changed=1");
    exit;

} catch (Exception $e) {
    header("Location: ../../vistas/Organizador/perfil/configuracion.php?error=exception");
    exit;
}
?>