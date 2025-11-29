<?php
session_start();
// Archivo para actualizar perfil de organizador
include '../../vistas/connection.php';

// Validar sesión
$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
    header('Location: ../../Frontend/auth/login.php');
    exit;
}

// Asegurar que la petición es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../vistas/Organizador/perfil/editar.php');
    exit;
}

try {
    // Obtener id_organizador
    $stmtOrg = $conn->prepare('SELECT id_organizador FROM organizador WHERE id_usuario = ? LIMIT 1');
    $stmtOrg->execute([$id_usuario]);
    $org = $stmtOrg->fetch(PDO::FETCH_ASSOC);
    $id_organizador = $org['id_organizador'] ?? null;

    if (!$id_organizador) {
        header('Location: ../../vistas/Organizador/perfil/ver.php?error=sin_perfil');
        exit;
    }

    // Recoger datos POST
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');

    // Validaciones básicas
    if ($nombre === '' || $email === '') {
        header('Location: ../../vistas/Organizador/perfil/editar.php?error=datos');
        exit;
    }

    // Validar formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../../vistas/Organizador/perfil/editar.php?error=email');
        exit;
    }

    // Comprobar unicidad del email (otro usuario no debe tenerlo)
    $stmtCheck = $conn->prepare('SELECT id_usuario FROM usuario WHERE email = ? AND id_usuario != ? LIMIT 1');
    $stmtCheck->execute([$email, $id_usuario]);
    $exists = $stmtCheck->fetch(PDO::FETCH_ASSOC);
    if ($exists) {
        header('Location: ../../vistas/Organizador/perfil/editar.php?error=exists');
        exit;
    }

    // Actualizar organizador
    $stmtUpd = $conn->prepare('UPDATE organizador SET nombre = ?, apellido = ?, descripcion = ? WHERE id_organizador = ?');
    $stmtUpd->execute([$nombre, $apellido, $descripcion, $id_organizador]);

    // Actualizar email en usuario
    $stmtUser = $conn->prepare('UPDATE usuario SET email = ? WHERE id_usuario = ?');
    $stmtUser->execute([$email, $id_usuario]);

    // Redirigir de vuelta al perfil
    header('Location: ../../vistas/Organizador/perfil/ver.php?actualizado=1');
    exit;

} catch (Exception $ex) {
    header('Location: ../../vistas/Organizador/perfil/editar.php?error=exception');
    exit;
}

?>
