<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VueloController;
use Illuminate\Support\Facades\Route;

// Ruta login
// Route::get('/login', function () {
//     return view('login');
// })->name('login');

// Ruta registro cliente
Route::middleware(['api'])->group(function () {
    // Ruta registro cliente
    Route::post('/registroCliente', [ClienteController::class, 'guardarCliente'])->name('registroCliente');

    // Ruta login cliente
    Route::post('/loginCliente', [ClienteController::class, 'comprobarLogin'])->name('loginCliente');

    // Comprobar Session Login
    Route::get('/comprobarSesion', [ClienteController::class, 'comprobarSesion'])->name('comprobarSesion');

    // Buscar vuelo ida
    Route::post('/buscarVueloIda', [VueloController::class, 'buscarVueloIda'])->name('buscarVueloIda');

    // Buscar vuelo ida/vuelta
    Route::post('/buscarVueloIdaVuelta', [VueloController::class, 'buscarVueloIdaVuelta'])->name('buscarVueloIdaVuelta');

    // Obtener Cliente
    Route::post('/obtenerCliente', [ClienteController::class, 'obtenerCliente'])->name('obtenerCliente');

    // Pagar vuelo
    Route::post('/pagarVuelo', [VueloController::class, 'pagarVuelo'])->name('pagarVuelo');
});
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');