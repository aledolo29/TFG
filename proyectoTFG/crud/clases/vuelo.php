<?PHP

include_once "conexion.php";

class vuelo
{

    var $conexion;

    function __construct()
    {
        $this->conexion = new conexion();
    }


    function insertar($vuelo_Fecha_Hora_Salida, $vuelo_AeropuertoSalida, $vuelo_AeropuertoLlegada)
    {
        // Llamamos a la api de los aeropuertos para obtener las ciudades
        $url = "https://api.npoint.io/0ae89dcddb751bee38ef";
        $response = file_get_contents($url);
        $datosAeropuertos = json_decode($response, true);
        $coordenadas1 = "";
        $coordenadas2 = "";
        $latitud1 = "";
        $longitud1 = "";
        $latitud2 = "";
        $longitud2 = "";

        // Buscamos las coordenadas de los aeropuertos
        foreach ($datosAeropuertos as $aeropuerto) {
            if ($aeropuerto['iata'] == $vuelo_AeropuertoSalida) {
                $coordenadas1 = $aeropuerto['coordinates_wkt'];
            }
            if ($aeropuerto['iata'] == $vuelo_AeropuertoLlegada) {
                $coordenadas2 = $aeropuerto['coordinates_wkt'];
            }
        }
        $coordenadas1 = explode("(", $coordenadas1);
        $coordenadas1 = substr($coordenadas1[1], 0, -1);
        $coordenadas1 = explode(" ", $coordenadas1);
        $latitud1 = $coordenadas1[0];
        $longitud1 = $coordenadas1[1];
        $coordenadas2 = explode("(", $coordenadas2);
        $coordenadas2 = substr($coordenadas2[1], 0, -1);
        $coordenadas2 = explode(" ", $coordenadas2);
        $latitud2 = $coordenadas2[0];
        $longitud2 = $coordenadas2[1];

        $intervalo = $this->distanciaEntrePuntos($latitud1, $longitud1, $latitud2, $longitud2);

        //hora llegada
        $f = new DateTime($vuelo_Fecha_Hora_Salida);
        $fechaHoraLlegada = new DateInterval('PT' . intval($intervalo[0]) . 'H' . intval($intervalo[1]) . 'M');
        $fechaHoraLlegada = $f->add($fechaHoraLlegada);
        $vuelo_Fecha_Hora_Llegada = $fechaHoraLlegada->format('Y-m-d H:i:s');


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
        $vuelo_Fecha_Hora_Salida,
        $vuelo_AeropuertoSalida,
        $vuelo_AeropuertoLlegada
    ) {
        // Llamamos a la api de los aeropuertos para obtener las ciudades
        $url = "https://api.npoint.io/0ae89dcddb751bee38ef";
        $response = file_get_contents($url);
        $datosAeropuertos = json_decode($response, true);
        $coordenadas1 = "";
        $coordenadas2 = "";
        $latitud1 = "";
        $longitud1 = "";
        $latitud2 = "";
        $longitud2 = "";

        // Buscamos las coordenadas de los aeropuertos
        foreach ($datosAeropuertos as $aeropuerto) {
            if ($aeropuerto['iata'] == $vuelo_AeropuertoSalida) {
                $coordenadas1 = $aeropuerto['coordinates_wkt'];
            }
            if ($aeropuerto['iata'] == $vuelo_AeropuertoLlegada) {
                $coordenadas2 = $aeropuerto['coordinates_wkt'];
            }
        }

        $coordenadas1 = explode("POINT (", $coordenadas1);
        $coordenadas1 = substr($coordenadas1[1], 0, -1);
        $coordenadas1 = explode(" ", $coordenadas1);
        $latitud1 = $coordenadas1[0];
        $longitud1 = $coordenadas1[1];
        $coordenadas2 = explode("POINT (", $coordenadas2);
        $coordenadas2 = substr($coordenadas2[1], 0, -1);
        $coordenadas2 = explode(" ", $coordenadas2);
        $latitud2 = $coordenadas2[0];
        $longitud2 = $coordenadas2[1];

        $intervalo = $this->distanciaEntrePuntos($latitud1, $longitud1, $latitud2, $longitud2);

        //hora llegada
        $f = new DateTime($vuelo_Fecha_Hora_Salida);
        $fechaHoraLlegada = new DateInterval('PT' . intval($intervalo[0]) . 'H' . intval($intervalo[1]) . 'M');
        $fechaHoraLlegada = $f->add($fechaHoraLlegada);
        $vuelo_Fecha_Hora_Llegada = $fechaHoraLlegada->format('Y-m-d H:i:s');



        $consulta = "UPDATE vuelos SET vuelo_Fecha_Hora_Llegada='$vuelo_Fecha_Hora_Llegada', vuelo_Fecha_Hora_Salida='$vuelo_Fecha_Hora_Salida', vuelo_AeropuertoSalida='$vuelo_AeropuertoSalida', vuelo_AeropuertoLlegada='$vuelo_AeropuertoLlegada'  WHERE vuelo_Id = '$vuelo_Id'";
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
    function distanciaEntrePuntos($latitud1, $longitud1, $latitud2, $longitud2)
    {
        $radioTierra = 6371; // Radio de la Tierra en kilómetros
        $tiempo = 0;

        // Convertir grados a radianes
        $radianesLatitud1 = deg2rad($latitud1);
        $radianesLongitud1 = deg2rad($longitud1);
        $radianesLatitud2 = deg2rad($latitud2);
        $radianesLongitud2 = deg2rad($longitud2);

        // Calcular la diferencia de latitud y longitud
        $deltaLatitud = $radianesLatitud2 - $radianesLatitud1;
        $deltaLongitud = $radianesLongitud2 - $radianesLongitud1;

        // Calcular la distancia utilizando la fórmula de Haversine
        $a = sin($deltaLatitud / 2) * sin($deltaLatitud / 2) +
            cos($radianesLatitud1) * cos($radianesLatitud2) * sin($deltaLongitud / 2) * sin($deltaLongitud / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distancia = $radioTierra * $c;

        if ($distancia < 500) {
            $tiempo = $distancia / 450; // La distancia en horas si la distancia es menor a 500 km
        } else {
            $tiempo = $distancia / 750; // La distancia en horas si la distancia es mayor a 500 km
        }

        $horas = floor($tiempo); // Obtener la parte entera de las horas
        $minutos = ceil(($tiempo - $horas) * 60); // Calcular los minutos restantes

        return [$horas, $minutos];
    }
}
