<?PHP

include_once "conexion.php";

class usuario
{

    var $conexion;

    function __construct()
    {
        $this->conexion = new conexion();
    }


    function insertar($usuario_Login, $usuario_Password, $usuario_DNI, $usuario_Activo)
    {
        $consulta = "INSERT INTO usuarios_maestros(usuario_Login, usuario_Password, usuario_DNI, usuario_Activo) VALUES('$usuario_Login', '$usuario_Password', '$usuario_DNI', '$usuario_Activo')";
        $res = $this->conexion->BD_Consulta($consulta);
        return $res;
    }


    function eliminar($condicion)
    {
        $consulta = "DELETE FROM usuarios_maestros $condicion";
        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }
    function desactivar($condicion)
    {
        $consulta = "UPDATE usuarios_maestros SET usuario_Activo = 'No' $condicion";
        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }

    function modificar(
        $usuario_Id,
        $usuario_Login,
        $usuario_Password,
        $usuario_DNI,
        $usuario_Activo
    ) {
        $consulta = "UPDATE usuarios_maestros SET usuario_Login='$usuario_Login', usuario_Password='$usuario_Password', usuario_DNI='$usuario_DNI', usuario_Activo='$usuario_Activo' WHERE usuario_Id = '$usuario_Id'";
        $res = $this->conexion->BD_Consulta($consulta);
        return $res;
    }


    function obtener()
    {
        $consulta  = "SELECT * FROM usuarios_maestros";
        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }


    function obtenerConFiltro($condicion, $order)
    {
        if ($condicion == "" && $order != "")
            $consulta  = "SELECT * FROM usuarios_maestros $order";
        else {
            if ($order == "" && $condicion != "")
                $consulta  = "SELECT * FROM usuarios_maestros $condicion";
            else {
                if ($order != "" && $condicion != "")
                    $consulta  = "SELECT * FROM usuarios_maestros $condicion $order";
                else {
                    if ($order == "" && $condicion == "")
                        $consulta  = "SELECT * FROM usuarios_maestros";
                }
            }
        }

        $res = $this->conexion->BD_Consulta($consulta);
        return ($res);
    }
}
