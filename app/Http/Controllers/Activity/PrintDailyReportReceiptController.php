<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Carbon\Carbon;

class PrintDailyReportReceiptController extends Controller
{
    public static function print_daily_report_receipt ($data) {

        
        $connector = new WindowsPrintConnector("zahra");

        /* initiate printer */
        $printer = new Printer($connector);
        $printer->initialize();

        $printer->setTextSize(1, 1);
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text("LAPORAN PENJUALAN \n");
        $printer -> text("------------------------------------------------\n");
        $printer -> text("PERIODE     : ". Carbon::now()->format('d-m-Y') . "s/d" . Carbon::now()->addDay(1)->format('d-m-Y') . " \n");
        $printer -> text("USER        : ". auth()->user()->name ." \n");
        $printer -> text("DEPT/GUDANG : UTAMA \n");
        $printer -> text("------------------------------------------------\n");
        $printer -> text("JML TRANSAKSI   : ". $data[0] ."\n");
        $printer -> text("TOT. POTONGAN   : ". $data[1] ."\n");
        $printer -> text("TOT. PAJAK      : ". $data[2] ."\n");
        $printer -> text("TOT. BIAYA      : ". $data[3] ."\n");
        $printer -> text("TOTAL           : ". $data[4] ."\n");
        $printer -> text("BAYAR TUNAI     : ". $data[5] ."\n");
        $printer -> text("BAYAR E-WALLET  : ". $data[6] ."\n");
        $printer -> text("BAYAR DEBIT     : ". $data[7] ."\n");
        $printer -> text("BAYAR TRANSFER  : ". $data[8] ."\n");
        $printer -> text("BAYAR KREDIT    : ". $data[9] ."\n");
        $printer -> text("------------------------------------------------\n");
        $printer -> text("JAM CETAK       : ". Carbon::now()->format('Y-m-d H:i:s') ." \n");
        $printer->feed();
    }
}
