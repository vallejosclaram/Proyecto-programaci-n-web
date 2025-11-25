<?php
include '../conexion.php';
header("Content-Type: application/json; charset=UTF-8");

try {
    if (!isset($_POST['usuario'], $_POST['email'], $_POST['password'])) {
        throw new Exception("Datos incompletos");
    }

    $usuario = trim($_POST['usuario']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validar email duplicado
    $check = $conexion->prepare("SELECT id_usuario FROM usuario WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        throw new Exception("El email ya está registrado");
    }

    // Hashear contraseña correctamente
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Crear usuario (Tabla usuario)
    $sql1 = $conexion->prepare(
        "INSERT INTO usuario (email, contrasena, usuario, id_estado) VALUES (?, ?, ?, 1)"
    );
    $sql1->bind_param("sss", $email, $passwordHash, $usuario);

    if (!$sql1->execute()) {
        throw new Exception("Error al crear usuario");
    }

    $id_usuario = $sql1->insert_id;

    // Crear organizador (Tabla organizador)
    $sql2 = $conexion->prepare(
        "INSERT INTO organizador (id_usuario) VALUES (?)"
    );
    $sql2->bind_param("i", $id_usuario);

    if (!$sql2->execute()) {
        throw new Exception("Error al crear organizador");
    }

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
