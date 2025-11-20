<?php
require_once(__DIR__ . '/../../connection.php');
session_start();

if (!isset($_SESSION["usuario_id"])) {
    die("Error: no hay usuario logueado.");
}

$nombre = $_POST["nombreEquipo"];
$cant = $_POST["cantidadJugadores"];
$id_juego = $_POST["juegoEquipo"];
$desc = $_POST["descripcionEquipo"];


$id_usuario = $_SESSION["usuario_id"];


$sql = "SELECT id_jugador FROM jugador WHERE id_usuario = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([":id" => $id_usuario]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$id_capitan = $row["id_jugador"];//para empezar es el creador del equipo

$sql = "INSERT INTO equipo (nombre, id_capitan_usuario, id_juego, descripcion, estado)
        VALUES (:n, :c, :j, :d, :1)";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ":n" => $nombre,
    ":c" => $id_capitan,
    ":j" => $id_juego,
    ":d" => $desc
]);

header("Location: crear-equipo.php?ok=1");
exit;
