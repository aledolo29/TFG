<?PHP

include_once "conexion.php";

class empleado
{

    var $conexion;

    function __construct()
    {
        $this->conexion = new conexion();
    }


    function insertar($empl_Nombre, $empl_Apellidos, $empl_DNI, $empl_Correo, $empl_Estado, $empl_Telefono)
    {
        $consulta = "INSERT INTO empleados(empl_Nombre, empl_Apellidos, empl_DNI, empl_Correo, empl_Estado, empl_Telefono) VALUES('$empl_Nombre', '$empl_Apellidos', '$empl_DNI', '$empl_Correo' , '$empl_Estado', '$empl_Telefono')";
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
        $consulta = "UPDATE empleados SET empl_Estado = 'Baja' $condicion";
        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }

    function modificar(
        $empl_Id,
        $empl_Nombre,
        $empl_Apellidos,
        $empl_DNI,
        $empl_Correo,
        $empl_Estado,
        $empl_Telefono
    ) {
        $consulta = "UPDATE empleados SET empl_Nombre='$empl_Nombre', empl_Apellidos='$empl_Apellidos', empl_DNI='$empl_DNI', empl_Correo='$empl_Correo', empl_Estado='$empl_Estado', empl_Telefono='$empl_Telefono' WHERE empl_Id = '$empl_Id'";
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
