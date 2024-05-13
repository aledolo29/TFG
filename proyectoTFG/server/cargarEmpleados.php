<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once "clases/conexion.php";
include_once "clases/empleado.php";
header('Content-Type: application/json; charset=utf-8');

if (isset($_SESSION['empl_Usuario']) && $_SESSION['empl_Usuario'] != null && isset($_SESSION['empl_Password']) && $_SESSION['empl_Password'] != null) {
    $conexion = new conexion();
    $empleados = new empleado();

    $consultaEmpleados = "WHERE empl_Estado = 'Alta'";
    $res = $empleados->obtenerConFiltro($consultaEmpleados, "");
    $tupla_Empleado = $conexion->BD_GetTupla($res);

    $arrayEmpleados = array();
    while ($tupla_Empleado !== null) {
        $arrayEmpleados[] = $tupla_Empleado;
        $tupla_Empleado = $conexion->BD_GetTupla($res);
    }
    echo json_encode($arrayEmpleados);
} else {
    header('Location: clases/desconectar.php');
}
