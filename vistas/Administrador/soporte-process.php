<?php
session_start();
require_once(__DIR__ . '/../connection.php');
require_once(__DIR__ . '/../includes/clases/permisos.php');

header('Content-Type: application/json; charset=UTF-8');

if (empty($_SESSION['admin']['id'])) {
    echo json_encode(['success' => false, 'error' => 'No hay usuario administrador logueado']);
    exit;
}

$id_admin = $_SESSION['admin']['id'];
$rol = $_SESSION['admin']['rol'] ?? null;

if ($rol != 1) {
    echo json_encode(['success' => false, 'error' => 'Acceso restringido']);
    exit;
}

if (!Permisos::tienePermiso('responder_tickets', $id_admin)) {
    echo json_encode(['success' => false, 'error' => 'No tenés permiso para gestionar tickets']);
    exit;
}

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);
if (!is_array($input)) {
    $input = [];
}

$accion = $input['accion'] ?? null;

if (!$accion) {
    $search      = isset($input['search']) ? trim($input['search']) : null;
    $estado      = $input['estado'] ?? null; 
    $fechaDesde  = $input['fecha_desde'] ?? null;
    $fechaHasta  = $input['fecha_hasta'] ?? null;

    $sql = "SELECT t.id_ticket, t.id_usuario, t.asunto, t.descripcion, t.fecha_creacion,
                   u.email AS usuario,
                   EXISTS(SELECT 1 FROM respuesta_ticket r WHERE r.id_ticket = t.id_ticket) AS respondido
            FROM ticket t
            INNER JOIN usuario u ON u.id_usuario = t.id_usuario
            WHERE 1=1";
    $params = [];

    if ($search) {
        $sql .= " AND (t.asunto LIKE ? OR t.descripcion LIKE ? OR u.email LIKE ?)";
        $like = "%$search%";
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    if ($estado === 'pendiente') {
        $sql .= " AND NOT EXISTS(SELECT 1 FROM respuesta_ticket r2 WHERE r2.id_ticket = t.id_ticket)";
    } elseif ($estado === 'respondido') {
        $sql .= " AND EXISTS(SELECT 1 FROM respuesta_ticket r3 WHERE r3.id_ticket = t.id_ticket)";
    }

    if ($fechaDesde) {
        $sql .= " AND DATE(t.fecha_creacion) >= ?";
        $params[] = $fechaDesde;
    }

    if ($fechaHasta) {
        $sql .= " AND DATE(t.fecha_creacion) <= ?";
        $params[] = $fechaHasta;
    }

    $sql .= " ORDER BY t.fecha_creacion DESC";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($tickets) {
            $ids = array_column($tickets, 'id_ticket');
            $respuestas = obtenerRespuestas($conn, $ids);

            $tickets = array_map(function ($ticket) use ($respuestas) {
                $ticketId = $ticket['id_ticket'];
                $ticket['respondido'] = (bool)$ticket['respondido'];
                $ticket['respuestas'] = $respuestas[$ticketId] ?? [];
                return $ticket;
            }, $tickets);
        }

        echo json_encode([
            'success' => true,
            'tickets' => $tickets
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Error al obtener tickets: ' . $e->getMessage()]);
    }
    exit;
}

switch ($accion) {
    case 'responder_ticket':
        $id_ticket = $input['id_ticket'] ?? null;
        $respuesta = trim($input['respuesta'] ?? '');

        if (!$id_ticket || $respuesta === '') {
            echo json_encode(['success' => false, 'error' => 'Ticket o respuesta no especificados']);
            exit;
        }

        try {
            $stmt = $conn->prepare("SELECT id_ticket FROM ticket WHERE id_ticket = ?");
            $stmt->execute([$id_ticket]);
            if (!$stmt->fetchColumn()) {
                echo json_encode(['success' => false, 'error' => 'El ticket no existe']);
                exit;
            }

            $stmt = $conn->prepare("INSERT INTO respuesta_ticket (id_ticket, id_admin, respuesta) VALUES (?, ?, ?)");
            $stmt->execute([$id_ticket, $id_admin, $respuesta]);

            echo json_encode(['success' => true, 'message' => 'Respuesta publicada correctamente']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error al publicar respuesta: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acción desconocida']);
}

function obtenerRespuestas(PDO $conn, array $ids)
{
    if (empty($ids)) {
        return [];
    }
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "SELECT r.id_ticket, r.respuesta, r.fecha_respuesta,
                   COALESCE(a.nombre, 'Soporte') AS admin
            FROM respuesta_ticket r
            LEFT JOIN administrador a ON a.id_admin = r.id_admin
            WHERE r.id_ticket IN ($placeholders)
            ORDER BY r.fecha_respuesta ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($ids);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $agrupado = [];
    foreach ($rows as $row) {
        $agrupado[$row['id_ticket']][] = [
            'respuesta' => $row['respuesta'],
            'fecha_respuesta' => $row['fecha_respuesta'],
            'admin' => $row['admin']
        ];
    }
    return $agrupado;
}

