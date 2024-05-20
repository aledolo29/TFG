<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VuelosController;
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

    // Aeropuerto ida
    Route::get('/buscarAeropuerto', [VuelosController::class, 'buscarAeropuerto'])->name('buscarAeropuerto');
});
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');