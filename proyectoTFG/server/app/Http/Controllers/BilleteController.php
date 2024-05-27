<?php

namespace App\Http\Controllers;

use App\Models\Billete;
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
}
