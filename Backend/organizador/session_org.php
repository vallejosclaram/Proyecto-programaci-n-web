<?php
session_start();
include_once __DIR__ . '/../../Backend/conexion.php';

$organizador = null;
$id_organizador = null;

if (isset($_SESSION['id_organizador'])) {
    $id_organizador = $_SESSION['id_organizador'];
} else {
    
    $id_organizador = 3;
}

$query = "SELECT o.*, u.email 
          FROM organizador o
          INNER JOIN usuario u ON o.id_usuario = u.id_usuario
          WHERE o.id_organizador = $id_organizador";

$resultado = $conexion->query($query);

if ($resultado && $resultado->num_rows > 0) {
    $organizador = $resultado->fetch_assoc();
}
?>
