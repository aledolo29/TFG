<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function guardarCliente(Request $request)
    {
        $comprobarCliente = Cliente::where('cliente_Usuario', $request->usuario)->orWhere('cliente_Correo', $request->correo)->first();
        $arrayValores = ["cliente_Usuario" => $request->usuario, "cliente_Correo" => $request->correo];
        if ($comprobarCliente != null) {
            foreach ($arrayValores as $key => $value) {
                if ($comprobarCliente->$key == $value) {
                    $campoRepetido =  explode('_', $key);
                    return response()->json([
                        'error' => 'El ' . strtolower($campoRepetido[1]) . ' ya esta registrado.'
                    ]);
                }
            }
        } else {
            $cliente = new Cliente();
            $cliente->cliente_Nombre = $request->nombre;
            $cliente->cliente_Apellidos = $request->apellidos;
            $cliente->cliente_Usuario = $request->usuario;
            $cliente->cliente_Password = $request->password;
            $cliente->cliente_DNI = $request->dni;
            $cliente->cliente_Correo = $request->correo;
            $cliente->cliente_Telefono = $request->telefono;
            $cliente->save();
            return response()->json([
                'correcto' => 'Usuario ' . $request->usuario . ' registrado correctamente. Espere para ser redirigido.'
            ]);
        }
    }

    public function comprobarLogin(Request $request)
    {
        $comprobarCliente = Cliente::where('cliente_Correo', $request->user)->orWhere('cliente_Usuario', $request->user)->first();
        if ($comprobarCliente != null) {
            if ($comprobarCliente->cliente_Password == $request->password) {
                return response()->json([
                    'correcto' => 'Usuario ' . $request->usuario . ' logueado correctamente. Espere para ser redirigido.', 'nombre' =>  $comprobarCliente->cliente_Nombre, 'idCliente' => $comprobarCliente->cliente_Id
                ]);
            } else {
                return response()->json([
                    'error' => 'Contraseña incorrecta.'
                ]);
            }
        } else {
            return response()->json([
                'error' => 'Usuario no registrado.'
            ]);
        }
    }

    public function obtenerCliente(Request $request)
    {
        $cliente = Cliente::where('cliente_Id', $request->idCliente)->first();
        return response()->json([
            'cliente' => $cliente
        ]);
    }
}
