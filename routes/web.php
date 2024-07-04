<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\WishController;
use App\Http\Controllers\ImageTestController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\ConsumableItemsController;
use App\Http\Controllers\UpdateStockController;
use App\Http\Controllers\InventoryPlanController;
use App\Models\ImageTest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::middleware('can:user-higher')
// ->group(function(){

// });

// グラフテスト用
Route::get('analysis', [AnalysisController::class, 'index'])->name('analysis');


// それぞれに適切な権限レベル(admin,staff,user)のmiddlewareをかける

// 消耗品 ConsumableItemsController
// middleware('can:user-higher')

Route::middleware('can:user-higher')->group(function () {
    Route::get('consumable_items', [ConsumableItemsController::class, 'index'])->name('consumable_items');

    Route::put('updateStock/{id}', [UpdateStockController::class, 'updateStock'])->name('updateStock');

    Route::get('consumable_items/{id}/history', [ConsumableItemsController::class, 'history'])->name('consumable_items.history');
});



Route::resource('items', ItemController::class)
->middleware(['auth', 'verified', 'can:staff-higher']);

// Route::middleware(['auth', 'verified', 'can:staff-higher'])->group(function () {
//     Route::get('/items', [ItemController::class, 'index']);
//     Route::get('/items/create', [ItemController::class, 'create']);
//     Route::post('/items', [ItemController::class, 'store']);
//     Route::get('/items/{item}', [ItemController::class, 'show']);
//     Route::get('/items/{item}/edit', [ItemController::class, 'edit']);
//     Route::put('/items/{item}', [ItemController::class, 'update']);
//     Route::delete('/items/{item}', [ItemController::class, 'destroy']);
// ソフトデリートを追加
// });


Route::resource('inventory_plans', InventoryPlanController::class)
->middleware(['auth', 'verified', 'can:staff-higher']);

// ウィッシュリスト
Route::resource('wishes', WishController::class)
->middleware(['auth', 'verified', 'can:user-higher']);

Route::resource('image_tests', ImageTestController::class)
->middleware(['auth', 'verified', 'can:user-higher']);


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
