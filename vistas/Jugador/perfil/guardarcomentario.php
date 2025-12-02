<?php
require_once("../../connection.php");
session_start();

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

$autor_id_usuario = $_SESSION["user"]["id"]; 


$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data["comentario"]) || empty($data["id_objetivo"])) {
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

$comentario = $data["comentario"];
$id_objetivo = intval($data["id_objetivo"]);

// Obtener nombre del autor desde tabla jugador
$sqlAutor = "SELECT nombre FROM jugador WHERE id_usuario = :id";
$stmtAutor = $conn->prepare($sqlAutor);
$stmtAutor->execute([":id" => $autor_id_usuario]);
$autor = $stmtAutor->fetchColumn();

if (!$autor) {
    echo json_encode(["error" => "No se encontró el jugador autor"]);
    exit;
}

// Insertar comentario
$sql = "
    INSERT INTO comentario (id_autor, id_objetivo, comentario, fecha)
    VALUES (:autor, :objetivo, :comentario, CURDATE())
";

$stmt = $conn->prepare($sql);
$ok = $stmt->execute([
    ":autor"     => $autor,
    ":objetivo"  => $id_objetivo,
    ":comentario"=> $comentario
]);

if ($ok) {
    echo json_encode(["ok" => true]);
} else {
    echo json_encode(["error" => "No se pudo guardar el comentario"]);
}
