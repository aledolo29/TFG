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
        $empl_Nombre = $_POST['empl_Nombre'];
        $empl_Apellidos = $_POST['empl_Apellidos'];
        $empl_DNI = $_POST['empl_DNI'];
        $empl_Email = $_POST['empl_Correo'];
        $empl_Estado = $_POST['empl_Estado'];
        $empl_Usuario = $_POST['empl_Usuario'];
        $empl_Password = $_POST['empl_Password'];


        $campos = ['empl_DNI' => $empl_DNI, 'empl_Usuario' => $empl_Usuario, 'empl_Email' => $empl_Email];
        foreach ($campos as $campo => $valor) {
            $consulta = "WHERE $campo = '$valor'";
            $res = $empleado->obtenerConFiltro($consulta, "");
            $tupla = $conexion->BD_GetTupla($res);
            if ($tupla !== null) {
                echo "El $campo ya existe";
                exit();
            }
        }

        $empleado->insertar($empl_Usuario, $empl_Password, $empl_Nombre, $empl_Apellidos, $empl_DNI, $empl_Email, $empl_Estado);
        echo "Empleado insertado";
    } else {
        header('Location: ../crud.html');
    }
} else {
    header('Location: clases/desconectar.php');
}
