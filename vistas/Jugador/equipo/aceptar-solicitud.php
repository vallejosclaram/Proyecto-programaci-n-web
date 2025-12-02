<?php
require_once("../../connection.php");
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$idSolicitud = $data["solicitud"] ?? null;

if (!$idSolicitud) {
    echo json_encode(["error" => "Solicitud no recibida"]);
    exit;
}

$sql = "SELECT id_usuario, id_equipo 
        FROM solicitud_equipo 
        WHERE id_solicitud = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([":id" => $idSolicitud]);
$sol = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sol) {
    echo json_encode(["error" => "La solicitud no existe"]);
    exit;
}

$idUsuario = $sol["id_usuario"];
$idEquipo  = $sol["id_equipo"];

try {
    $conn->beginTransaction();

    // Insertar el usuario como miembro
    $sqlInsert = "INSERT INTO miembros_equipo (id_equipo, id_usuario, fecha_union)
                  VALUES (:eq, :usu, NOW())";

    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->execute([
        ":eq" => $idEquipo,
        ":usu" => $idUsuario
    ]);

    // Borrar la solicitud aceptada
    $sqlDelete = "DELETE FROM solicitud_equipo WHERE id_solicitud = :id";
    $stmtDel = $conn->prepare($sqlDelete);
    $stmtDel->execute([":id" => $idSolicitud]);

    $conn->commit();

    echo json_encode(["ok" => true]);

} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(["error" => $e->getMessage()]);
}
