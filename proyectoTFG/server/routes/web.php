<?php

<<<<<<< HEAD
use App\Http\Controllers\ProfileController;
=======
>>>>>>> db327f805d329124414310ad257da63d5a139e1e
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
<<<<<<< HEAD

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
=======
>>>>>>> db327f805d329124414310ad257da63d5a139e1e
