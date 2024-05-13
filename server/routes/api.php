<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController; // Import the EmpleadoController class

Route::post('/comprobarEmpleado', [EmpleadoController::class, 'comprobarEmpleado']);
