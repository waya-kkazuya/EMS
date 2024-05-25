<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AnalysisController;
use App\Http\Controllers\Api\ConsumableItemsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')
->get('/analysis', [AnalysisController::class, 'index' ])
->name('api.analysis');

Route::middleware('auth:sanctum')
->get('/history', [ConsumableItemsController::class, 'history' ])
->name('api.history');

Route::get('/notifications', function () {
  return auth()->user()->unreadNotifications;
});