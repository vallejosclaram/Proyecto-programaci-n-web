<?php
require('../connection.php');
session_start();

$errors = [];
$errorCredenciales = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    if ($user === '') {
        $errors['user'] = 'El usuario no puede ser vacío';
    }
    if ($pass === '') {
        $errors['password'] = 'La contraseña no puede ser vacía';
    }

    if (empty($errors)) {
        $query = '
            SELECT u.*, ur.id_rol
            FROM usuario u
            LEFT JOIN usuario_rol ur ON ur.id_usuario = u.id_usuario
            WHERE u.email = :user
        ';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user', $user);
        $stmt->execute();

        if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($pass, $result['contrasena']) && $result['id_rol'] == 1) {
                $_SESSION['admin'] = [
                    'id' => $result['id_usuario'],
                    'email' => $result['email'],
                    'rol' => $result['id_rol']
                ];
                header('Location: ../Administrador/dashboard.php');
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
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login Admin - UPE-SPORT</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <?php if ($errorCredenciales): ?>
    <div class="alert alert-danger">Credenciales inválidas o no es administrador.</div>
    <a href="adminlogin.php" class="btn btn-primary">Volver</a>
  <?php endif; ?>
</body>
</html>
