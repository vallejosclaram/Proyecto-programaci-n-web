<?php
$host = "localhost";
$user = "root";
$password = ""; // por defecto en XAMPP no tiene contraseña
$database = "upesport_bd;";

$conexion = new mysqli($host, $user, $password, $database);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
} else {
    echo "Conexión exitosa";
}
?>