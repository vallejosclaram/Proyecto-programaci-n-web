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



$updates_usuario = [];
$params_usuario = [":id" => $usuario_id];



if ($email !== null) {
    $updates_usuario[] = "email = :email";
    $params_usuario[":email"] = $email;
}

if (!empty($updates_usuario)) {
    $sqlUsuario = "UPDATE usuario SET " . implode(", ", $updates_usuario) . " WHERE id_usuario = :id";
    $stmt = $conn->prepare($sqlUsuario);
    $stmt->execute($params_usuario);
}


$updates_jugador = [];
$params_jugador = [":id" => $usuario_id];

if ($nombre !== null) {
    $updates_jugador[] = "nombre = :nombre";
    $params_jugador[":nombre"] = $nombre;
}

if ($apellido !== null) {
    $updates_jugador[] = "apellido = :apellido";
    $params_jugador[":apellido"] = $apellido;
}

if ($descripcion !== null) {
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
