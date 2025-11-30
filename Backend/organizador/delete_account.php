<?php
session_start();
include '../../vistas/connection.php';

$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
    header('Location: ../../vistas/auth/login.php');
    exit;
}

try {
    // Borrado permanente: eliminar organizador, torneos y relaciones, luego usuario
    $conn->beginTransaction();

    // Obtener id_organizador si existe
    $stmt = $conn->prepare("SELECT id_organizador FROM organizador WHERE id_usuario = ? LIMIT 1");
    $stmt->execute([$id_usuario]);
    $id_org = $stmt->fetchColumn();

    if ($id_org) {
        // Obtener torneos del organizador
        $stmt = $conn->prepare("SELECT id_torneo FROM torneo WHERE id_organizador = ?");
        $stmt->execute([$id_org]);
        $torneos = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($torneos)) {
            // Eliminar solicitudes relacionadas a esos torneos
            $in  = str_repeat('?,', count($torneos) - 1) . '?';
            $sql = "DELETE FROM solicitud_torneo WHERE id_torneo IN ($in)";
            $stmt = $conn->prepare($sql);
            $stmt->execute($torneos);

            // Eliminar inscripciones (torneo_jugador)
            $sql2 = "DELETE FROM torneo_jugador WHERE id_torneo IN ($in)";
            $stmt = $conn->prepare($sql2);
            $stmt->execute($torneos);
        }

        // Eliminar torneos del organizador
        $stmt = $conn->prepare("DELETE FROM torneo WHERE id_organizador = ?");
        $stmt->execute([$id_org]);
    }

    // Eliminar roles del usuario
    $stmt = $conn->prepare("DELETE FROM usuario_rol WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);

    // Eliminar fila de organizador si existe
    $stmt = $conn->prepare("DELETE FROM organizador WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);

    // Finalmente eliminar usuario
    $stmt = $conn->prepare("DELETE FROM usuario WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);

    $conn->commit();

    session_unset();
    session_destroy();

    header("Location: ../../vistas/inicio.php?deleted=1");
    exit;

} catch (Exception $e) {
    header("Location: ../../vistas/Organizador/perfil/configuracion.php?error=exception");
    exit;
}
?>
