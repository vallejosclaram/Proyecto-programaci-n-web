<?php
require_once(__DIR__ . '/../../connection.php');
require_once(__DIR__ . '/../../includes/clases/permisos.php');
session_start();

if (!isset($_SESSION["user"]["id"])) {
    die("Error: no hay usuario logueado.");
}

$id_usuario = $_SESSION["user"]["id"];


/*if (!Permisos::tienePermiso("Ver notificaciones", $id_usuario)) {
    echo json_encode(["error" => "No tenés permiso para ver tus notificaciones"]);
    exit;
}*/


$stmt = $conn->prepare("
    SELECT n.*, u.nombre, u.apellido
    FROM notificacion n
    LEFT JOIN usuario u ON u.id_usuario = n.id_emisor
    WHERE n.id_receptor = :id_receptor
    ORDER BY n.fecha DESC
");
$stmt->bindValue(':id_receptor', $id_usuario);
$stmt->execute();
$notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Notificaciones</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

<?php require_once __DIR__ . '/../../includes/dashboardJugador.php'; ?>

<div class="banner-container">
    <img src="../img/videojuegos.jpg" alt="Banner" class="banner-img" />
</div>

<div class="container d-flex align-items-center justify-content-center">
    
    <div class="hero-card" style="width: 650px;">

        <div class="text-center mb-2">
            <h2>Notificaciones</h2>
            <p class="text-muted small">Mensajes importantes sobre tu actividad en la plataforma.</p>
        </div>

        <?php if (empty($notificaciones)): ?>
            <div class="alert alert-secondary text-center mt-4">
                No tenés notificaciones por ahora.
            </div>
        <?php else: ?>
            <div class="list-group">

                <?php foreach ($notificaciones as $n): 
                    $emisor = $n["nombre"] 
                        ? htmlspecialchars($n["nombre"] . ' ' . $n["apellido"]) 
                        : "Sistema";
                ?>
                
                <div class="list-group-item bg-dark text-white mb-2 rounded p-3 shadow-sm">
                    <div class="d-flex justify-content-between">
                        <strong><?= htmlspecialchars($emisor) ?></strong>
                        <small class="text-muted"><?= date("d/m/Y H:i", strtotime($n["fecha"])) ?></small>
                    </div>
                    <div class="mt-1">
                        <?= htmlspecialchars($n["notificacion"]) ?>
                    </div>
                </div>

                <?php endforeach; ?>

            </div>
        <?php endif; ?>

    </div>

</div>

</body>
</html>
