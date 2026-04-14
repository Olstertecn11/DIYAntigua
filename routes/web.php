<?php

use App\Http\Controllers\Private\AdminController;
use App\Http\Controllers\Private\RutaController;
use App\Http\Controllers\Private\SocioController;
use App\Http\Controllers\Private\ConductorController;
use App\Http\Controllers\Private\LugarController;
use App\Http\Controllers\Private\VehiculoController;
use App\Http\Controllers\Public\ReservaController;
use App\Models\Ruta;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// --- RUTA PÚBLICA / REFERIDOS ---
Route::get('/', function (Request $request) {
    if ($request->has('ref')) {
        session(['afiliado_referido' => $request->query('ref')]);
    }
    $rutas = Ruta::where('activa', true)->with(['origen', 'destino'])->get();
    return view('welcome', compact('rutas'));
})->name('welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Flujo de Reservaciones
Route::prefix('reservas')->group(function () {

    Route::get('/cotizar', [ReservaController::class, 'cotizar'])->name('reservas.cotizar');
    Route::get('/detalles', [ReservaController::class, 'detalles'])->name('reservas.detalles');
    Route::post('/confirmar', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/confirmar/{codigo}', [ReservaController::class, 'confirmar'])->name('reservas.confirmar');

});

Route::group(['prefix' => 'socios'], function () {
    Route::get('/login', [SocioController::class, 'login'])->name('socios.login');

    Route::middleware(['socio'])->group(function () {
        Route::get('/dashboard', [SocioController::class, 'dashboard'])->name('socios.dashboard');
    });
});

// --- SECCIÓN ADMINISTRADOR ---
Route::group([
    'prefix' => 'admin',
    'middleware' => ['admin'],
    'as' => 'admin.'
], function () {

    // Login y Dashboard Principal
    Route::get('/', [AdminController::class, 'login'])->name('login')->withoutMiddleware(['admin']);
    Route::post('/login', [AdminController::class, 'postLogin'])->name('login.post')->withoutMiddleware(['admin']);
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    Route::resource('lugares', LugarController::class);

    Route::resource('rutas', RutaController::class);

    // VEHICULOS
    Route::get('/vehiculos', [VehiculoController::class, 'index'])->name('vehiculos.index');
    Route::post('/vehiculos', [VehiculoController::class, 'store'])->name('vehiculos.store');
    Route::put('/vehiculos/{vehiculo}', [VehiculoController::class, 'update'])->name('vehiculos.update');
    Route::delete('/vehiculos/{vehiculo}', [VehiculoController::class, 'destroy'])->name('vehiculos.destroy');

    Route::get('/conductores', [ConductorController::class, 'index'])->name('conductores.index');
    Route::post('/conductores', [ConductorController::class, 'store'])->name('conductores.store');
    Route::delete('/conductores/{conductore}', [ConductorController::class, 'destroy'])->name('conductores.destroy');

    Route::get('/reservas', [AdminController::class, 'reservas'])->name('reservas.index');

    // NEGOCIO

    Route::get('/afiliados', [AdminController::class, 'indexAfiliados'])->name('afiliados.index');
    Route::post('/afiliados', [AdminController::class, 'storeAfiliado'])->name('afiliados.store');

    // Pagos y Comisiones (Placeholder)
    Route::get('/pagos', [AdminController::class, 'pagos'])->name('pagos.index');

});
