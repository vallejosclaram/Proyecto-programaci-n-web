<?php
require_once "../connection.php";

$q = "%" . ($_GET["q"] ?? "") . "%";

$sql = "SELECT 
            j.id_jugador AS id,
            CONCAT(j.nombre, ' ', j.apellido) AS titulo,
            'jugador' AS tipo
        FROM jugador j
        WHERE CONCAT(j.nombre, ' ', j.apellido) LIKE :q
        
        UNION
        SELECT 
            t.id_torneo AS id,
            t.nombre AS titulo,
            'torneo' AS tipo
        FROM torneo t
        WHERE t.nombre LIKE :q

        UNION
        SELECT 
            e.id_equipo AS id,
            e.nombre AS titulo,
            'equipo' AS tipo
        FROM equipo e
        WHERE e.nombre LIKE :q
        LIMIT 50";

$stmt = $conn->prepare($sql);
$stmt->execute(['q' => $q]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
