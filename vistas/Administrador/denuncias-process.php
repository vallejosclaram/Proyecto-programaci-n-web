<?php
    session_start();
    require_once(__DIR__ . '/../connection.php');
    require_once(__DIR__ . '/../includes/clases/permisos.php');

    header('Content-Type: application/json; charset=UTF-8');

    // Validar sesión y rol
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

    // Entrada JSON
    $input  = json_decode(file_get_contents('php://input'), true);
    $accion = $input['accion'] ?? null;

    $hasUsuarioFecha = false;
    $hasTorneoFecha  = false;

    // Verificar columnas fecha_fin_bloqueo
    try {
        $stmtCols = $conn->prepare("
            SELECT COUNT(*) AS c 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = ? 
              AND COLUMN_NAME = 'fecha_fin_bloqueo'
        ");
        $stmtCols->execute(['usuario']);
        $hasUsuarioFecha = intval($stmtCols->fetchColumn()) > 0;

        $stmtCols->execute(['torneo']);
        $hasTorneoFecha = intval($stmtCols->fetchColumn()) > 0;
    } catch (PDOException $e) {}

    // Liberar bloqueos vencidos
    if ($hasUsuarioFecha) {
        try {
            $conn->exec("
                UPDATE usuario 
                SET id_estado = 1, fecha_fin_bloqueo = NULL 
                WHERE id_estado = 3 
                  AND fecha_fin_bloqueo IS NOT NULL 
                  AND fecha_fin_bloqueo <= NOW()
            ");
        } catch (PDOException $e) {}
    }

    if ($hasTorneoFecha) {
        try {
            $conn->exec("
                UPDATE torneo 
                SET id_estado = 1, fecha_fin_bloqueo = NULL 
                WHERE id_estado = 4 
                  AND fecha_fin_bloqueo IS NOT NULL 
                  AND fecha_fin_bloqueo <= NOW()
            ");
        } catch (PDOException $e) {}
    }

    // Si no hay acción → devolver denuncias
    if (!$accion) {
        try {
            $usuarioFechaField = $hasUsuarioFecha ? 'rep.fecha_fin_bloqueo AS fecha_fin_bloqueo_usuario,' : '';
            $torneoFechaField  = $hasTorneoFecha ? 't.fecha_fin_bloqueo AS fecha_fin_bloqueo_torneo,' : '';

            $sql = "
                SELECT d.id_denuncia,
                       d.descripcion,
                       d.fecha_creacion,
                       d.id_reportador,
                       d.id_reportado,
                       d.id_torneo,
                       r.email AS reportador,
                       rep.email AS reportado,
                       u_estado.descripcion AS estado_usuario,
                       (CASE 
                            WHEN (t.nombre IS NULL OR t.nombre = '') 
                                 AND d.id_torneo IS NOT NULL 
                                 AND d.id_torneo > 0 
                            THEN CONCAT('Torneo ID: ', d.id_torneo) 
                            ELSE t.nombre 
                        END) AS torneo,
                       t.id_juego AS id_juego,
                       j.nombre AS juego,
                       t.id_tipo AS id_tipo,
                       tipo.descripcion AS tipo_torneo,
                       $usuarioFechaField
                       $torneoFechaField
                       t_estado.descripcion AS estado_torneo
                FROM denuncias d
                LEFT JOIN usuario r ON r.id_usuario = d.id_reportador
                LEFT JOIN usuario rep ON rep.id_usuario = d.id_reportado
                LEFT JOIN estado u_estado ON rep.id_estado = u_estado.id_estado
                LEFT JOIN torneo t ON t.id_torneo = d.id_torneo
                LEFT JOIN juego j ON j.id_juego = t.id_juego
                LEFT JOIN tipo_torneo tipo ON tipo.id_tipo = t.id_tipo
                LEFT JOIN estado_torneo t_estado ON t.id_estado = t_estado.id_estado
                ORDER BY d.fecha_creacion DESC
            ";

            $stmt     = $conn->query($sql);
            $denuncias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Catálogos auxiliares
            try {
                $juegos = $conn->query('SELECT id_juego, nombre FROM juego ORDER BY nombre')->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) { $juegos = []; }

            try {
                $torneos_all = $conn->query('SELECT id_torneo, nombre FROM torneo ORDER BY nombre')->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) { $torneos_all = []; }

            try {
                $tipos = $conn->query('SELECT id_tipo, descripcion FROM tipo_torneo ORDER BY descripcion')->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) { $tipos = []; }

            // Separar denuncias
            $usuario = array_filter($denuncias, function($d) {
                return (empty($d['id_torneo']) || intval($d['id_torneo']) <= 0) && !empty($d['id_reportado']);
            });

            $torneo = array_filter($denuncias, function($d) {
                return (isset($d['id_torneo']) && intval($d['id_torneo']) > 0);
            });

            echo json_encode([
                'success'      => true,
                'usuario'      => array_values($usuario),
                'torneo'       => array_values($torneo),
                'juegos'       => $juegos,
                'torneos'      => $torneos_all,
                'tipos_torneo' => $tipos
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error en la consulta: '.$e->getMessage()]);
        }
        exit;
    }

    // Bloqueo de usuario
    if ($accion == 'bloquear_usuario') {
        if (empty($input['id_reportado'])) {
            echo json_encode(['success' => false, 'error' => 'ID de usuario reportado es requerido.']);
            exit;
        }

        $id_reportado = $input['id_reportado'];

        try {
            if ($hasUsuarioFecha) {
                $stmt = $conn->prepare("
                    UPDATE usuario 
                    SET id_estado = 3, 
                        fecha_fin_bloqueo = DATE_ADD(NOW(), INTERVAL 15 DAY) 
                    WHERE id_usuario = ?
                ");
                $stmt->execute([$id_reportado]);
                $affected = $stmt->rowCount();
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario bloqueado exitosamente por 15 días.',
                    'affected' => $affected
                ]);
            } else {
                $stmt = $conn->prepare("UPDATE usuario SET id_estado = 3 WHERE id_usuario = ?");
                $stmt->execute([$id_reportado]);
                $affected = $stmt->rowCount();
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario bloqueado exitosamente.',
                    'affected' => $affected
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error al bloquear usuario: ' . $e->getMessage()]);
        }
        exit;
    }

    // Bloqueo de torneo
    if ($accion == 'bloquear_torneo') {
        if (empty($input['id_torneo'])) {
            echo json_encode(['success' => false, 'error' => 'ID de torneo es requerido.']);
            exit;
        }

        $id_torneo = $input['id_torneo'];

        try {
            if ($hasTorneoFecha) {
                $stmt = $conn->prepare("
                    UPDATE torneo 
                    SET id_estado = 4, 
                        fecha_fin_bloqueo = DATE_ADD(NOW(), INTERVAL 15 DAY) 
                    WHERE id_torneo = ?
                ");
                $stmt->execute([$id_torneo]);
                $affected = $stmt->rowCount();
                echo json_encode([
                    'success' => true,
                    'message' => 'Torneo bloqueado exitosamente por 15 días.',
                    
                ]);
            } else {
                $stmt = $conn->prepare("UPDATE torneo SET id_estado = 4 WHERE id_torneo = ?");
                $stmt->execute([$id_torneo]);
                $affected = $stmt->rowCount();
                echo json_encode([
                    'success' => true,
                    'message' => 'Torneo bloqueado exitosamente.',
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error al bloquear torneo: ' . $e->getMessage()]);
        }
        exit;
    }
?>
