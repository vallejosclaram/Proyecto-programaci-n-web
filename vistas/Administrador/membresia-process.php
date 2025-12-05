<?php
session_start();
require_once(__DIR__ . '/../connection.php');
require_once(__DIR__ . '/../includes/clases/permisos.php');

header('Content-Type: application/json');

if (empty($_SESSION['admin']['id']) || ($_SESSION['admin']['rol'] ?? null) != 1) {
    echo json_encode(['success' => false, 'error' => 'Acceso restringido']);
    exit;
}

$id_solicitud = $_POST['id_solicitud'] ?? null;
$accion = $_POST['accion'] ?? null;

if (!$id_solicitud || !$accion) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit;
}

try {
    $conn->beginTransaction();

    // Buscar la solicitud en solicitud_membresia
    $stmt = $conn->prepare("SELECT id_usuario, membresia FROM solicitud_membresia WHERE id_membresia = ?");
    $stmt->execute([$id_solicitud]);
    $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$solicitud) {
        throw new Exception("Solicitud no encontrada");
    }

    $id_usuario_solicitante = (int)$solicitud['id_usuario'];
    $membresia_nombre = $solicitud['membresia'];

    if ($accion === "aceptar") {
        // Buscar id real de la membresía
        $stmt = $conn->prepare("SELECT id_membresia FROM membresia WHERE descripcion = ?");
        $stmt->execute([$membresia_nombre]);
        $m = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$m) {
            throw new Exception("Membresía no válida");
        }

        $id_membresia_real = (int)$m['id_membresia'];

        // Actualizar jugador con la membresía elegida
        $stmt = $conn->prepare("UPDATE jugador SET id_membresia = ? WHERE id_usuario = ?");
        $stmt->execute([$id_membresia_real, $id_usuario_solicitante]);

        // Eliminar la solicitud
        $stmt = $conn->prepare("DELETE FROM solicitud_membresia WHERE id_membresia = ?");
        $stmt->execute([$id_solicitud]);

    } elseif ($accion === "rechazar") {
        // Eliminar la solicitud sin tocar jugador
        $stmt = $conn->prepare("DELETE FROM solicitud_membresia WHERE id_membresia = ?");
        $stmt->execute([$id_solicitud]);

    } else {
        throw new Exception("Acción no válida");
    }

    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
