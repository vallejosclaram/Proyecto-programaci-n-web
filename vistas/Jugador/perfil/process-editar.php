<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

header("Content-Type: application/json");

if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

$usuario_id = $_SESSION["user"]["id"];

$email = $_POST["email"] ?? null;
$nombre = $_POST["nombre"] ?? null;
$apellido = $_POST["apellido"] ?? null;
$descripcion = $_POST["descripcion"] ?? null;


// ---------- TABLA USUARIO ----------
$updates_usuario = [];
$params_usuario = [":id" => $usuario_id];

if ($email !== null && $email !== "") {
    $updates_usuario[] = "email = :email";
    $params_usuario[":email"] = $email;
}

if ($email !== null && $email !== "") {

    $check = $conn->prepare("SELECT id_usuario FROM usuario WHERE email = :email AND id_usuario != :id");
    $check->execute([
        ":email" => $email,
        ":id" => $usuario_id
    ]);

    if ($check->rowCount() > 0) {
        echo json_encode(["error" => "El email ya está en uso por otra cuenta"]);
        exit;
    }

    $updates_usuario[] = "email = :email";
    $params_usuario[":email"] = $email;
}

if (!empty($updates_usuario)) {
    $sqlUsuario = "UPDATE usuario SET " . implode(", ", $updates_usuario) . " WHERE id_usuario = :id";
    $stmt = $conn->prepare($sqlUsuario);
    $stmt->execute($params_usuario);
}


// ---------- TABLA JUGADOR ----------
$updates_jugador = [];
$params_jugador = [":id" => $usuario_id];

if ($nombre !== null && $nombre !== "") {
    $updates_jugador[] = "nombre = :nombre";
    $params_jugador[":nombre"] = $nombre;
}

if ($apellido !== null && $apellido !== "") {
    $updates_jugador[] = "apellido = :apellido";
    $params_jugador[":apellido"] = $apellido;
}

if ($descripcion !== null && $descripcion !== "") {
    $updates_jugador[] = "biografia = :bio";
    $params_jugador[":bio"] = $descripcion;
}

if (!empty($updates_jugador)) {
    $sqlJugador = "
        UPDATE jugador
        SET " . implode(", ", $updates_jugador) . "
        WHERE id_usuario = :id
    ";
    $stmt = $conn->prepare($sqlJugador);
    $stmt->execute($params_jugador);
}

echo json_encode(["ok" => true, "mensaje" => "Perfil actualizado con éxito"]);
exit;
