<?php

namespace App\Http\Controllers;

use App\Models\Billete;
use App\Models\Vuelo;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;

class VueloController extends Controller
{
    // Función para buscar vuelos ida
    public function buscarVuelo(Request $request)
    {

        // Recogemos los datos del formulario
        $origen = $request->origen;
        $destino = $request->destino;
        $fecha = $request->fecha;
        $pasajeros = $request->pasajeros;
        $intervalo = $request->intervalo;

        // Realizamos la consulta a la base de datos
        $vuelos = Vuelo::where('vuelo_AeropuertoSalida', $origen)
            ->where('vuelo_AeropuertoLlegada', $destino)
            ->whereDate('vuelo_Fecha_Hora_Salida', $fecha)
            ->get();

        // Comprobar si existen billetes para los vuelos encontrados
        foreach ($vuelos as $key => $v) {
            $numBilletes = Billete::where('billete_Vuelo_IdFK', $v->vuelo_Id)->count();
            if ($numBilletes >= 90) {
                $vuelos->forget($key);
            }
        }   

        foreach ($vuelos as $v) {
            $billete = Billete::where('billete_Vuelo_IdFK', $v->vuelo_Id)->first();
            if ($billete) {
                $asiento = $billete->billete_Asiento;
                if (intval(substr($asiento, 1)) <= 6) {
                    $v->precio = $billete->billete_Precio / 1.5;
                } else if (intval(substr($asiento, 1)) > 6 && intval(substr($asiento, 1)) <= 10) {
                    $v->precio = $billete->billete_Precio / 3;
                } else {
                    $v->precio = $billete->billete_Precio;
                }
            }
            $v->vuelo_Num_Pasajeros = $pasajeros;
        }

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
                $vuelo->vuelo_Num_Pasajeros = $pasajeros;
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

    public function guardarVueloBillete(Request $request)
    {
        $asientos = $request->asientos;
        $vueloSeleccionado = $request->vueloSeleccionado;
        $atributos = [
            'vuelo_Fecha_Hora_Salida' => DateTime::createFromFormat('Y-m-d H:i:s', $vueloSeleccionado['vuelo_Fecha_Hora_Salida']),
            'vuelo_Fecha_Hora_Llegada' => DateTime::createFromFormat('Y-m-d H:i:s', $vueloSeleccionado['vuelo_Fecha_Hora_Llegada']),
            'vuelo_AeropuertoSalida' => $vueloSeleccionado['vuelo_AeropuertoSalida'],
            'vuelo_AeropuertoLlegada' => $vueloSeleccionado['vuelo_AeropuertoLlegada'],
            // Agrega aquí cualquier otro atributo que quieras buscar
        ];
        $vuelo = Vuelo::firstOrCreate($atributos);
        foreach ($asientos as $asiento) {
            $billete = new Billete();
            $billete->billete_Vuelo_IdFK = $vuelo->vuelo_Id;
            $billete->billete_Cliente_IdFK = $request->idCliente;
            $billete->billete_Asiento = $asiento;
            $precio = $vueloSeleccionado['precio'];
            $asiento = substr($asiento, 1);
            if ($asiento <= 6) {
                $precio = $precio * 1.5;
            } else if ($asiento > 6 && $asiento <= 10) {
                $precio = $precio * 3;
            }
            $billete->billete_Precio = $precio;
            $billete->save();
        }
    }
    public function obtenerVuelo(Request $request)
    {
        $vuelo = Vuelo::where('vuelo_Id', $request->vuelo_Id)->first();
        return response()->json($vuelo);
    }
}
