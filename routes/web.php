<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\PartController;
use App\Models\Car;
use App\Models\Part;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        'totalCars' => Car::count(),
        'totalParts' => Part::count()
    ]);
})->name('home');

Route::controller(CarController::class)->prefix('cars')->name('cars.')->group(function (){
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::patch('/{car}', 'update')->name('update');
    Route::delete('/{car}', 'destroy')->name('delete');
});

Route::controller(PartController::class)->prefix('parts')->name('parts.')->group(function (){
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::patch('/{part}', 'update')->name('update');
    Route::delete('/{part}', 'destroy')->name('delete');
});

Route::fallback(function () {
   return redirect('/');
});
