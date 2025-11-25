<?php
session_start();
include("../conexion.php");

$email = '';
$errors = [];
$errorCredenciales = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (!isset($_POST['email']) || trim($_POST['email']) == '') {
        $errors['email'] = 'El email no puede ser vacío';
    } elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $_POST['email'])) {
        $errors['email'] = 'El email no tiene formato válido';
    } else {
        $email = trim($_POST['email']);
    }


    if (!isset($_POST['password']) || trim($_POST['password']) == '') {
        $errors['password'] = 'La contraseña no puede ser vacía';
    }

    
    if (empty($errors)) {
        $pass = $_POST['password'];

      
       $query = '
            SELECT u.*, ur.id_rol
            FROM usuario u
            LEFT JOIN usuario_rol ur ON ur.id_usuario = u.id_usuario
            WHERE u.email = :email
        ';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            if ($result['contrasena'] === $pass) {
              $_SESSION['user'] = [
                    'id' => $result['id_usuario'],
                    'email' => $result['email'],
                    'fecha_registro' => $result['fecha_registro'],
                    'id_estado' => $result['id_estado'],
                    'rol' => $result['id_rol']   
                ];
                switch ($result['id_rol']) {

                    case 1: 
                        header('Location: ../Administrador/dashboard.php');
                        break;

                    case 2: 
                        header('Location: ../Jugador/dashboard.php');
                        
                        break;

                    case 3: 
                        header('Location: ../Organizador/dashboard.php');
                        break;

                    default:
                       
                        header('Location: ../error/rol-no-asignado.php');
                        break;
                }

                exit;
            } else {
                $errorCredenciales = true;
            }
        } else {
            $errorCredenciales = true;
        }
    }
}
?>


