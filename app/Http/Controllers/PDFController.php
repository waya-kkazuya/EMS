<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

class PDFController extends Controller
{
    public function generatePDF()
    {
        // QRコード画像のパスを取得
        $qrCodes = [];
        for ($i = 1; $i <= 11; $i++) {
            // とりあえず1種類でを10枚で試してみる
            // $qrCodes[] = storage_path('app/public/qrcode/qrcode_' . $i . '.png');
            $qrCodes[] = Storage::path('public/labels/QRCodeTest_label.jpg');
        }

        // dd($qrCodes);
        \Log::info("qrCodes",$qrCodes);
        \Log::info("qrCodes");

        $qrCodePairs = array_chunk($qrCodes, 2);

        // ビューを作成してPDFを生成
        // $pdf = PDF::loadView('pdf.qrcodes', compact('qrCodePairs'));
        $pdf = PDF::loadView('pdf.pdf-preview-table', compact('qrCodePairs'))
            ->setPaper('A4','portrait')
            ->setOption('margin-top', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0);

        return $pdf->download('消耗品QRコード.pdf');
    }

    public function designPDF()
    {
        $qrCodes = [];
        for ($i = 1; $i <= 11; $i++) {
            // $qrCodes[] = storage_path('app/public/qrcode/qrcode_' . $i . '.png');
            $qrCodes[] = Storage::url('public/labels/QRCodeTest_label.jpg');
        }

        // 配列を2つずつのチャンクに分割
        $qrCodePairs = array_chunk($qrCodes, 2);

        return view('pdf.pdf-preview-table', compact('qrCodePairs'));
    }

}
