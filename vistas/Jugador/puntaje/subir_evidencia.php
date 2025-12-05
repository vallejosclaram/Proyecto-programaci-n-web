
<?php
require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../includes/clases/permisos.php');
session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION["user"]["id"])) {
    http_response_code(401);
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}
$usuario_id = $_SESSION["user"]["id"];

if (!Permisos::tienePermiso("subir_puntaje", $usuario_id)) {
    http_response_code(403);
    echo json_encode(["error" => "No tenés permiso para subir evidencia"]);
    exit;
}

$torneo_id   = isset($_POST['torneo_id'])   ? intval($_POST['torneo_id'])   : 0;
$id_partida  = isset($_POST['id_partida'])  ? intval($_POST['id_partida'])  : 0;
$id_reporte  = isset($_POST['id_reporte'])  ? intval($_POST['id_reporte'])  : 0;

if ($torneo_id <= 0 || $id_partida <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "torneo_id e id_partida son requeridos"]);
    exit;
}

if (!isset($_FILES['evidencia'])) {
    http_response_code(400);
    echo json_encode(["error" => "No se recibió archivo de evidencia"]);
    exit;
}

try {
    // Validar partida ↔ torneo
    $sqlPartida = "
        SELECT p.*
        FROM partida p
        INNER JOIN torneo t ON t.id_torneo = p.id_torneo
        WHERE p.id_partida = :p AND p.id_torneo = :t
        LIMIT 1
    ";
    $stP = $conn->prepare($sqlPartida);
    $stP->execute([":p" => $id_partida, ":t" => $torneo_id]);
    $partida = $stP->fetch(PDO::FETCH_ASSOC);
    if (!$partida) throw new Exception("Partida no encontrada para el torneo");

    // Validar capitanía o jugadora
    if (!empty($partida['id_equipo1']) || !empty($partida['id_equipo2'])) {
        $sqlEquipoUsu = "
            SELECT e.id_equipo, e.id_usuario_capitan
            FROM miembros_equipo me
            INNER JOIN equipo e ON e.id_equipo = me.id_equipo
            WHERE me.id_usuario = :u
              AND (e.id_equipo = :e1 OR e.id_equipo = :e2)
            LIMIT 1
        ";
        $stEU = $conn->prepare($sqlEquipoUsu);
        $stEU->execute([
            ":u" => $usuario_id,
            ":e1" => $partida['id_equipo1'],
            ":e2" => $partida['id_equipo2'],
        ]);
        $equipo = $stEU->fetch(PDO::FETCH_ASSOC);
        if (!$equipo) throw new Exception("No pertenecés a un equipo de esta partida");
        if (intval($equipo['id_usuario_capitan']) !== $usuario_id) {
            throw new Exception("Debés ser capitana del equipo para subir evidencia");
        }
    } else {
        $stJ = $conn->prepare("SELECT id_jugador FROM jugador WHERE id_usuario = :u LIMIT 1");
        $stJ->execute([":u" => $usuario_id]);
        $jug = $stJ->fetch(PDO::FETCH_ASSOC);
        if (!$jug) throw new Exception("No existe jugadora vinculada al usuario");
        $id_jugador = intval($jug['id_jugador']);
        if (!(intval($partida['id_jugador1']) === $id_jugador || intval($partida['id_jugador2']) === $id_jugador)) {
            throw new Exception("Esta partida no pertenece a tu usuario");
        }
    }

    // Resolver id_reporte: si no vino, tomar el último (fila 15)
    if ($id_reporte <= 0) {
        $sqlUltimo = "
            SELECT rr.id_reporte
            FROM reporte_resultado rr
            WHERE rr.id_partida = :p
            ORDER BY rr.id_reporte DESC
            LIMIT 1
        ";
        $stU = $conn->prepare($sqlUltimo);
        $stU->execute([":p" => $id_partida]);
        $row = $stU->fetch(PDO::FETCH_ASSOC);
        if (!$row) throw new Exception("No hay reportes para esta partida");
        $id_reporte = intval($row['id_reporte']);
    }

    // Validar que el id_reporte corresponde a la misma partida
    $stVal = $conn->prepare("SELECT id_partida FROM reporte_resultado WHERE id_reporte = :r LIMIT 1");
    $stVal->execute([":r" => $id_reporte]);
    $rep = $stVal->fetch(PDO::FETCH_ASSOC);
    if (!$rep || intval($rep['id_partida']) !== $id_partida) {
        throw new Exception("El id_reporte no corresponde a la partida indicada");
    }

    // Validación del archivo
    $file = $_FILES['evidencia'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Error al subir archivo (código " . $file['error'] . ")");
    }

    // Limitar tamaño (8MB)
    $maxSize = 8 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        throw new Exception("La imagen supera el tamaño máximo de 8MB");
    }

    // Detectar tipo real del archivo (MIME) de forma segura
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) {
        throw new Exception("Formato no permitido. Usá JPG, PNG o WEBP");
    }
    $ext = $allowed[$mime];

    // Crear nombre/ubicación segura
    $uploadsDir = __DIR__ . '/../../uploads/evidencias';
    if (!is_dir($uploadsDir)) {
        if (!mkdir($uploadsDir, 0775, true)) {
            throw new Exception("No se pudo crear el directorio de evidencias");
        }
    }

    $filename = "reporte_" . $id_reporte . "_" . date("Ymd_His") . "_" . bin2hex(random_bytes(4)) . "." . $ext;
    $destPath = $uploadsDir . "/" . $filename;
    $relativePath = "uploads/evidencias/" . $filename; // esto se guarda en la DB

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        throw new Exception("No se pudo mover el archivo subido");
    }

    // Guardar en evidencia_reporte
    $sqlInsEv = "
        INSERT INTO evidencia_reporte (id_reporte, archivo, tipo, fecha_subida)
        VALUES (:r, :arch, :tipo, NOW())
    ";
    $stEv = $conn->prepare($sqlInsEv);
    $stEv->execute([
        ":r"    => $id_reporte,
        ":arch" => $relativePath,
        ":tipo" => $mime
    ]);

    $id_evidencia = intval($conn->lastInsertId());

    echo json_encode([
        "ok" => true,
        "mensaje" => "Evidencia subida",
        "id_evidencia" => $id_evidencia,
        "id_reporte" => $id_reporte,
        "archivo" => $relativePath,
        "tipo" => $mime
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
