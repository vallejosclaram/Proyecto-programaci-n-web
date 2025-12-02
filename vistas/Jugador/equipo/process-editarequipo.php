<?php
require_once("../../connection.php");
session_start();

if (!isset($_SESSION["user"]["id"])) {
    die("Error: no hay usuario logueado.");
}

$usuario_id = $_SESSION["user"]["id"];

$data = json_decode(file_get_contents("php://input"), true);
$idEquipo = $data["equipo"];
$idUsuario = $data["usuario"];


$sql_cap = "SELECT id_capitan_usuario 
            FROM equipo 
            WHERE id_equipo = :eq";

$stmt_cap = $conn->prepare($sql_cap);
$stmt_cap->execute([":eq" => $idEquipo]);
$capitan_actual = $stmt_cap->fetch(PDO::FETCH_ASSOC);

if ($capitan_actual["id_capitan_usuario"] != $usuario_id) {
    echo json_encode(["error" => "No sos la capitana, no podés cambiar el capitán"]);
    exit;
}

try {
    $sql = "UPDATE equipo 
            SET id_capitan_usuario = :usu
            WHERE id_equipo = :eq";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":usu" => $idUsuario,
        ":eq"  => $idEquipo
    ]);

    echo json_encode(["ok" => true]);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
