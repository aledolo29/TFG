<?php

use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;

// Ruta login
Route::get('/login', function () {
    return view('login');
})->name('login');

// Ruta registro cliente
Route::post('/registroCliente', [ClienteController::class, 'guardarCliente'])->name('registroCliente');
Route::post('/loginCliente', [ClienteController::class, 'comprobarLogin'])->name('loginCliente');

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
