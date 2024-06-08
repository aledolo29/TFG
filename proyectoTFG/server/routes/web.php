<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use PhpParser\Builder\Function_;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Para mostrar el formulario de pago
Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');

// Para dar por correcto el pago
Route::get('/success', [PaymentController::class, 'sendEmail'])->name('success');

// Para obtener token
Route::post('/token', function () {
    return response()->json(['token' => csrf_token()]);
})->name('token');

// Para generar pdf
Route::post('/descargarFactura', [PDFController::class, 'generatePDF'])->name('descargarFactura');


require __DIR__ . '/auth.php';
