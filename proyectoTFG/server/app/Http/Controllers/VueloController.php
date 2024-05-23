<?php

namespace App\Http\Controllers;

use App\Models\Vuelo;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;

class VueloController extends Controller
{
    // Función para buscar vuelos ida
    public function buscarVueloIda(Request $request)
    {

        // Recogemos los datos del formulario
        $origen = $request->origen;
        $destino = $request->destino;
        $fecha = $request->fecha;
        $intervalo = $request->intervalo;

        // Realizamos la consulta a la base de datos
        $vuelos = Vuelo::where('vuelo_AeropuertoSalida', $origen)
            ->where('vuelo_AeropuertoLlegada', $destino)
            ->whereDate('vuelo_Fecha_Hora_Salida', $fecha)
            ->get();

        if ($vuelos->count() < 5) {
            $cantidadAnadir = 5 - $vuelos->count(); // Calculamos cuantos vuelos faltan
            for ($i = 0; $i < $cantidadAnadir; $i++) {
                //Creamos vuelos aleatorios
                //hora salida
                $horaIda = $this->creaHoras();
                $fechaHoraFormatSalida = DateTime::createFromFormat('Y-m-d H:i:s', $fecha . " " . $horaIda);
                $fechaHoraFormatSalida = $fechaHoraFormatSalida->format('Y-m-d H:i:s');

                //hora llegada
                $f = new DateTime($fechaHoraFormatSalida);
                $fechaHoraLlegada = new DateInterval('PT' . intval($intervalo[0]) . 'H' . intval($intervalo[1]) . 'M');
                $fechaHoraLlegada = $f->add($fechaHoraLlegada);
                $fechaHoraFormatLlegada = $fechaHoraLlegada->format('Y-m-d H:i:s');

                // Creamos el vuelo
                $vuelo = new Vuelo();
                $vuelo->vuelo_Num_Pasajeros = 90;
                $vuelo->vuelo_Fecha_Hora_Salida = $fechaHoraFormatSalida;
                $vuelo->vuelo_Fecha_Hora_Llegada = $fechaHoraFormatLlegada;
                $vuelo->vuelo_AeropuertoSalida = $origen;
                $vuelo->vuelo_AeropuertoLlegada = $destino;
                $vuelos->push($vuelo);
            }
        }

        // Devolvemos los vuelos encontrados
        return response()->json($vuelos);
    }

    // Función para buscar vuelos ida/vuelta
    public function buscarVueloIdaVuelta(Request $request)
    {
        // Recogemos los datos del formulario
        $origen = $request->origen;
        $destino = $request->destino;
        $fechaIda = $request->fechaIda;
        $fechaVuelta = $request->fechaVuelta;
        $intervalo = $request->intervalo;

        // Realizamos la consulta a la base de datos
        $vuelos = Vuelo::where('vuelo_AeropuertoSalida', $origen)
            ->where('vuelo_AeropuertoLlegada', $destino)
            ->whereDate('vuelo_Fecha_Hora_Salida', $fechaIda)
            ->get();

        if ($vuelos->count() < 5) {
            $cantidadAnadir = 5 - $vuelos->count(); // Calculamos cuantos vuelos faltan
            for ($i = 0; $i < $cantidadAnadir; $i++) {
                //Creamos vuelos aleatorios
                //hora ida
                $horaIda = $this->creaHoras();
                $fechaHoraFormatSalida = DateTime::createFromFormat('Y-m-d H:i:s', $fechaIda . " " . $horaIda);
                $fechaHoraFormatSalida = $fechaHoraFormatSalida->format('Y-m-d H:i:s');

                //hora llegada
                $f = new DateTime($fechaHoraFormatSalida);
                $fechaHoraLlegada = new DateInterval('PT' . intval($intervalo[0]) . 'H' . intval($intervalo[1]) . 'M');
                $fechaHoraLlegada = $f->add($fechaHoraLlegada);
                $fechaHoraFormatLlegada = $fechaHoraLlegada->format('Y-m-d H:i:s');

                $vuelo = new Vuelo();
                $vuelo->vuelo_Num_Pasajeros = 90;
                $vuelo->vuelo_Fecha_Hora_Salida = $fechaHoraFormatSalida;
                $vuelo->vuelo_Fecha_Hora_Llegada = $fechaHoraFormatLlegada;
                $vuelo->vuelo_AeropuertoSalida = $origen;
                $vuelo->vuelo_AeropuertoLlegada = $destino;
                $vuelos->push($vuelo);
            }
        }

        // Devolvemos los vuelos encontrados
        return response()->json($vuelos);
    }

    public function creaHoras()
    {
        $minutos = [];
        $horas = [];

        // Minutos
        for ($i = 0; $i < 60; $i += 5) {
            array_push($minutos, $i);
        }

        // Horas de la mañana
        for ($i = 0; $i < 2; $i++) {
            $h = ["06", "07", "08", "09", "10", "11"];
            $elementoAleatorio = $h[array_rand($h)];
            array_push($horas, $elementoAleatorio);
        }

        // Horas de la tarde
        for ($i = 0; $i < 2; $i++) {
            $h = ["12", "13", "14", "15", "16", "17", "18", "19", "20", "21"];
            $elementoAleatorio = $h[array_rand($h)];
            array_push($horas, $elementoAleatorio);
        }

        // Horas de la noche
        $h = ["22", "23", "00", "01", "02", "03", "04", "05"];
        $elementoAleatorio = $h[array_rand($h)];
        array_push($horas, $elementoAleatorio);

        $horaCreada = $horas[array_rand($horas)] . ":" . str_pad($minutos[array_rand($minutos)], 2, '0', STR_PAD_LEFT) . ":00";
        return $horaCreada;
    }
}
