<?php

namespace App\Http\Controllers;

use App\Models\Billete;
use App\Models\Vuelo;
use Illuminate\Http\Request;

class BilleteController extends Controller
{
    public function obtenerBilletes(Request $request)
    {
        $billete = new Billete();
        $idCliente = $request->idCliente;

        $billetes = $billete->where('billete_cliente_IdFK', $idCliente)->get();

        return response()->json($billetes);
    }

    public function eliminarBillete(Request $request)
    {
        $billete = new Billete();
        $billete_Id = $request->billete_Id;

        $b = Billete::where('billete_Id', $billete_Id)->first();
        $billeteVueloFK = Billete::where('billete_vuelo_IdFK', $b->billete_vuelo_IdFK)->get();
        if($billeteVueloFK->count() == 1){
            $vuelo = Vuelo::where('vuelo_Id', $b->billete_vuelo_IdFK)->first();
            $vuelo->delete();
        }

        $billete->where('billete_Id', $billete_Id)->delete();

        return response()->json('Billete eliminado');
    }

    public function comprobarAsientos(Request $request)
    {
        $billeteExistente = Billete::where('billete_Vuelo_IdFK', $request->vuelo_Id)->get();
        if ($billeteExistente->count() == 0) {
            return response()->json(false);
        } else {
            $asientos = [];
            foreach ($billeteExistente as $b) {
                $asientos[] = $b->billete_Asiento;
            }
            return response()->json($asientos);
        }
    }
}
