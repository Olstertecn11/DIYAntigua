<?php

use App\Http\Controllers\Private\AdminController;
use App\Http\Controllers\Private\SocioController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    if ($request->has('ref')) {
        session(['afiliado_referido' => $request->query('ref')]);
    }
    return view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




Route::group(['prefix' => 'socios'], function () {
    Route::get('/login', [SocioController::class, 'login'])->name('socios.login');

    Route::middleware(['socio'])->group(function () {
        Route::get('/dashboard', [SocioController::class, 'dashboard'])->name('socios.dashboard');
        // Agregamos logout específico si prefieres, o usa el general de Auth::routes()
    });
});


Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'postLogin'])->name('admin.login.post');

    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

        // Esta es la ruta que te hace falta:
        Route::post('/afiliados', [AdminController::class, 'storeAfiliado'])->name('admin.afiliados.store');
    });
});
