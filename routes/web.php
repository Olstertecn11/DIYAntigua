<?php

use App\Http\Controllers\Private\AdminController;
use App\Http\Controllers\Private\RutaController;
use App\Http\Controllers\Private\SocioController;
use App\Http\Controllers\Private\ConductorController;
use App\Http\Controllers\Private\LugarController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// --- RUTA PÚBLICA / REFERIDOS ---
Route::get('/', function (Request $request) {
    if ($request->has('ref')) {
        session(['afiliado_referido' => $request->query('ref')]);
    }
    return view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// --- SECCIÓN SOCIOS ---
Route::group(['prefix' => 'socios'], function () {
    Route::get('/login', [SocioController::class, 'login'])->name('socios.login');

    Route::middleware(['socio'])->group(function () {
        Route::get('/dashboard', [SocioController::class, 'dashboard'])->name('socios.dashboard');
        // Futuras rutas de socios aquí (ej: reservas.create)
    });
});

// --- SECCIÓN ADMINISTRADOR ---
Route::group([
    'prefix' => 'admin',
    'middleware' => ['admin'],
    'as' => 'admin.' // Esto añade automáticamente "admin." a todos los nombres de rutas internos
], function () {

    // Login y Dashboard Principal
    Route::get('/', [AdminController::class, 'login'])->name('login')->withoutMiddleware(['admin']);
    Route::post('/login', [AdminController::class, 'postLogin'])->name('login.post')->withoutMiddleware(['admin']);
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    // OPERACIONES (Gestión Relacional)

    // Lugares y Hoteles
    Route::resource('lugares', LugarController::class);

    // Rutas (Tarifario basado en Lugares)
    Route::resource('rutas', RutaController::class);

    // Gestión de Conductores
    Route::get('/conductores', [ConductorController::class, 'index'])->name('conductores.index');
    Route::post('/conductores', [ConductorController::class, 'store'])->name('conductores.store');
    Route::delete('/conductores/{conductore}', [ConductorController::class, 'destroy'])->name('conductores.destroy');

    // Gestión de Reservas
    Route::get('/reservas', [AdminController::class, 'reservas'])->name('reservas.index');

    // NEGOCIO

    // Gestión de Afiliados (Socios)
    Route::get('/afiliados', [AdminController::class, 'indexAfiliados'])->name('afiliados.index');
    Route::post('/afiliados', [AdminController::class, 'storeAfiliado'])->name('afiliados.store');

    // Pagos y Comisiones (Placeholder)
    Route::get('/pagos', [AdminController::class, 'pagos'])->name('pagos.index');
});
