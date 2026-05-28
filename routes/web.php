<?php

use App\Http\Controllers\Private\AdminController;
use App\Http\Controllers\Private\AdminProfileController;
use App\Http\Controllers\Private\AdminReservationController;
use App\Http\Controllers\Private\RutaController;
use App\Http\Controllers\Private\SocioController;
use App\Http\Controllers\Private\ConductorController;
use App\Http\Controllers\Private\LugarController;
use App\Http\Controllers\Private\VehiculoController;
use App\Http\Controllers\Public\ProfileController;
use App\Http\Controllers\Public\ReservaController;
use App\Http\Controllers\Public\ReservaEmailVerificationController;
use App\Http\Controllers\Public\UserReservationController;
use App\Models\Ruta;
use App\Services\Affiliates\ReferralTracker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// --- RUTA PÚBLICA / REFERIDOS ---
Route::get('/', function (Request $request, ReferralTracker $referrals) {
    $referrals->capture($request);

    $rutas = Cache::remember('welcome:rutas-activas', now()->addMinutes(10), function () {
        return Ruta::where('activa', true)->with(['origen', 'destino'])->get();
    });

    return view('welcome', compact('rutas'));
})->name('welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->prefix('perfil')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
    Route::put('/password', [ProfileController::class, 'password'])->name('password');
});

Route::middleware('auth')->prefix('mis-reservas')->name('reservas.mine.')->group(function () {
    Route::get('/', [UserReservationController::class, 'index'])->name('index');
    Route::get('/{reserva}', [UserReservationController::class, 'show'])->name('show');
    Route::post('/{reserva}/cancelar', [UserReservationController::class, 'cancel'])
        ->middleware('throttle:4,1')
        ->name('cancel');
});


// Flujo de Reservaciones
Route::prefix('reservas')->group(function () {

    Route::get('/cotizar', [ReservaController::class, 'cotizar'])->name('reservas.cotizar');
    Route::get('/detalles', [ReservaController::class, 'detalles'])->name('reservas.detalles');
    Route::post('/confirmar', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/confirmar/{codigo}', [ReservaController::class, 'confirmar'])->name('reservas.confirmar');
    Route::get('/pdf/{codigo}', [ReservaController::class, 'descargarPDF'])->name('reservas.pdf');
    Route::post('/email-code/send', [ReservaEmailVerificationController::class, 'send'])
    ->name('reservas.email-code.send');
    Route::post('/email-code/verify', [ReservaEmailVerificationController::class, 'verify'])
    ->name('reservas.email-code.verify');

});

require __DIR__.'/payments.php';

Route::group(['prefix' => 'socios'], function () {
    Route::get('/login', [SocioController::class, 'login'])->name('socios.login');

    Route::middleware(['socio'])->group(function () {
        Route::get('/dashboard', [SocioController::class, 'dashboard'])->name('socios.dashboard');
        Route::get('/perfil', [AdminProfileController::class, 'edit'])->name('socios.profile.edit');
        Route::put('/perfil', [AdminProfileController::class, 'update'])->name('socios.profile.update');
        Route::put('/perfil/password', [AdminProfileController::class, 'password'])->name('socios.profile.password');
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
    Route::get('/perfil', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/perfil/password', [AdminProfileController::class, 'password'])->name('profile.password');

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

    Route::get('/reservas', [AdminReservationController::class, 'index'])->name('reservas.index');
    Route::get('/reservas/{reserva}', [AdminReservationController::class, 'show'])->name('reservas.show');
    Route::post('/reservas/{reserva}/cancelar', [AdminReservationController::class, 'cancel'])
        ->middleware('throttle:8,1')
        ->name('reservas.cancel');

    // NEGOCIO

    Route::get('/afiliados', [AdminController::class, 'indexAfiliados'])->name('afiliados.index');
    Route::post('/afiliados', [AdminController::class, 'storeAfiliado'])->name('afiliados.store');

    // Pagos y Comisiones (Placeholder)
    Route::get('/pagos', [AdminController::class, 'pagos'])->name('pagos.index');

});
