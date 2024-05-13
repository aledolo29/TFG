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


        $campos = ['empl_DNI' => $empl_DNI, 'empl_Usuario' => $empl_Usuario, 'empl_Email' => $empl_Email];
        foreach ($campos as $campo => $valor) {
            $consulta = "WHERE $campo = '$valor'";
            $res = $empleado->obtenerConFiltro($consulta, "");
            $tupla = $conexion->BD_GetTupla($res);
            if ($tupla !== null) {
                echo json_encode(['mensaje' => "Empleado $empl_Nombre insertado correctamente", 'insertado' => true]);
                exit();
            } else {
                echo json_encode(['mensaje' => "ERROR al insertar el empleado. Ya existe un usuario con ese $campo", 'insertado' => false]);
            }
        }

        $empleado->insertar($empl_Usuario, $empl_Password, $empl_Nombre, $empl_Apellidos, $empl_DNI, $empl_Correo, $empl_Tipo_Usuario, $empl_Estado);
        echo "Empleado insertado";
    } else {
        header('Location: ../crud.html');
    }
} else {
    header('Location: clases/desconectar.php');
}
