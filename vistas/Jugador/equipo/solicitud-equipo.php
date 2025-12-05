<?php
require_once("../../connection.php");
session_start();

if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

$usuario_id = $_SESSION["user"]["id"];
$data = json_decode(file_get_contents("php://input"), true);
$idEquipo = $data["equipo"] ?? null;

if (!$idEquipo) {
    echo json_encode(["error" => "Equipo no recibido"]);
    exit;
}

// verificar que no haya una solicitud previa
$sql_check = "SELECT 1 FROM solicitud_equipo WHERE id_usuario = :u AND id_equipo = :e";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->execute([":u" => $usuario_id, ":e" => $idEquipo]);

if ($stmt_check->fetch()) {
    echo json_encode(["error" => "Ya enviaste una solicitud a este equipo"]);
    exit;
}

try {

    // 1) Insertar solicitud
    $sql = "INSERT INTO solicitud_equipo (id_usuario, id_equipo, fecha_solicitud)
            VALUES (:u, :e, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":u" => $usuario_id,
        ":e" => $idEquipo
    ]);

    // 2) Buscar capitán usando id_usuario_capitan
    $sqlCap = "SELECT id_usuario_capitan FROM equipo WHERE id_equipo = :eq LIMIT 1";
    $stmtCap = $conn->prepare($sqlCap);
    $stmtCap->execute([":eq" => $idEquipo]);
    $cap = $stmtCap->fetch(PDO::FETCH_ASSOC);

    if ($cap && isset($cap["id_usuario_capitan"])) {

        $idCapitan = $cap["id_usuario_capitan"];

        // 3) Insertar notificación
        $mensaje = "El usuario $usuario_id solicitó unirse a tu equipo.";

        $sqlNotif = "INSERT INTO notificacion (id_receptor, id_emisor, notificacion, fecha)
                     VALUES (:rec, :emi, :msg, NOW())";

        $stmtNotif = $conn->prepare($sqlNotif);
        $stmtNotif->execute([
            ":rec" => $idCapitan,
            ":emi" => $usuario_id,
            ":msg" => $mensaje
        ]);
    }

    echo json_encode(["ok" => true]);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
