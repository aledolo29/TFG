<?php
include_once "clases/conexion.php";


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$conexion = new conexion();

if (isset($_POST['empl_Usuario']) && $_POST['empl_Password']) {
    $usuario = $_POST['empl_Usuario'];
    $password = $_POST['empl_Password'];

    $consulta = "SELECT * FROM empleados WHERE empl_Usuario = '$usuario' AND empl_Password = '$password'";
    $res = $conexion->BD_Consulta($consulta);
    if ($res->num_rows > 0) {
        $_SESSION['empl_Usuario'] = $usuario;
        $_SESSION['empl_Password'] = $password;
        header('Location: ../crud.html');
    } else {
        header('Location: ../error.html');
    }
} else {
    header('Location: ../login-crud.html');
}
