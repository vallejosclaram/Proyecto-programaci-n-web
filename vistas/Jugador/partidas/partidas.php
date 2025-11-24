<?php
session_start();

if (!isset($_SESSION["user"]["id"])) {
    die("Error: no hay usuario logueado.");
}

$usuario_id = $_SESSION["user"]["id"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Partidas</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="partidas.js"></script>
</head>

<body class="dashboard-page">

   <?php require_once __DIR__ . '/../../includes/dashboardJugador.php'; ?>

  <main class="container py-4">
    
    <h1 class="mb-4">🎥 Partidas en vivo</h1>

    <section class="mb-5">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <h2 class="section-title">Transmisiones de eSports</h2>

        <div class="scroll-controls">
          <button class="btn btn-sm btn-light me-1" data-target="partidasList" data-dir="-1">◀</button>
          <button class="btn btn-sm btn-light" data-target="partidasList" data-dir="1">▶</button>
        </div>
      </div>

      <div class="h-scroll" id="partidasList" tabindex="0" aria-label="Lista de partidas" style="gap: 20px;">
        
        
        <div class="card bg-dark text-white border-secondary" style="min-width: 380px;">
          <div class="card-body">
            <h5 class="mb-3">League of Legends – LLA</h5>
            <iframe
              src="https://player.twitch.tv/?channel=riotgameslatam&parent=localhost"
              frameborder="0"
              allowfullscreen="true"
              scrolling="no"
              height="250"
              width="100%">
            </iframe>
          </div>
        </div>

        
        <div class="card bg-dark text-white border-secondary" style="min-width: 380px;">
          <div class="card-body">
            <h5 class="mb-3">Valorant Champions Tour</h5>
            <iframe
              src="https://player.twitch.tv/?channel=valorant_es&parent=localhost"
              frameborder="0"
              allowfullscreen="true"
              scrolling="no"
              height="250"
              width="100%">
            </iframe>
          </div>
        </div>

        
        <div class="card bg-dark text-white border-secondary" style="min-width: 380px;">
          <div class="card-body">
            <h5 class="mb-3">CS2 – Copa LATAM</h5>
            <iframe
              src="https://player.twitch.tv/?channel=esl_csgo&parent=localhost"
              frameborder="0"
              allowfullscreen="true"
              scrolling="no"
              height="250"
              width="100%">
            </iframe>
          </div>
        </div>

      </div>
    </section>

  </main>

</body>
</html>
