<?php

use App\Http\Controllers\Api\PermisosController;
use App\Http\Controllers\Api\PermisoUsuarioController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('user')->group(function () {
        Route::post('/register', [UserController::class, 'register']);
        Route::put('/password/{id}', [UserController::class, 'password'])->where('id', '[0-9]+');
        Route::put('/editar/{id}', [UserController::class, 'update'])->where('id', '[0-9]+');
        Route::get('/user-profile', [UserController::class, 'userprofile']);
        Route::get('/logout', [UserController::class, 'logout']);
    });
});

Route::prefix('productos')->group(function () {
    Route::post('/getAll', [ProductoController::class, 'getProductos']);
    Route::get('getOne/{producto_id}', [ProductoController::class, 'getProductoById'])->where('producto_id', '[0-9]+');
    Route::post('/insert', [ProductoController::class, 'insertProducto']);
    Route::put('/update/{producto_id}', [ProductoController::class, 'updateProducto'])->where('producto_id', '[0-9]+');
    Route::delete('/delete/{producto_id}', [ProductoController::class, 'deleteProducto'])->where('producto_id', '[0-9]+');
});

Route::prefix('permisos')->group(function () {
    Route::post('/getAll', [PermisosController::class, 'getPermisos']);
    Route::get('/getOne/{idPermiso}', [PermisosController::class, 'getPermisoById'])->where('idPermiso', '[0-9]+');
    Route::post('/insert', [PermisosController::class, 'insertPermiso']);
    Route::put('/update/{idPermiso}', [PermisosController::class, 'updatePermiso'])->where('idPermiso', '[0-9]+');
    Route::delete('/delete/{idPermiso}', [PermisosController::class, 'deletePermiso'])->where('idPermiso', '[0-9]+');
});

Route::post('login', [UserController::class, 'login']);
Route::get('getUsuarios', [UserController::class, 'getUsuarios']);
Route::get('getPermiso/{idUsuario}', [PermisoUsuarioController::class, 'getPermisosUsuario'])->where('','[0-9]+');
Route::get('getNoPermisos/{idUsuario}', [PermisoUsuarioController::class, 'getNoPermisosUsuario'])->where('','[0-9]+');
Route::get('showDisponibles/{idUsuario}', [PermisoUsuarioController::class, 'showDisponibles'])->where('','[0-9]+');
Route::get('showAsignados/{idUsuario}', [PermisoUsuarioController::class, 'showAsignados'])->where('','[0-9]+');
Route::post('updateInsert', [PermisoUsuarioController::class, 'updateInsert']);
Route::post('ActualizarPermisos', [PermisoUsuarioController::class, 'ActualizarPermisos']);
Route::delete('DeletePermisos', [PermisoUsuarioController::class, 'ActualizarPermisos']);
