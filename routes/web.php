<?php

use App\Http\Controllers\Private\SocioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




// create a group routes for socios
Route::group(['prefix' => 'socios'], function () {
    Route::get('/', [SocioController::class, 'login'])->name('socios.login');
    Route::get('/login', [SocioController::class, 'login'])->name('socios.login');
});

