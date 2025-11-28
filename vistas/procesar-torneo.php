<?php
include '../../connection.php';
session_start();

// Validar sesión
$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
  echo "Error: Usuario no autenticado.";
  exit;
}

// Obtener ID del organizador (PDO)
$stmtOrg = $conn->prepare("SELECT id_organizador FROM organizador WHERE id_usuario = ?");
$stmtOrg->execute([$id_usuario]);
$org = $stmtOrg->fetch(PDO::FETCH_ASSOC);
$id_organizador = $org['id_organizador'] ?? null;

if (!$id_organizador) {
  echo "Error: Organizador no encontrado.";
  exit;
}

// Recibir datos del formulario
$nombre = $_POST['nombre'] ?? '';
$juego = $_POST['juego'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$fechaInicio = $_POST['fechaInscripcion'] ?? '';
$estado = $_POST['estado'] ?? '';

// Buscar ID del juego (PDO)
$stmtJuego = $conn->prepare("SELECT id_juego FROM juego WHERE nombre = ?");
$stmtJuego->execute([$juego]);
$j = $stmtJuego->fetch(PDO::FETCH_ASSOC);
$id_juego = $j['id_juego'] ?? null;

if (!$id_juego) {
  echo "Error: Juego no encontrado.";
  exit;
}

// Convertir tipo y estado a IDs
$id_tipo = ($tipo == "individual") ? 1 : 2;
$id_estado = ($estado == "abierto") ? 1 : 2;

// Calcular fecha de fin (+7 días)
$fechaFin = date('Y-m-d', strtotime($fechaInicio . ' +7 days'));

$descripcion = "Torneo creado por organizador";

// Insertar torneo (PDO)
$stmtInsert = $conn->prepare("
  INSERT INTO torneo 
  (id_organizador, id_juego, nombre, descripcion, fecha_inicio, fecha_fin, id_estado, id_tipo)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$success = $stmtInsert->execute([
  $id_organizador,
  $id_juego,
  $nombre,
  $descripcion,
  $fechaInicio,
  $fechaFin,
  $id_estado,
  $id_tipo
]);

if ($success) {
  echo "Torneo creado con éxito";
} else {
  echo "Error al crear torneo";
}
?>
