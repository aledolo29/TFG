<?PHP

include_once "conexion.php";

class nomina
{

    var $conexion;

    function __construct()
    {
        $this->conexion = new conexion();
    }


    function insertar($nomina_Empleado_IdFK, $nomina_Fecha, $nomina_Archivo)
    {
        $consulta = "INSERT INTO nominas(nomina_Empleado_IdFK, nomina_Fecha, nomina_Archivo) VALUES('$nomina_Empleado_IdFK', '$nomina_Fecha', '$nomina_Archivo')";
        $res = $this->conexion->BD_Consulta($consulta);
        return $res;
    }


    function eliminar($condicion)
    {
        $consulta = "DELETE FROM nominas $condicion";
        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }

    function modificar(
        $nomina_Id,
        $nomina_Empleado_IdFK,
        $nomina_Fecha,
        $nomina_Archivo
    ) {
        $consulta = "UPDATE nominas SET nomina_Empleado_IdFK='$nomina_Empleado_IdFK', nomina_Fecha='$nomina_Fecha', nomina_Archivo='$nomina_Archivo' WHERE nomina_Id = '$nomina_Id'";
        $res = $this->conexion->BD_Consulta($consulta);
        return $res;
    }


    function obtener()
    {
        $consulta  = "SELECT * FROM nominas";
        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }


    function obtenerConFiltro($condicion, $order)
    {
        if ($condicion == "" && $order != "")
            $consulta  = "SELECT * FROM nominas $order";
        else {
            if ($order == "" && $condicion != "")
                $consulta  = "SELECT * FROM nominas $condicion";
            else {
                if ($order != "" && $condicion != "")
                    $consulta  = "SELECT * FROM nominas $condicion $order";
                else {
                    if ($order == "" && $condicion == "")
                        $consulta  = "SELECT * FROM nominas";
                }
            }
        }

        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }



    function subirArchivo($directorio, $id, $ext)
    {
        $nombreDirectorio = "../../imagen/nominas/";
        $idUnico = rand(0, time());
        $nombreFichero = $idUnico . "-" . $id . "." . $ext;
        if ($nombreFichero != '') {
            move_uploaded_file($directorio, $nombreDirectorio . $nombreFichero);
        }
        return ($nombreFichero);
    }

    function eliminarArchivo($imagen)
    {
        if (trim($imagen) != "") {
            $imagen2 = "../../imagen/nominas/" . $imagen;
            unlink($imagen2);
        }
    }
}
