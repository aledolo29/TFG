<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EmpleadoController extends Controller
{
    public function comprobarEmpleado(Request $request)
    {
        $user = $request["empl_Usuario"];
        $pass = $request["empl_Password"];
        $compruebaUsuario = Empleado::where('empl_Usuario', $user)->where('empl_Password', $pass)->first();

        if ($compruebaUsuario) {
            return redirect()->route('correcto');
        } else {
            return redirect()->route('error');
        }
    }
}
