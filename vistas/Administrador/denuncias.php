<?php
    session_start();
    require_once(__DIR__ . '/../connection.php');
    require_once(__DIR__ . '/../includes/clases/permisos.php');

    if (empty($_SESSION['admin']['id'])) {
        echo json_encode(['success' => false, 'error' => 'No hay usuario administrador logueado']);
        exit;
    }

    $id_usuario = $_SESSION['admin']['id'];
    $rol = $_SESSION['admin']['rol'] ?? null;

    if ($rol != 1) {
        echo json_encode(['success' => false, 'error' => 'Acceso restringido']);
        exit;
    }

    if (!Permisos::tienePermiso('Visualizar denuncia', $id_usuario)) {
        header('Location: ../error.php?msg=No tenés permiso para visualizar denuncias');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Denuncias</title>

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css" />
</head>
<body class="dashboard-page">
    <?php require_once __DIR__ . '/../includes/dashboardAdmin.php'; ?>

    <main class="main-content" id="mainContent">
        <h2>Denuncias de Usuarios</h2>
        <div class="form-container">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="filterUsuario" class="form-label">Buscar Usuario Reportado (por email)</label>
                    <input type="text" id="filterUsuario" class="form-control" placeholder="Escriba el email o parte del email">
                </div>
            </div>
        </div>

        <div id="denuncias-usuarios-container" class="mt-4">
            <table class="table table-striped table-dark">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Reportador</th>
                        <th>Usuario Reportado</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="denuncias-usuarios-body"></tbody>
            </table>
        </div>

        <h2 class="mt-5">Denuncias de Torneos</h2>
        <div class="form-container">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="filterJuego" class="form-label">Filtrar por juego</label>
                    <select id="filterJuego" class="form-select">
                        <option value="">Todos los juegos</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterTorneo" class="form-label">Filtrar por torneo</label>
                    <select id="filterTorneo" class="form-select">
                        <option value="">Todos los torneos</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterTipo" class="form-label">Filtrar por tipo de torneo</label>
                    <select id="filterTipo" class="form-select">
                        <option value="">Todos los tipos</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-secondary" id="btnClearFilters">Limpiar filtros</button>
                </div>
            </div>
        </div>

        <div id="denuncias-torneos-container" class="mt-4">
            <table class="table table-striped table-dark">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Reportador</th>
                        <th>Torneo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="denuncias-torneos-body"></tbody>
            </table>
        </div>
    </main>

    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="modalDetallesLabel">Detalles de la denuncia</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalDetallesBody"></div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-danger" id="btnBloquear">Bloquear</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalConfirmar" tabindex="-1" aria-labelledby="modalConfirmarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="modalConfirmarLabel">Confirmar acción</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="modalConfirmarBody">¿Estás seguro que deseas bloquear este elemento por 15 días?</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-danger" id="btnConfirmarBloquear">Confirmar bloqueo</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMensaje" tabindex="-1" aria-labelledby="modalMensajeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="modalMensajeLabel">Mensaje</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalMensajeBody"></div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="denuncias.js"></script>
</body>
</html>
