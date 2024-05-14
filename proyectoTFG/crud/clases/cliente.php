<?PHP

include_once "conexion.php";

class cliente
{

    var $conexion;

    function __construct()
    {
        $this->conexion = new conexion();
    }


    function insertar($cliente_Nombre, $cliente_Apellidos, $cliente_Usuario, $cliente_Password, $cliente_Telefono, $cliente_DNI, $cliente_Correo)
    {
        $consulta = "INSERT INTO clientes(cliente_Nombre, cliente_Apellidos, cliente_Usuario, cliente_Password, cliente_Telefono, cliente_DNI, cliente_Correo) VALUES('$cliente_Nombre', '$cliente_Apellidos', '$cliente_Usuario', '$cliente_Password', '$cliente_Telefono', '$cliente_DNI', '$cliente_Correo')";
        $res = $this->conexion->BD_Consulta($consulta);
        return $res;
    }


    function eliminar($condicion)
    {
        $consulta = "DELETE FROM clientes $condicion";
        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }

    function modificar(
        $cliente_Id,
        $cliente_Nombre,
        $cliente_Apellidos,
        $cliente_Usuario,
        $cliente_Password,
        $cliente_Telefono,
        $cliente_DNI,
        $cliente_Correo
    ) {
        $consulta = "UPDATE clientes SET cliente_Nombre='$cliente_Nombre', cliente_Apellidos='$cliente_Apellidos', cliente_Usuario='$cliente_Usuario', cliente_Password='$cliente_Password', cliente_Telefono='$cliente_Telefono', cliente_DNI='$cliente_DNI', cliente_Correo='$cliente_Correo' WHERE cliente_Id = '$cliente_Id'";
        $res = $this->conexion->BD_Consulta($consulta);
        return $res;
    }


    function obtener()
    {
        $consulta  = "SELECT * FROM clientes";
        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }


    function obtenerConFiltro($condicion, $order)
    {
        if ($condicion == "" && $order != "")
            $consulta  = "SELECT * FROM clientes $order";
        else {
            if ($order == "" && $condicion != "")
                $consulta  = "SELECT * FROM clientes $condicion";
            else {
                if ($order != "" && $condicion != "")
                    $consulta  = "SELECT * FROM clientes $condicion $order";
                else {
                    if ($order == "" && $condicion == "")
                        $consulta  = "SELECT * FROM clientes";
                }
            }
        }

        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }



    function subirImagen($directorio, $id, $ext)
    {
        $nombreDirectorio = "../../imagen/clientes/";
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
            $imagen2 = "../../imagen/clientes/" . $imagen;
            unlink($imagen2);
        }
    }
}
