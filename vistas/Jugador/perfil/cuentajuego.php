<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

header("Content-Type: application/json; charset=utf-8");

// Verificar sesión
if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(['success' => false, 'error' => 'No hay usuario logueado']);
    exit;
}

$usuario_id = $_SESSION["user"]["id"];

// Recibir JSON de JS
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!$data || empty($data["cuenta_id"])) {
    echo json_encode(['success' => false, 'error' => 'No se recibió la cuenta de juego']);
    exit;
}

$cuenta_id = trim($data["cuenta_id"]); // Puede ser puuid, steamId o nickname

try {
    // Aseguramos que PDO muestre errores reales
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        UPDATE jugador
        SET id_cuentajuego = :cuenta
        WHERE id_usuario = :usuario
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":cuenta"  => $cuenta_id,
        ":usuario" => $usuario_id
    ]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
