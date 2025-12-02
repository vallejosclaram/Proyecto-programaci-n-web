<?php
include '../../vistas/connection.php';

$id_solicitud = $_GET['id_solicitud'] ?? null;

if (!$id_solicitud) exit("Faltan datos");

$conn->prepare("DELETE FROM solicitud_torneo WHERE id_solicitud_torneo=?")
     ->execute([$id_solicitud]);

header("Location: http://localhost/Proyecto-programaci-n-web/vistas/Organizador/solicitudes.php?rechazada=1");
exit;
?>

