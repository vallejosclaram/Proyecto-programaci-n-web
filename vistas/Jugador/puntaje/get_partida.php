
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

$torneo_id = isset($_GET['torneo_id']) ? intval($_GET['torneo_id']) : 0;
if ($torneo_id <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "torneo_id inválido"]);
    exit;
}

try {
    // 1) obtener id_jugador de la usuaria
    $stJ = $conn->prepare("SELECT id_jugador FROM jugador WHERE id_usuario = :u LIMIT 1");
    $stJ->execute([":u" => $usuario_id]);
    $jug = $stJ->fetch(PDO::FETCH_ASSOC);

    if (!$jug) {
        throw new Exception("No existe jugadora en la tabla jugador");
    }
    $id_jugador = intval($jug["id_jugador"]);

    // 2) Determinar si participa como INDIVIDUAL o por EQUIPO y hallar su partida
    // Intento individual: una partida del torneo donde figura como jugador1 o jugador2
    $sqlPartidaInd = "
        SELECT p.*
        FROM partida p
        INNER JOIN torneo t ON t.id_torneo = p.id_torneo
        WHERE p.id_torneo = :torneo
          AND (p.id_jugador1 = :j OR p.id_jugador2 = :j)
        ORDER BY p.fecha, p.horario, p.id_partida
        LIMIT 1
    ";
    $stPI = $conn->prepare($sqlPartidaInd);
    $stPI->execute([":torneo" => $torneo_id, ":j" => $id_jugador]);
    $partidaInd = $stPI->fetch(PDO::FETCH_ASSOC);

    $tipo = null;
    $partida = null;
    $es_capitana = false;

    if ($partidaInd) {
        $tipo = "individual";
        $partida = $partidaInd;
        $es_capitana = true; // en individual, el propio jugador puede cargar
    } else {
        // Intento por equipo: hallar equipos del usuario
        $sqlEquiposUsuario = "
            SELECT e.id_equipo, e.id_usuario_capitan
            FROM miembros_equipo me
            INNER JOIN equipo e ON e.id_equipo = me.id_equipo
            WHERE me.id_usuario = :u
        ";
        $stEU = $conn->prepare($sqlEquiposUsuario);
        $stEU->execute([":u" => $usuario_id]);
        $equiposUsuario = $stEU->fetchAll(PDO::FETCH_ASSOC);

        if (!$equiposUsuario || count($equiposUsuario) === 0) {
            throw new Exception("No se encontró equipo del usuario en este torneo");
        }

        // Buscar partida del torneo donde participe alguno de esos equipos
        $idsEquipos = array_map(function($e){ return intval($e['id_equipo']); }, $equiposUsuario);
        $inPlaceholders = implode(',', array_fill(0, count($idsEquipos), '?'));

        $sqlPartidaEq = "
            SELECT p.*
            FROM partida p
            INNER JOIN torneo t ON t.id_torneo = p.id_torneo
            WHERE p.id_torneo = ?
              AND (
                    p.id_equipo1 IN ($inPlaceholders) OR
                    p.id_equipo2 IN ($inPlaceholders)
                  )
            ORDER BY p.fecha, p.horario, p.id_partida
            LIMIT 1
        ";
        $params = array_merge([$torneo_id], $idsEquipos, $idsEquipos);
        $stPE = $conn->prepare($sqlPartidaEq);
        $stPE->execute($params);
        $partidaEq = $stPE->fetch(PDO::FETCH_ASSOC);

        if (!$partidaEq) {
            throw new Exception("No se encontró partida del equipo del usuario en este torneo");
        }

        $tipo = "equipo";
        $partida = $partidaEq;

        // Verificar capitanía del equipo que coincide con la partida
        $equipoDelUsuarioEnPartida = null;
        foreach ($equiposUsuario as $e) {
            if (intval($partida['id_equipo1']) === intval($e['id_equipo']) ||
                intval($partida['id_equipo2']) === intval($e['id_equipo'])) {
                $equipoDelUsuarioEnPartida = $e;
                break;
            }
        }
        if (!$equipoDelUsuarioEnPartida) {
            throw new Exception("El equipo del usuario no coincide con la partida");
        }
        $es_capitana = (intval($equipoDelUsuarioEnPartida['id_usuario_capitan']) === $usuario_id);
    }

    $id_partida = intval($partida['id_partida']);

    // 3) Estado de reportes existentes (JOIN pedido)
    $sqlExistentes = "
        SELECT p.id_partida,
               SUM(CASE WHEN rr.resultado_a IS NOT NULL THEN 1 ELSE 0 END) AS cnt_a,
               SUM(CASE WHEN rr.resultado_b IS NOT NULL THEN 1 ELSE 0 END) AS cnt_b
        FROM partida p
        LEFT JOIN reporte_resultado rr ON rr.id_partida = p.id_partida
        WHERE p.id_partida = :p
        GROUP BY p.id_partida
    ";
    $stEX = $conn->prepare($sqlExistentes);
    $stEX->execute([":p" => $id_partida]);
    $ex = $stEX->fetch(PDO::FETCH_ASSOC);
    $cnt_a = $ex ? intval($ex['cnt_a']) : 0;
    $cnt_b = $ex ? intval($ex['cnt_b']) : 0;

    // 4) Determinar columna a usar según regla
    // Si hay datos en A => usar B; si no hay datos en A => usar A.
    // Si ambas tienen datos => nadie puede cargar más.
    $columnaObjetivo = null;
    if ($cnt_a > 0 && $cnt_b === 0) {
        $columnaObjetivo = "resultado_b";
    } elseif ($cnt_a === 0 && $cnt_b === 0) {
        $columnaObjetivo = "resultado_a";
    } elseif ($cnt_a > 0 && $cnt_b > 0) {
        $columnaObjetivo = null; // ambas llenas: no se puede cargar
    } else { // cnt_a === 0 && cnt_b > 0 (caso raro), entonces se usa A
        $columnaObjetivo = "resultado_a";
    }

    // 5) Bloqueo si la usuaria ya cargó (sin agregar columnas):
    // Usamos una marca en sesión por partida.
    $ya_cargado_sesion = !empty($_SESSION["puntaje_cargado_partida_" . $id_partida]);

    $respuesta = [
        "ok" => true,
        "tipo" => $tipo,
        "es_capitana" => $es_capitana,
        "id_partida" => $id_partida,
        "columna_objetivo" => $columnaObjetivo, // puede ser null si ambas tienen datos
        "existentes" => ["a" => $cnt_a, "b" => $cnt_b],
        "ya_cargado" => $ya_cargado_sesion,
    ];

    // Regla de deshabilitado: si ya cargó por sesión, o no es capitana en equipo, o ambas columnas llenas
    $respuesta["deshabilitar"] = ($ya_cargado_sesion || (!$es_capitana && $tipo === "equipo") || $columnaObjetivo === null);

    echo json_encode($respuesta);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
