<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once "clases/conexion.php";
include_once "clases/empleado.php";

$conexion = new conexion();
$empleado = new empleado();
if (isset($_SESSION['empl_Usuario']) && $_SESSION['empl_Usuario'] != null && isset($_SESSION['empl_Password']) && $_SESSION['empl_Password'] != null) {
    if (isset($_POST['aux_insertar_empleado'])) {
        $empl_Usuario = $_POST['empl_Usuario'];
        $empl_Password = $_POST['empl_Password'];
        $empl_Nombre = $_POST['empl_Nombre'];
        $empl_Apellidos = $_POST['empl_Apellidos'];
        $empl_DNI = $_POST['empl_DNI'];
        $empl_Correo = $_POST['empl_Correo'];
        $empl_Tipo_Usuario = $_POST['empl_Tipo_Usuario'];
        $empl_Estado = $_POST['empl_Estado'];


        $empleado->insertar($empl_Usuario, $empl_Password, $empl_Nombre, $empl_Apellidos, $empl_DNI, $empl_Correo, $empl_Tipo_Usuario, $empl_Estado);
        $mensaje = "Empleado insertado";
    } else {
        header('Location: ../crudEmpleados.php');
    }
} else {
    header('Location: ./desconectar.php');
}
