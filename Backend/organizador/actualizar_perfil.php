<?php
session_start();
include '../conexion.php'; 

if(!isset($_SESSION['id_organizador'])){
    die("Acceso denegado");
}


$id_organizador = $_SESSION['id_organizador'];


$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';


if(empty($nombre) || empty($apellido) || empty($email)){
    die("Por favor completa todos los campos obligatorios.");
}


$sql = "UPDATE organizador o
        JOIN usuario u ON o.id_usuario = u.id_usuario
        SET u.email = ?, o.nombre = ?, o.apellido = ?, o.descripcion = ?
        WHERE o.id_organizador = ?";

$stmt = $conn->prepare($sql);
if(!$stmt){
    die("Error en la preparación de la consulta: " . $conn->error);
}


$stmt->bind_param("ssssi", $email, $nombre, $apellido, $descripcion, $id_organizador);


if($stmt->execute()){
    
    header("Location: ../Frontend/Organizador/perfil/ver.php?update=success");
    exit();
} else {
    echo "Error al actualizar: " . $stmt->error;
}


$stmt->close();
$conn->close();
?>
