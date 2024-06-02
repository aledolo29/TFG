<?PHP

include_once "conexion.php";

class vuelo
{

    var $conexion;

    function __construct()
    {
        $this->conexion = new conexion();
    }


    function insertar($vuelo_Fecha_Hora_Llegada, $vuelo_Fecha_Hora_Salida, $vuelo_AeropuertoSalida, $vuelo_AeropuertoLlegada)
    {
        $consulta = "INSERT INTO vuelos(vuelo_Fecha_Hora_Llegada, vuelo_Fecha_Hora_Salida, vuelo_AeropuertoSalida, vuelo_AeropuertoLlegada, created_at, updated_at) VALUES('$vuelo_Fecha_Hora_Llegada', '$vuelo_Fecha_Hora_Salida', '$vuelo_AeropuertoSalida', '$vuelo_AeropuertoLlegada' , NOW(), NOW())";
        $res = $this->conexion->BD_Consulta($consulta);
        return $res;
    }


    function eliminar($condicion)
    {
        $consulta = "DELETE FROM vuelos $condicion";
        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }

    function modificar(
        $vuelo_Id,
        $vuelo_Fecha_Hora_Llegada,
        $vuelo_Fecha_Hora_Salida,
        $vuelo_AeropuertoSalida,
        $vuelo_AeropuertoLlegada
    ) {
        $consulta = "UPDATE vuelos SET vuelo_Fecha_Hora_Llegada='$vuelo_Fecha_Hora_Llegada', vuelo_Fecha_Hora_Salida='$vuelo_Fecha_Hora_Salida', vuelo_AeopuertoSlida='$vuelo_AeropuertoSalida', vuelo_AeropuertoLlegada='$vuelo_AeropuertoLlegada' WHERE vuelo_Id = '$vuelo_Id'";
        $res = $this->conexion->BD_Consulta($consulta);
        return $res;
    }


    function obtener()
    {
        $consulta  = "SELECT * FROM vuelos";
        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }


    function obtenerConFiltro($condicion, $order)
    {
        if ($condicion == "" && $order != "")
            $consulta  = "SELECT * FROM vuelos $order";
        else {
            if ($order == "" && $condicion != "")
                $consulta  = "SELECT * FROM vuelos $condicion";
            else {
                if ($order != "" && $condicion != "")
                    $consulta  = "SELECT * FROM vuelos $condicion $order";
                else {
                    if ($order == "" && $condicion == "")
                        $consulta  = "SELECT * FROM vuelos";
                }
            }
        }

        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }
}
