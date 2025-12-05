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

$idUsuario = $sol["id_usuario"];  // usuario que pidió unirse
$idEquipo  = $sol["id_equipo"];

try {
    $conn->beginTransaction();

    // 1) Insertar el usuario como miembro del equipo
    $sqlInsert = "INSERT INTO miembros_equipo (id_equipo, id_usuario, fecha_union)
                  VALUES (:eq, :usu, NOW())";

    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->execute([
        ":eq"  => $idEquipo,
        ":usu" => $idUsuario
    ]);

    // 2) Borrar la solicitud aceptada
    $sqlDelete = "DELETE FROM solicitud_equipo WHERE id_solicitud = :id";
    $stmtDel = $conn->prepare($sqlDelete);
    $stmtDel->execute([":id" => $idSolicitud]);

    // 3) Buscar el capitán del equipo
    $sqlCap = "SELECT id_usuario_capitan FROM equipo WHERE id_equipo = :eq LIMIT 1";
    $stmtCap = $conn->prepare($sqlCap);
    $stmtCap->execute([":eq" => $idEquipo]);
    $cap = $stmtCap->fetch(PDO::FETCH_ASSOC);

    if ($cap && isset($cap["id_usuario_capitan"])) {

        $idCapitan = $cap["id_usuario_capitan"];

        // 4) Enviar notificación al usuario aceptado
        $mensaje = "Tu solicitud para unirte al equipo fue aceptada.";

        $sqlNotif = "INSERT INTO notificacion (id_receptor, id_emisor, notificacion, fecha)
                     VALUES (:receptor, :emisor, :msg, NOW())";

        $stmtNotif = $conn->prepare($sqlNotif);
        $stmtNotif->execute([
            ":receptor" => $idUsuario,   // quien recibe la buena noticia
            ":emisor"   => $idCapitan,   // el capitán que aceptó
            ":msg"      => $mensaje
        ]);
    }

    $conn->commit();
    echo json_encode(["ok" => true]);

} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(["error" => $e->getMessage()]);
}
