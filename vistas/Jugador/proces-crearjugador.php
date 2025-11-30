<?php
require_once("../connection.php");
header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);
$errors = [];

$nombre = $data['nombre'];
$apellido = $data['apellido'];
$pais = $data['pais'];
$email = $data['email'];
$contrasena = $data['contrasena'];
$fechaNacimiento = $data['fechaNacimiento'];
$id_rol = intval($data['id_rol']); 

//validaciones
if ($nombre === '') {
    $errors['nombre'] = 'El nombre no puede estar vacío';
}

if ($apellido === '') {
    $errors['apellido'] = 'El apellido no puede estar vacío';
}

if ($email === '') {
    $errors['email'] = 'El email no puede estar vacío';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Formato de email inválido';
}

if ($contrasena === '') {
    $errors['contrasena'] = 'La contraseña no puede estar vacía';
}

if ($fechaNacimiento === '') {
    $errors['fechaNacimiento'] = 'Debe ingresar una fecha de nacimiento';
}


$hoy = new DateTime();
$nac = new DateTime($fechaNacimiento);
$diff = $hoy->diff($nac);

if ($diff->y < 13) {
    $errors['fechaNacimiento'] = 'Debés tener al menos 13 años';
}

if (!empty($errors)) {
    echo json_encode(['errores' => $errors]);
    exit;
}

try {
    
    $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $existe = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existe) {
        echo json_encode(['errores' => ['email' => 'Este email ya está registrado']]);
        exit;
    }

    
    $hash = password_hash($contrasena, PASSWORD_BCRYPT);

    $sqlusuario = "INSERT INTO usuario (email, contrasena, fecha_registro, id_estado)
               VALUES (:email, :pass, NOW(), :estado)";

        $stmt_u = $conn->prepare($sqlusuario);

        $stmt_u->execute([
            ':email' => $email,
            ':pass' => $contrasena,
            ':estado' => 1
        ]);

    $id_usuario = $conn->lastInsertId();

    // Crear jugador
    $sqljugador = "INSERT INTO jugador 
(id_usuario, nombre, apellido, pais, fecha_nacimiento)
VALUES (:usuario, :nombre, :apellido, :pais, :fecha_nacimiento, :id_rol)";
    $stmt_j = $conn->prepare($sqljugador);
    $stmt_j->execute([
        ':usuario' => $id_usuario,
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':pais' => $pais,
        ':fecha_nacimiento' => $fechaNacimiento,
        ':id_rol' => $id_rol
    ]);

    // asignar rol
    $sql_rol = "INSERT INTO usuario_rol (id_usuario, id_rol)
                VALUES (:usuario, :rol)";
    $stmt_r = $conn->prepare($sql_rol);
    $stmt_r->execute([
        ':usuario' => $id_usuario,
        ':rol' => $id_rol
    ]);

    echo json_encode(['mensaje' => 'Jugador creado correctamente']);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
