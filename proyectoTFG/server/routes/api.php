<?php

use App\Http\Controllers\BilleteController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VueloController;
use Illuminate\Support\Facades\Route;

// Ruta login
// Route::get('/login', function () {
//     return view('login');
// })->name('login');

// Ruta registro cliente
// Route::middleware(['api'])->group(function () {
// Ruta registro cliente
Route::post('/registroCliente', [ClienteController::class, 'guardarCliente'])->name('registroCliente');

// Ruta login cliente
Route::post('/loginCliente', [ClienteController::class, 'comprobarLogin'])->name('loginCliente');

// Comprobar Session Login
Route::get('/comprobarSesion', [ClienteController::class, 'comprobarSesion'])->name('comprobarSesion');

// Buscar vuelo
Route::post('/buscarVuelo', [VueloController::class, 'buscarVuelo'])->name('buscarVuelo');

// Obtener Cliente
Route::post('/obtenerCliente', [ClienteController::class, 'obtenerCliente'])->name('obtenerCliente');

// Pagar vuelo
Route::post('/pagarVuelo', [VueloController::class, 'pagarVuelo'])->name('pagarVuelo');

// Guardar vuelo
Route::post('/guardarVueloBillete', [VueloController::class, 'guardarVueloBillete'])->name('guardarVueloBillete');

// Obtener billetes
Route::post('/obtenerBilletes', [BilleteController::class, 'obtenerBilletes'])->name('obtenerBilletes');

// Obtener vuelo
Route::post('/obtenerVuelo', [VueloController::class, 'obtenerVuelo'])->name('obtenerVuelo');

// Cancelar billete
Route::post('/eliminarBillete', [BilleteController::class, 'eliminarBillete'])->name('eliminarBillete');

// Rceuperar contraseña
Route::post('/recuperarContrasena', [ClienteController::class, 'recuperarContrasena'])->name('recuperarContrasena');

// Comprobar asientos
Route::post('/comprobarAsientos', [BilleteController::class, 'comprobarAsientos'])->name('comprobarAsientos');
// });
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');