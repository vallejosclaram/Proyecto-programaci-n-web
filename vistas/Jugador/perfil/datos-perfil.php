<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

header("Content-Type: application/json");

if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

$usuario_id = $_SESSION["user"]["id"];

$sql = "
SELECT 
    u.id_usuario,
    u.email,
    j.nombre,
    j.apellido,
    j.biografia as descripcion
FROM usuario u
INNER JOIN jugador j ON j.id_usuario = u.id_usuario
WHERE u.id_usuario = :id
";

$stmt = $conn->prepare($sql);
$stmt->execute([":id" => $usuario_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($data);
?>