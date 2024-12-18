<?php

use App\Livewire\Perfil;
use App\Livewire\Auth\CambiarContrasena;
use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Inicio::class)->name('inicio');
Route::get('/inicio', function () {
    return redirect()->route('inicio');
});
Route::get('/home', function () {
    return redirect()->route('inicio');
});
Route::get('/inicio', function () {
    return redirect()->route('inicio');
});

Route::get('/categorias', \App\Livewire\MostrarCategoria::class)->name('categorias');
Route::get('/marcas', \App\Livewire\MostrarMarca::class)->name('marcas');
Route::get('/reporteproblema', \App\Livewire\ReporteProblemas::class)->name('reporteproblema');
Route::get('/quejasugerencia', \App\Livewire\QuejaSugerencias::class)->name('quejasugerencia');
Route::get('/productos/{categoria?}/{marca?}', \App\Livewire\Productos::class)->name('productos');
Route::get('/producto/{id}', \App\Livewire\DetalleProducto::class)->name('producto');
Route::get('/carrito', \App\Livewire\Carrito::class)->name('carrito');
Route::get('/ordenes/{estado?}', \App\Livewire\ListaOrdenes::class)->name('ordenes');
Route::get('/mi_orden/{id}', \App\Livewire\Ordenes::class)->name('mi_orden');
Route::get('/exportarClaveRecuperacion', [Perfil::class, 'exportarClaveRecuperacion']);

Route::middleware('auth')->group(function () {
    Route::get('logout', function () {
        auth()->logout();
        return redirect()->route('inicio');
    });
});

Route::middleware('guest')->group(function () {
    Route::get('/registro', \App\Livewire\Auth\Registro::class)->name('registro');
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    Route::get('/verificarclave', \App\Livewire\Auth\VerificarClave::class)->name('verificarclave');
    Route::get('/cambiarcontrasena/{token?}', CambiarContrasena::class)->name('cambiarcontrasena');
    Route::get('/admin/login', function () {
        return redirect()->route('login');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('logout', function () {
        auth()->logout();
        return redirect()->route('inicio');
    })->name('logout');

    Route::get('/admin/login', function () {
        return redirect()->route('login');
    });

    route::post('/auth/logout', [\App\Http\Controllers\Filament\LogoutController::class, 'logout'])->name('filament.admin.auth.logout');

    Route::get('/cupones', \App\Livewire\DetalleCupon::class)->name('cupones');

    Route::get('/perfil', \App\Livewire\Perfil::class)->name('perfil');

    Route::get('/notificaciones', \App\Livewire\Notificaciones::class)->name('notificaciones');

    Route::get('/favoritos', \App\Livewire\Favoritos::class)->name('favoritos');

    Route::get('/editarperfil', \App\Livewire\EditarPerfil::class)->name('editarperfil');
    Route::get('/pedido', \App\Livewire\DetallePedido::class)->name('pedido');
    Route::get('/exito', \App\Livewire\ExitoPedido::class)->name('exito');
});


