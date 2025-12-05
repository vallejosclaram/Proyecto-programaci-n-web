<?php
require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../includes/clases/permisos.php'); 
session_start();


if (!isset($_SESSION["user"]["id"])) {
    echo json_encode(["error" => "No hay usuario logueado"]);
    exit;
}

$usuario_id = $_SESSION["user"]["id"];

if (!Permisos::tienePermiso("Crear equipo", $usuario_id)) {
    echo json_encode(["error" => "No tenés permiso para crear un equipo"]);
    exit;
}


$input = file_get_contents("php://input");
$data = json_decode($input, true);

$nombre = trim($data['nombre'] ?? '');
$cant = trim($data['cantidad'] ?? '');
$id_juego = $data['juego'] ?? null;
$desc = trim($data['descripcion'] ?? '');




if (!Permisos::tienePermiso("Crear equipo", $usuario_id)) {
    echo json_encode(["error" => "No tenés permiso para crear un equipo"]);
    exit;
}


$errors = [];
if ($nombre === '') {
    $errors['nombre'] = 'El nombre no puede estar vacío';
}
if ($cant === '') {
    $errors['cant'] = 'La cantidad no puede estar vacía';
} elseif ($cant > 10) {
    $errors['cant'] = 'La cantidad no puede ser mayor a 10';
}

if (!empty($errors)) {
    echo json_encode(['error' => $errors]);
    exit;
}

try {
    
    $sql = "SELECT id_jugador FROM jugador WHERE id_usuario = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([":id" => $usuario_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['error' => "No se encontró el jugador asociado."]);
        exit;
    }


    
    $sql = "INSERT INTO equipo (nombre, id_capitan_usuario, id_juego, descripcion, estado)
            SELECT :n, :c, :j, :d, id_estado 
            FROM estado_equipo 
            WHERE descripcion = 'activo'
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":n" => $nombre,
        ":c" => $usuario_id,
        ":j" => $id_juego,
        ":d" => $desc
    ]);

   
    $nuevo_equipo_id = $conn->lastInsertId();

    
    $sql = "INSERT INTO miembros_equipo (id_equipo, id_usuario, fecha_union)
            VALUES (:e, :u, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ":e" => $nuevo_equipo_id,
        ":u" => $usuario_id
    ]);

    $sql = "
        SELECT e.id_equipo, e.nombre, e.id_juego, e.descripcion, ee.descripcion AS estado
        FROM equipo e
        JOIN estado_equipo ee ON e.estado = ee.id_estado
        WHERE e.id_equipo = :id
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([":id" => $nuevo_equipo_id]);
    $equipo = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(["mensaje" => "Equipo creado con éxito", "equipo" => $equipo]);

} catch (Exception $e) {
    // $e->getMessage()
    echo json_encode(["error" => $e->getMessage()]);
}
?>
