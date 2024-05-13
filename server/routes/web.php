<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;


Route::get('/', function () {
    return view('welcome');
});
Route::post('/comprobarEmpleado', [EmpleadoController::class, 'comprobarEmpleado']);
