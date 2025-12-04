<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["ok" => false, "error" => "No logueado"]);
    exit;
}

$user = $_SESSION["user"]["id"];

$sql = $conn->prepare("SELECT id_cuentajuego FROM jugador WHERE id_usuario = ?");
$sql->execute([$user]);
$row = $sql->fetch(PDO::FETCH_ASSOC);


if (!$row || !$row["id_cuentajuego"]) {
    echo json_encode([
        "ok" => false,
        "id_cuentajuego" => null
    ]);
} else {
    echo json_encode([
        "ok" => true,
        "id_cuentajuego" => $row["id_cuentajuego"]
    ]);
}
