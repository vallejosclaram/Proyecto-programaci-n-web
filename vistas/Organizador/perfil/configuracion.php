<?php
include '../../connection.php';
session_start();

$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
  header('Location: ../../auth/login.php');
  exit;
}

// Obtener organizador
$stmtOrg = $conn->prepare('SELECT * FROM organizador WHERE id_usuario = ? LIMIT 1');
$stmtOrg->execute([$id_usuario]);
$organizador = $stmtOrg->fetch(PDO::FETCH_ASSOC);

// Obtener email
$stmtUser = $conn->prepare('SELECT email FROM usuario WHERE id_usuario = ? LIMIT 1');
$stmtUser->execute([$id_usuario]);
$userRow = $stmtUser->fetch(PDO::FETCH_ASSOC);
$organizador['email'] = $userRow['email'] ?? null;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Perfil - UPE-SPORT</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Roboto&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="configuracion.css">
</head>
<body>

    <!-- ===== HEADER  ===== -->
    <?php include '../componentes/header.php'; ?>
    <div class="btn-return-box">
        <a href="ver.php" class="btn-return">
            <i class="fa-solid fa-arrow-left"></i> Volver
        </a>
    </div>
    <div class="config-wrapper">
    <!-- Botón volver arriba -->
    <h1>⚙️ Configuración</h1>

    <!-- ===== CAMBIAR CONTRASEÑA ===== -->
    <div class="config-card">
        <h3>Cambiar contraseña</h3>

        <div id="changePwdAlert" class="inline-alert"></div>

        <form id="changePasswordForm" method="POST">
            <div class="form-group">
                <label>Contraseña actual</label>
                <input type="password" name="current" id="pwdCurrent" required>
            </div>

            <div class="form-group">
                <label>Nueva contraseña</label>
                <input type="password" name="new" id="pwdNew" required>
            </div>

            <div class="form-group">
                <label>Confirmar contraseña</label>
                <input type="password" name="confirm" id="pwdConfirm" required>
            </div>

            <button id="changePasswordBtn" class="btn-brand full">Cambiar contraseña</button>
        </form>
    </div>


    <!-- ===== ELIMINAR CUENTA ===== -->
    <div class="config-card danger">
        <h3>Eliminar cuenta</h3>
        <p class="danger-text">
            Esta acción es <b>permanente</b> y no podrás recuperar tu acceso.
        </p>

        <button type="button" class="btn-danger-brand full" data-bs-toggle="modal" data-bs-target="#deleteModal">
            Eliminar cuenta
        </button>
    </div>
    </div>
    <!-- ===== MODAL ELIMINAR CUENTA ===== -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background:#1c1c2b; border:2px solid #ff4d6d; border-radius:15px; color:white;">

                <div class="modal-header" style="border-bottom:1px solid #ff4d6d;">
                    <h5 class="modal-title">⚠️ Eliminar cuenta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="font-size:1rem;">
                    <p>¿Estás seguro de que deseas eliminar tu cuenta?</p>
                    <p class="text-danger"><b>Esta acción es permanente y no podrás recuperarla.</b></p>
                </div>

                <div class="modal-footer" style="border-top:1px solid #ff4d6d;">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                    <form action="../../../Backend/organizador/delete_account.php" method="POST">
                        <button class="btn btn-danger">Eliminar definitivamente</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/Proyecto-programaci-n-web/vistas/Organizador/perfil/configuracion.js"></script>
  
</body>
</html>