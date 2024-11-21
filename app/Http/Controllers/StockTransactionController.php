<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\StockTransaction ;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StockTransactionController extends Controller
{
    public function stockTransaction(Request $request)
    {
        Log::info('StockTransactionController API stockTransaction method called');

        $item = Item::find($request->item_id);

        // stock_transactionsを取得 withを使うとitemの情報すべてが取れてくる
        $stock_transactions = StockTransaction::where('item_id', $item->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // 最新の在庫数を取得
        $current_stock = $item->stock;

        // $current_stockはクロージャ―の外で定義されており、&を付けることで参照渡ししている
        // よって、値を引き継いだままループをする 1つずつズレる
        $stock_transactions->each(function ($transaction) use (&$current_stock) {
            // 現在のレコードの在庫数を設定
            $transaction->current_stock = $current_stock;
        
            // 次のレコードの在庫数を計算、次に持ち越し
            $current_stock -= $transaction->quantity; //過去に遡るからマイナス

            $transaction->formatted_created_at = Carbon::parse($transaction->created_at)->format('Y-m-d H:i');
        });

        // グラフ用のデータを準備、時系列を左から右にするため配列を逆順にする
        $labels = array_reverse($stock_transactions->pluck('formatted_created_at')->toArray());
        $stocks = array_reverse($stock_transactions->pluck('current_stock')->toArray());

        Log::info('StockTransactionController API stockTransaction method succeeded');

        return [
            'stockTransactions' => $stock_transactions,
            'labels' => $labels,
            'stocks' => $stocks
        ];
    }
}
