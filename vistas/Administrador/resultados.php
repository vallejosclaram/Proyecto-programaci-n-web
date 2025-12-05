<?php
session_start();
require_once(__DIR__ . '/../connection.php');
require_once(__DIR__ . '/../includes/clases/permisos.php');

if (empty($_SESSION['admin']['id'])) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['success' => false, 'error' => 'No hay usuario administrador logueado']);
        exit;
    }
    header('Location: /ruta/a/login.php');
    exit;
}

$id_usuario = $_SESSION['admin']['id'];
$rol = $_SESSION['admin']['rol'] ?? null;

if ($rol != 1 || !Permisos::tienePermiso('gestionar_puntaje', $id_usuario)) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['success' => false, 'error' => 'Acceso restringido o falta de permiso']);
        exit;
    }
    header('Location: ../error.php?msg=No tenés permiso para visualizar resultados');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Resultados</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" /> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="dashboard-page bg-light">

    <?php require_once __DIR__ . '/../includes/dashboardAdmin.php'; ?> 

    <main class="main-content" id="mainContent">
        <div class="container py-5">
            <div class="form-container">

                <h1 class="mb-4 text-center text-dark">Gestión de Resultados y Bracket de Torneos 🎮</h1>

                <div id="alert-container"></div>

                <div class="card mb-5 shadow-lg">
                    <div class="card-header bg-dark text-white p-3">
                        <h2>Resultados Pendientes de Aprobación</h2>
                    </div>

                    <div class="card-body bg-light text-dark">
                        <div class="table-scroll">
                            <table class="table table-dark table-hover mt-3">
                                <thead>
                                    <tr>
                                        <th>ID Partida</th>
                                        <th>Torneo</th>
                                        <th>Ronda</th>
                                        <th>Tipo</th>
                                        <th>Participante 1</th>
                                        <th>Participante 2</th>
                                        <th>Resultado Propuesto</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-pendientes">
                                    <tr>
                                        <td colspan="8" class="text-center">Cargando datos...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p id="no-pendientes" class="text-center text-muted mt-3" style="display: none;">No hay resultados pendientes de aprobación.</p>
                    </div>
                </div>

                <hr class="my-5">

                <div class="card mb-5 shadow-lg">
                    <div class="card-header bg-dark text-white p-3">
                        <h2>Visualización del Bracket del Torneo 🏆</h2>
                    </div>

                    <div class="card-body bg-light text-dark">
                        <div id="bracket-seleccion" class="row g-3">
                            <div class="col-md-6">
                                <label for="selectTorneoBracket" class="form-label">Seleccionar Torneo</label>
                                <select id="selectTorneoBracket" class="form-select">
                                    <option value="">Cargando torneos...</option>
                                </select>
                            </div>

                            <div class="col-md-6 d-flex align-items-end">
                                <button id="btnVerBracket" class="btn btn-warning me-2" disabled>Ver Bracket</button>
                            </div>
                        </div>

                        <div id="bracket-draw-container" class="mt-4 p-3 border rounded bg-dark text-white">
                            <p class="text-muted text-center">Seleccione un torneo para ver su estructura de partidas.</p>
                        </div>
                    </div>
                </div>

                <hr class="my-5">

                <div class="card mb-5 shadow-lg">
                    <div class="card-header bg-dark text-white p-3">
                        <h2>Ranking del Torneo 🏅</h2>
                    </div>

                    <div class="card-body bg-light text-dark">
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="selectTorneoRanking" class="form-label">Seleccionar Torneo</label>
                                <select id="selectTorneoRanking" class="form-select">
                                    <option value="">Cargando torneos...</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="selectTipoRanking" class="form-label">Tipo de Ranking</label>
                                <select id="selectTipoRanking" class="form-select">
                                    <option value="individual">Individual</option>
                                    <option value="equipo">Equipo</option>
                                </select>
                            </div>

                            <div class="col-md-4 d-flex align-items-end">
                                <button id="btnVerRanking" class="btn btn-success" disabled>Ver Ranking</button>
                            </div>
                        </div>

                        <div id="ranking-container" class="mt-4">
                            <p class="text-muted text-center">Seleccione un torneo para ver la clasificación.</p>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="confirmarModal" tabindex="-1" aria-labelledby="confirmarModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="confirmarModalLabel">Confirmar Acción</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <p>¿Está seguro de que desea <strong>aceptar/rechazar</strong> el siguiente resultado?</p>
                                <p id="modal-detalle-partida" class="mb-1"></p>
                                <p class="fw-bold">Resultado: <span id="modal-resultado-propuesto" class="text-primary"></span></p>

                                <div class="alert alert-warning" role="alert">
                                    Aceptar finaliza la partida y otorga puntos. Rechazar la revierte a 'en_progreso'.
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-primary" id="btnConfirmarAceptar">Aceptar</button>
                                <button type="button" class="btn btn-danger" id="btnConfirmarRechazar">Rechazar</button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="resultados.js"></script>
    </main>
</body>
</html>
