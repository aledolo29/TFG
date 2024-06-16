<?PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Europe/Madrid');


class conexion
{

    var $host = "ruix";
    var $bd_nombre_global = "domingueza_general";
    var $bd_usuario_global = "domingueza";
    var $bd_password_global = "dOmingueza10";
    var $bd_ubicacion_global = "ruix";



    //Constructor
    function __construct()
    {
        $bd_nombre = $this->bd_nombre_global;
        $bd_usuario = $this->bd_usuario_global;
        $bd_password = $this->bd_password_global;
        $bd_ubicacion = $this->bd_ubicacion_global;

        if (!isset($GLOBALS['BD_link_conecct']) || !$GLOBALS['BD_link_conecct']) {
            $bd = mysqli_connect($bd_ubicacion, $bd_usuario, $bd_password, $bd_nombre);
            //hacer aparecer ñ
            $bd->query("SET NAMES 'utf8'");
            $bd->set_charset("utf8");
            if ($bd)
                $GLOBALS['BD_link_conecct'] = $bd;
        } //fin del if(!isset($GLOBALS['BD_link_conecct']) || !$GLOBALS['BD_link_conecct'])	
    }


    // Devuelve 1 si se ha cerrado la base de datos o NULL si hay error
    function BD_Cerrar()
    {
        if (isset($GLOBALS['BD_link_conecct']) && mysqli_close($GLOBALS['BD_link_conecct']))
            return (1);
        else
            return (NULL);
    }

    // Ejecuta una consulta en la base de datos. Devuelve NULL si hay error.
    function BD_Consulta($consulta)
    {
        $resultado = FALSE;
        $i = 0;

        while (!$resultado and $i < 3 and isset($GLOBALS['BD_link_conecct'])) {
            $resultado = mysqli_query($GLOBALS['BD_link_conecct'], $consulta);
            $i++;
        }

        if ($resultado)
            return ($resultado);
        else
            return (NULL);
    }

    // Devuelve el numero de filas de una consulta
    function BD_NumeroFilas($consulta)
    {
        $filas = mysqli_num_rows($consulta);

        return $filas;
    }

    // Devuelve un array con una tupla del resultado usando el nombre de campo como indice
    // Devuelve NULL si no quedan m�s filas
    function BD_GetTupla($resultado)
    {
        $tupla = array();
        $tupla = mysqli_fetch_array($resultado);

        return $tupla;
    }

    // Libera el resultado de una consulta
    function BD_BorraResultado($resultado)
    {
        mysqli_free_result($resultado);
    }
}
