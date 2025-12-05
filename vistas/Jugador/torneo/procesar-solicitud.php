<?php
session_start();
require_once('../../connection.php');
require_once(__DIR__ . '/../../includes/clases/permisos.php'); 

$input = file_get_contents("php://input");
$data = json_decode($input, true);

$usuario_id = $_SESSION["user"]["id"];

$id_torneo = $data['id_torneo'] ?? null;

if (!Permisos::tienePermiso("solicitar_torneo", $usuario_id)) {
    echo json_encode(["error" => "No tenés permiso para unirte a un torneo"]);
    exit;
}

if (!$id_torneo) {
    echo json_encode(['error'=>'Falta id_torneo']);
    exit;
}

$stmt = $conn->prepare("SELECT id_tipo FROM torneo WHERE id_torneo = :id_torneo");
$stmt->bindValue(':id_torneo', $id_torneo);
$stmt->execute();
$torneo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$torneo) {
    echo json_encode(['success'=>false,'error'=>'Torneo no encontrado']);
    exit;
}

$id_equipo = null;
if ($torneo['id_tipo'] == 2) { 
    
    $stmt = $conn->prepare("SELECT id_equipo FROM miembros_equipo WHERE id_usuario = :id_usuario LIMIT 1");
    $stmt->bindValue(':id_usuario', $usuario_id);
    $stmt->execute();
    $equipo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$equipo) {
        echo json_encode(['error'=>'No pertenecés a ningún equipo']);
        exit;
    }
    $id_equipo = $equipo['id_equipo'];
}

try {
    // Insertar solicitud
    $stmt = $conn->prepare("
        INSERT INTO solicitud_torneo (id_equipo, id_usuario, id_torneo, fecha_solicitud)
        VALUES (:id_equipo, :id_usuario, :id_torneo, NOW())
    ");
    $stmt->bindValue(':id_equipo', $id_equipo);
    $stmt->bindValue(':id_usuario', $usuario_id);
    $stmt->bindValue(':id_torneo', $id_torneo);
    $stmt->execute();

    

    // Buscar a la capitana
    $stmtCap = $conn->prepare("
        SELECT id_capitan_usuario 
        FROM equipo 
        WHERE id_equipo = :id_equipo
        LIMIT 1
    ");
    $stmtCap->bindValue(':id_equipo', $id_equipo);
    $stmtCap->execute();
    $capitana = $stmtCap->fetch(PDO::FETCH_ASSOC);

    if ($capitana) {
        $capitana_id = $capitana['id_capitan_usuario'];

        // Obtener nombre del torneo
$stmtTor = $conn->prepare("
    SELECT nombre 
    FROM torneo 
    WHERE id_torneo = :id_torneo
    LIMIT 1
");
$stmtTor->bindValue(':id_torneo', $id_torneo);
$stmtTor->execute();
$torData = $stmtTor->fetch(PDO::FETCH_ASSOC);

$nombreTorneo = $torData ? $torData['nombre'] : 'Torneo desconocido';


$stmtUser = $conn->prepare("
    SELECT nombre, apellido
    FROM usuario
    WHERE id_usuario = :id_usuario
    LIMIT 1
");
$stmtUser->bindValue(':id_usuario', $usuario_id);
$stmtUser->execute();
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

$nombreSolicitante = $userData 
    ? $userData['nombre'] . ' ' . $userData['apellido']
    : 'Usuario desconocido';


$mensaje = "🔔 Nueva solicitud para *$nombreTorneo* enviada por *$nombreSolicitante*.";

        
        $stmtNotif = $conn->prepare("
            INSERT INTO notificaciones (id_receptor, id_emisor notificacion, fecha)
            VALUES (:id_usuario, :mensaje, NOW())
        ");
        $stmtNotif->bindValue(':id_receptor', $capitana_id);
        $stmtNotif->bindValue(':id_emisor', $usuario_id);
        $stmtNotif->bindValue(':notififcacion', $mensaje);
        $stmtNotif->execute();
    }

    echo json_encode(['success'=>true]);

} catch (Exception $e) {
    echo json_encode(['success'=>false,'error'=>$e->getMessage()]);
}
