<?php
session_start();
require_once(__DIR__ . '/../connection.php');
require_once(__DIR__ . '/../includes/clases/permisos.php');

header('Content-Type: application/json; charset=UTF-8');

// Validar sesión y rol
if (empty($_SESSION['admin']['id'])) {
    echo json_encode(['success' => false, 'error' => 'No hay usuario administrador logueado']);
    exit;
}
$id_usuario = $_SESSION['admin']['id'];
$rol = $_SESSION['admin']['rol'] ?? null;

if ($rol != 1) {
    echo json_encode(['success' => false, 'error' => 'Acceso restringido']);
    exit;
}

// Leer acción
$input = json_decode(file_get_contents('php://input'), true);
$accion = $input['accion'] ?? null;

if (!$accion) {
    // Listado de denuncias
    $sql = "SELECT d.id_denuncia, d.descripcion, d.fecha_creacion,
                   d.id_reportador, d.id_reportado, d.id_organizador,
                   r.email AS reportador,
                   rep.email AS reportado,
                   rep.bloqueado_hasta AS bloqueado_hasta,
                   o.nombre AS organizador
            FROM denuncias d
            LEFT JOIN usuario r ON r.id_usuario = d.id_reportador
            LEFT JOIN usuario rep ON rep.id_usuario = d.id_reportado
            LEFT JOIN torneo o ON o.id_torneo = d.id_organizador
            ORDER BY d.fecha_creacion DESC";

    try {
        $stmt = $conn->query($sql);
        $denuncias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Separar usuarios y torneos
        $usuario = array_filter($denuncias, fn($d) => !empty($d['id_reportado']));
        $torneo  = array_filter($denuncias, fn($d) => !empty($d['id_organizador']));

        echo json_encode([
            'success' => true,
            'usuario' => array_values($usuario),
            'torneo'  => array_values($torneo)
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Error en la consulta: '.$e->getMessage()]);
    }
    exit;
}

switch ($accion) {
    case 'bloquear_usuario':
        if (!Permisos::tienePermiso('Bloquear usuario', $id_usuario)) {
            echo json_encode(['success' => false, 'error' => 'No tenés permiso para bloquear usuarios']);
            exit;
        }
        $id_reportado = $input['id_reportado'] ?? null;
        if (!$id_reportado) {
            echo json_encode(['success' => false, 'error' => 'Usuario a bloquear no especificado']);
            exit;
        }
        $stmt = $conn->prepare("UPDATE usuario 
                                SET id_estado = 3, bloqueado_hasta = DATE_ADD(NOW(), INTERVAL 15 DAY) 
                                WHERE id_usuario = ?");
        $stmt->execute([$id_reportado]);
        echo json_encode(['success' => true, 'message' => 'Usuario bloqueado (estado = 3) por 15 días']);
        break;

    case 'bloquear_torneo':
        if (!Permisos::tienePermiso('Bloquear torneo', $id_usuario)) {
            echo json_encode(['success' => false, 'error' => 'No tenés permiso para bloquear torneos']);
            exit;
        }
        $id_torneo = $input['id_torneo'] ?? null;
        if (!$id_torneo) {
            echo json_encode(['success' => false, 'error' => 'Torneo a bloquear no especificado']);
            exit;
        }
        $stmt = $conn->prepare("UPDATE torneo 
                                SET id_estado = 3 
                                WHERE id_torneo = ?");
        $stmt->execute([$id_torneo]);
        echo json_encode(['success' => true, 'message' => 'Torneo bloqueado (estado = 3)']);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acción desconocida']);
}
