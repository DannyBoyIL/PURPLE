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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/trades', [App\Http\Controllers\TradeController::class, 'index']);
Route::get('/users', [App\Http\Controllers\UserController::class, 'index']);
Route::get('/users/{id}', [App\Http\Controllers\UserController::class, 'show']);
Route::get('/users/{id}/deals', [App\Http\Controllers\UserController::class, 'deals']);
Route::post('/users/{id}', [App\Http\Controllers\UserController::class, 'update']);
Route::post('/users/{id}/items', [App\Http\Controllers\UserController::class, 'items']);
Route::post('/users/{id}/bid', [App\Http\Controllers\UserController::class, 'bid']);
Route::post('/users/{id}/trade', [App\Http\Controllers\UserController::class, 'trade']);