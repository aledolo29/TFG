<?php
include_once "conexion.php";


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$conexion = new conexion();

if (isset($_POST['admin_Usuario']) && $_POST['admin_Password']) {
    $usuario = $_POST['admin_Usuario'];
    $password = $_POST['admin_Password'];

    $consulta = "SELECT * FROM usuarios_maestros WHERE user_Login = '$usuario' AND user_Password = '$password'";
    $res = $conexion->BD_Consulta($consulta);
    if ($res->num_rows > 0) {
        $_SESSION['admin_Usuario'] = $usuario;
        $_SESSION['admin_Password'] = $password;

        header('Location: ../crudEmpleados.php');
    } else {
        $mensaje = "Usuario o contraseña incorrectos";
        header('Location: ../login-crud.php?mensaje=' . $mensaje);
    }
} else {
    header('Location: ../login-crud.php');
}
