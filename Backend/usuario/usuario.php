<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([]);
    exit;
}

echo json_encode([
    "id_usuario" => $_SESSION['id_usuario'],
    "rol" => $_SESSION['rol'],
    "nombre" => $_SESSION['nombre'] ?? '',
    "apellido" => $_SESSION['apellido'] ?? ''
]);
?>
