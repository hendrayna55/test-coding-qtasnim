<?php

use App\Http\Controllers\DataPenjualanController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function(){
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/dashboard', [HomeController::class, 'index']);
    
    Route::prefix('data-penjualan')->group(function(){
        Route::get('/', [DataPenjualanController::class, 'index']);
        Route::post('/', [DataPenjualanController::class, 'store']);
        Route::put('/{id}', [DataPenjualanController::class, 'update']);
        Route::delete('/{id}', [DataPenjualanController::class, 'destroy']);
    });

    Route::get('/chart-penjualan', [DataPenjualanController::class, 'chart']);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
