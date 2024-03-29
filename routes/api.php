<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(\App\Http\Controllers\AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::get('donacije', [\App\Http\Controllers\DonacijeController::class, 'index']); // zbog ?mojeDonacije
Route::post('donacije/{id}/slike', [\App\Http\Controllers\SlikeController::class, 'store']);
Route::delete('donacije/{id}/slike/{slika_id}', [\App\Http\Controllers\SlikeController::class, 'destroy']);
Route::resource('donacije',\App\Http\Controllers\DonacijeController::class)->except([
    'index', 'create', 'edit'
]);

Route::post('akcije/{id}/prijava', [\App\Http\Controllers\AkcijeController::class, 'prijava']);
Route::post('akcije/{id}/odjava', [\App\Http\Controllers\AkcijeController::class, 'odjava']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::resource('akcije',\App\Http\Controllers\AkcijeController::class)->except([
        'create', 'edit'
    ]);
});
