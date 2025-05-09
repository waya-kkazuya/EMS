<?php

use App\Http\Controllers\Api\EdithistoryController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\ItemRequestController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\StockTransactionController;
use App\Http\Controllers\Api\UpdateStockController;
use App\Http\Controllers\Api\VueErrorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// 備品の廃棄
Route::middleware(['auth:sanctum', 'verified', 'can:staff-higher'])
  ->post('/items/{id}/restore', [ItemController::class, 'restore'])
  ->name('api.items.restore');

// 対象の備品の編集履歴を取得する
Route::middleware(['auth:sanctum', 'verified', 'can:staff-higher'])
  ->get('/edithistory', [EdithistoryController::class, 'index']);

// 在庫数の入出庫履歴を取得する
Route::middleware('auth:sanctum', 'verified', 'can:user-higher')
  ->get('/stock_transactions', [StockTransactionController::class, 'index'])
  ->name('stock_transactions');

// 消耗品の入出・出庫モーダルで更新処理をした際、在庫数をリアルタイムに反映する
Route::middleware(['auth:sanctum', 'verified', 'can:staff-higher'])
  ->get('/consumable_items/{itemId}/stock', [UpdateStockController::class, 'getStock']);

// 通知を表示したら既読にする
Route::middleware(['auth:sanctum', 'verified', 'can:staff-higher'])->group(function () {
  Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});

// ベルに未読通知数を表示する
Route::middleware('auth:sanctum', 'verified', 'can:staff-higher')
  ->get('/notifications_count', function () {
    return auth()->user()->unreadNotifications;
  });

// リクエストのステータスのプルダウンを変更する
Route::middleware(['auth:sanctum', 'verified', 'can:staff-higher', 'RestrictGuestAccess'])
  ->post('item-requests/{id}/update-status', [ItemRequestController::class, 'updateStatus'])
  ->name('item-requests.update-status');

// リクエスト一覧画面でユーザーの権限情報を取得する
Route::middleware(['auth:sanctum', 'verified', 'can:user-higher'])
  ->get('/user-role', function (Request $request) {
    return auth()->user()->role;
  });

// AuthenticatedLayout.vueのプロフィール画像URLを取得する
Route::middleware(['auth:sanctum', 'verified', 'can:user-higher'])
  ->get('/profile-image', [ProfileController::class, 'getProfileImage']);

// Vue側のエラーをAPIでログに書き込む
Route::middleware(['auth:sanctum', 'verified', 'can:user-higher'])
  ->post('/log-error', [VueErrorController::class, 'logError']);
