<?PHP

include_once "conexion.php";

class empleado
{

    var $conexion;

    function __construct()
    {
        $this->conexion = new conexion();
    }


    function insertar($empl_Usuario, $empl_Password, $empl_Nombre, $empl_Apellidos, $empl_DNI, $empl_Tipo_Usuario, $empl_Estado)
    {
        $consulta = "INSERT INTO empleados(empl_Usuario, empl_Password, empl_Nombre, empl_Apellidos, empl_DNI, empl_Tipo_Usuario, empl_Estado) VALUES('$empl_Usuario', '$empl_Password', '$empl_Nombre', '$empl_Apellidos', '$empl_DNI', '$empl_Tipo_Usuario', '$empl_Estado')";
        $res = $this->conexion->BD_Consulta($consulta);
        return $res;
    }


    function eliminar($condicion)
    {
        $consulta = "DELETE FROM empleados $condicion";
        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }
    function desactivar($condicion)
    {
        $consulta = "UPDATE empleados SET empl_Activo = 'No' $condicion";
        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }

    function modificar(
        $empl_Id,
        $empl_Usuario,
        $empl_Password,
        $empl_Nombre,
        $empl_Apellidos,
        $empl_DNI,
        $empl_Tipo_Usuario,
        $empl_Estado
    ) {
        $consulta = "UPDATE empleados SET empl_Usuario='$empl_Usuario', empl_Password='$empl_Password', empl_Nombre='$empl_Nombre', empl_Apellidos='$empl_Apellidos', empl_DNI='$empl_DNI', empl_Tipo_Usuario='$empl_Tipo_Usuario', empl_Estado='$empl_Estado' WHERE empl_Id = '$empl_Id'";
        $res = $this->conexion->BD_Consulta($consulta);
        return $res;
    }


    function obtener()
    {
        $consulta  = "SELECT * FROM empleados";
        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }


    function obtenerConFiltro($condicion, $order)
    {
        if ($condicion == "" && $order != "")
            $consulta  = "SELECT * FROM empleados $order";
        else {
            if ($order == "" && $condicion != "")
                $consulta  = "SELECT * FROM empleados $condicion";
            else {
                if ($order != "" && $condicion != "")
                    $consulta  = "SELECT * FROM empleados $condicion $order";
                else {
                    if ($order == "" && $condicion == "")
                        $consulta  = "SELECT * FROM empleados";
                }
            }
        }

        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }



    function subirImagen($directorio, $id, $ext)
    {
        $nombreDirectorio = "../../imagen/empleados/";
        $idUnico = rand(0, time());
        $nombreFichero = $idUnico . "-" . $id . "." . $ext;
        if ($nombreFichero != '') {
            move_uploaded_file($directorio, $nombreDirectorio . $nombreFichero);
        }
        return ($nombreFichero);
    }

    function eliminarImagen($imagen)
    {
        if (trim($imagen) != "") {
            $imagen2 = "../../imagen/empleados/" . $imagen;
            unlink($imagen2);
        }
    }
}
