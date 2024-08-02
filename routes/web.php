<?php

use App\Http\Controllers\ChirpController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Chirp;

/*
DB::listen(function ($query) {
    dump($query->sql);
});
*/

Route::view('/', 'welcome')->name('welcome');

# Las rutas agrupadas mediante (group) heredan el middleware 'auth'
Route::middleware('auth')->group(function () { # Un middleware es una pieza de codigo que se ejecuta ENTRE la solicitud y la respuesta. En este caso se aplica un middleware, 'auth'
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/chirps', [ChirpController::class, 'index'])
        ->name('chirps.index');

    Route::post('/chirps', [ChirpController::class, 'store'])
        ->name('chirps.store');

    Route::get('/chirps/{chirp}/edit', [ChirpController::class, 'edit'])
        ->name('chirps.edit');

    Route::put('/chirps/{chirp}', [ChirpController::class, 'update'])
        ->name('chirps.update');

    Route::delete('/chirps/{chirp}', [ChirpController::class, 'destroy'])
        ->name('chirps.destroy');
});

require __DIR__.'/auth.php';

/*
Route::get('/chirps/{chirp?}', function ($chirp = NULL) {
    if($chirp == "2"){
        return to_route('chirps.index'); # Con to_route('nombre-del-sitio') Redireccionamos a una pagina que queramos.
    }
    return "Bienvenido a chirp, " . $chirp;
});
*/