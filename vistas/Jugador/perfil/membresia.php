<?php
require_once "../../connection.php";
session_start();


$id_usuario = $_SESSION["user"]["id"] ?? null;
if (!$id_usuario) {
    die("No hay usuario logueado");
}


$membresias = $conn->query("SELECT id_membresia, descripcion FROM membresia")->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Membresía</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../style.css"/>
  <link rel="stylesheet" href="perfil.css" />
  <script src="membresia.js"></script>
 
</head>
<body>

<?php require_once __DIR__ . '/../../includes/dashboardJugador.php'; ?>

<h2 class="mb-3 mt-3 d-flex align-items-center justify-content-center">Solicitar Membresía</h2>
<div class="d-flex align-items-center justify-content-center">
    
<form id="formMembresia" >
    <label for="tipo" class="mt-3 mb-3">Tipo de membresía:</label>
    <select id="tipo" name="tipo" class="form-select">
        <?php foreach ($membresias as $m): ?>
            <option value="<?= $m['id_membresia'] ?>">
                <?= htmlspecialchars($m['descripcion']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <label class="mt-3 mb-3">Subir comprobante:</label>
    <input type="file" id="comprobante" name="comprobante" class="form-control" accept="image/*,application/pdf">

    <br><br>
    
    <button type="submit" class="mb-3 mt-3 btn btn-success">Enviar Solicitud 🧾</button>
</form>

        </div>



</body>
</html>
