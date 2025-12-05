
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
    echo json_encode(["error" => "No tenés permiso para subir puntaje"]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$torneo_id = isset($input['torneo_id']) ? intval($input['torneo_id']) : 0;
$id_partida = isset($input['id_partida']) ? intval($input['id_partida']) : 0;
$resultados = isset($input['resultados']) && is_array($input['resultados']) ? $input['resultados'] : null;

if ($torneo_id <= 0 || $id_partida <= 0 || !$resultados || count($resultados) !== 15) {
    http_response_code(400);
    echo json_encode(["error" => "Datos de entrada inválidos"]);
    exit;
}

if (!empty($_SESSION["puntaje_cargado_partida_" . $id_partida])) {
    http_response_code(409);
    echo json_encode(["error" => "Ya cargaste resultados para esta partida"]);
    exit;
}

try {
    // Validar partida ↔ torneo (JOIN pedido)
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
    if (!$partida) {
        throw new Exception("Partida no encontrada para el torneo");
    }

    // Validar capitanía o jugador correspondiente
    if (!empty($partida['id_equipo1']) || !empty($partida['id_equipo2'])) {
        // Equipo
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
            throw new Exception("Debés ser capitana del equipo para cargar resultados");
        }
    } else {
        // Individual
        $stJ = $conn->prepare("SELECT id_jugador FROM jugador WHERE id_usuario = :u LIMIT 1");
        $stJ->execute([":u" => $usuario_id]);
        $jug = $stJ->fetch(PDO::FETCH_ASSOC);
        if (!$jug) throw new Exception("No existe jugadora vinculada al usuario");
        $id_jugador = intval($jug['id_jugador']);
        if (!(intval($partida['id_jugador1']) === $id_jugador || intval($partida['id_jugador2']) === $id_jugador)) {
            throw new Exception("Esta partida no pertenece a tu usuario");
        }
    }

    // Elegir columna (regla A/B)
    $sqlExistentes = "
        SELECT
          SUM(CASE WHEN resultado_a IS NOT NULL THEN 1 ELSE 0 END) AS cnt_a,
          SUM(CASE WHEN resultado_b IS NOT NULL THEN 1 ELSE 0 END) AS cnt_b
        FROM reporte_resultado
        WHERE id_partida = :p
    ";
    $stEX = $conn->prepare($sqlExistentes);
    $stEX->execute([":p" => $id_partida]);
    $ex = $stEX->fetch(PDO::FETCH_ASSOC);
    $cnt_a = $ex ? intval($ex['cnt_a']) : 0;
    $cnt_b = $ex ? intval($ex['cnt_b']) : 0;

    // col = "b" => escribe en resultado_a; col = "a" => escribe en resultado_b
    $col = null;
    if ($cnt_a > 0 && $cnt_b === 0) $col = "a";
    elseif ($cnt_a === 0 && $cnt_b === 0) $col = "b";
    elseif ($cnt_a > 0 && $cnt_b > 0) throw new Exception("Ya se cargaron resultados en ambas columnas para esta partida");
    else $col = "b"; // cnt_a == 0 && cnt_b > 0

    $conn->beginTransaction();

    $sqlInsert = "
        INSERT INTO reporte_resultado (id_partida, resultado_a, resultado_b, fecha_reporte, estado)
        VALUES (:p, :ra, :rb, NOW(), :estado)
    ";
    $stIns = $conn->prepare($sqlInsert);

    $id_reporte_15 = null;
    $i = 0;
    foreach ($resultados as $val) {
        $i++;
        $v = ($val ? 1 : 0);
        $params = [
            ":p" => $id_partida,
            ":ra" => ($col === "b" ? $v : null),
            ":rb" => ($col === "a" ? $v : null),
            ":estado" => "cargado",
        ];
        $stIns->execute($params);
        // capturamos el id_reporte de cada fila; el último será el de la ronda 15
        $lastId = $conn->lastInsertId();
        if ($i === 15) $id_reporte_15 = intval($lastId);
    }

    $conn->commit();
    $_SESSION["puntaje_cargado_partida_" . $id_partida] = true;

    echo json_encode([
        "ok" => true,
        "mensaje" => "Resultados guardados",
        "rondas" => count($resultados),
        "id_reporte_15" => $id_reporte_15
    ]);

} catch (Exception $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
