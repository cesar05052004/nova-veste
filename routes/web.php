<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PedidoController;
use App\Http\Controllers\Admin\ProductoController;
use App\Models\Pedido;
use App\Http\Controllers\Admin\TiendaController;
use Illuminate\Support\Facades\Http;

function notificarPorTelegram($mensaje)
{
    $token = '7621985084:AAHibdc-wul9fSDd71eQ8UQj0kgZiqBKQws'; // tu token de bot
    $chat_id = '5257846261'; // tu ID de chat (el que apareció cuando enviaste "Hola")

    $url = "https://api.telegram.org/bot$token/sendMessage";

    $data = [
        'chat_id' => $chat_id,
        'text'    => $mensaje,
        'parse_mode' => 'HTML',
    ];

    try {
        Http::post($url, $data);
    } catch (\Exception $e) {
        \Log::error('Error al enviar mensaje de Telegram: ' . $e->getMessage());
    }
}

// Página principal (catálogo desde base de datos)
Route::get('/', [TiendaController::class, 'inicio'])->name('inicio');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Auth routes
require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Vista formulario para pedir producto
    Route::get('/pedir-producto', function () {
        return view('tienda.pedido');
    })->name('pedir.producto');

    // Guardar pedido y notificar por Telegram
    Route::post('/pedir-producto', function (Request $request) {
        $request->validate([
            'producto' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:1',
            'direccion' => 'required|string|max:255',
            'telefono'  => 'required|digits:10', // validamos también el teléfono
        ]);

        $pedido = Pedido::create([
            'user_id'   => auth()->id(),
            'producto'  => $request->producto,
            'cantidad'  => $request->cantidad,
            'direccion' => $request->direccion,
            'telefono'  => $request->telefono,
        ]);

        // Mensaje para el administrador
        $mensaje = "<b>NUEVO PEDIDO</b>\n";
        $mensaje .= "👤 Usuario: " . auth()->user()->name . "\n";
        $mensaje .= "📦 Producto: " . $request->producto . "\n";
        $mensaje .= "🔢 Cantidad: " . $request->cantidad . "\n";
        $mensaje .= "📍 Dirección: " . $request->direccion;
        $mensaje .= "📞 Teléfono: " . $request->telefono;

        notificarPorTelegram($mensaje);

        return back()->with('success', '¡Pedido exitoso! En breve nos comunicaremos con usted.');
    })->name('pedir.producto.post');

    // Vista admin pedidos
    Route::get('/admin/pedidos', [PedidoController::class, 'index'])->name('admin.pedidos');
});

// Admin productos
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/crear-producto', [ProductoController::class, 'crear'])->name('admin.crear-producto');
    Route::post('/guardar-producto', [ProductoController::class, 'guardar'])->name('admin.guardar-producto');
});

Route::delete('/eliminar-producto/{id}', [ProductoController::class, 'eliminar'])->name('admin.eliminar-producto');
Route::get('/admin/productos/{id}/editar', [App\Http\Controllers\Admin\ProductoController::class, 'editar'])->name('admin.editar-producto');
Route::put('/admin/productos/{id}', [App\Http\Controllers\Admin\ProductoController::class, 'actualizar'])->name('admin.actualizar-producto');

Route::patch('/admin/pedidos/{pedido}/completar', function (Pedido $pedido) {
    $pedido->update(['completado' => true]);
    return back()->with('success', 'Pedido marcado como completado');
})->name('pedidos.completar');

Route::patch('/admin/pedidos/{pedido}/reabrir', function (Pedido $pedido) {
    $pedido->update(['completado' => false]);
    return back()->with('success', 'Pedido reabierto');
})->name('pedidos.reabrir');

// Forzar redeploy en Render
