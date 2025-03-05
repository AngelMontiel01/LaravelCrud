<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CiudadController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ComercialController;
use App\Http\Controllers\EstatusController;

Route::get('/', function () {
    // Si el usuario está autenticado, lo redirige a home, sino lo manda al login
    return auth()->check() ? redirect()->route('home') : redirect()->route('login');
});


Auth::routes();




///vistas
Route::get('/client', [ClienteController::class, 'clientesView'])->name('clientes');
// En routes/web.php
Route::get('/pedido', [PedidoController::class, 'pedidosView'])->name('pedidos')->middleware('auth');

Route::get('/comercio', [ComercialController::class, 'comercialView'])->name('comerciales');

//rutas de catalagos
Route::get('/clientes', [ClienteController::class, 'index']);
Route::get('/comerciales', [ComercialController::class, 'index']);
Route::get('/estatus', [EstatusController::class, 'index']);
Route::get('/categoria', [CategoriaController::class, 'index']);
Route::get('/ciudad', [CiudadController::class, 'index']);

//  Pedidos
Route::get('/pedidos', [PedidoController::class, 'index'])->middleware('rol:1,2,3'); // Todos pueden ver
Route::post('/pedidos', [PedidoController::class, 'store'])->middleware('rol:1'); // Solo rol 1 puede crear
Route::get('/pedidos/{id}', [PedidoController::class, 'edit'])->middleware('rol:1,2,3'); // Todos pueden ver un pedido específico
Route::put('/pedidos/{id}', [PedidoController::class, 'update'])->middleware('rol:1,2'); // rol 1 puede modificar todo, Comercial solo cambia estatus
Route::delete('/pedidos/{id}', [PedidoController::class, 'destroy'])->middleware('rol:1'); // Solo rol 1 puede eliminar

//  Cliente
Route::get('/cliente', [ClienteController::class, 'index'])->middleware('rol:1,2,3'); // Todos pueden ver
Route::post('/cliente', [ClienteController::class, 'store'])->middleware('rol:2'); // Solo rol 2 puede crear
Route::get('/cliente/{id}', [ClienteController::class, 'edit'])->middleware('rol:1,2,3'); // Todos pueden ver
Route::put('/cliente/{id}', [ClienteController::class, 'update'])->middleware('rol:2'); // Solo rol 2 puede modificar
Route::delete('/cliente/{id}', [ClienteController::class, 'destroy'])->middleware('rol:2'); // Solo rol 2 puede eliminar

//  Comercial
Route::get('/comercial', [ComercialController::class, 'index'])->middleware('rol:1,2,3'); // Todos pueden ver
Route::post('/comercial', [ComercialController::class, 'store'])->middleware('rol:2'); // Solo rol 2 puede crear
Route::get('/comercial/{id}', [ComercialController::class, 'edit'])->middleware('rol:1,2,3'); // Todos pueden ver
Route::put('/comercial/{id}', [ComercialController::class, 'update'])->middleware('rol:2'); // Solo rol 2 puede modificar
Route::delete('/comercial/{id}', [ComercialController::class, 'destroy'])->middleware('rol:2'); // Solo rol 2 puede eliminar
