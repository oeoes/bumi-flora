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

        
        $connector = new WindowsPrintConnector("epson");

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
        $printer -> text("JML TRANSAKSI   : ". $data['jumlah_transaksi'] ."\n");
        $printer -> text("TOT. POTONGAN   : ". number_format($data['total_discount']) ."\n");
        $printer -> text("TOT. PAJAK      : ". number_format($data['total_pajak']) ."\n");
        $printer -> text("TOT. BIAYA      : ". number_format($data['total_biaya']) ."\n");
        $printer -> text("TOTAL           : ". number_format($data['total_tunai'] + $data['total_debit'] + $data['total_ewallet'] + $data['total_transfer'] + $data['total_credit']) ."\n");
        $printer -> text("BAYAR TUNAI     : ". number_format($data['total_tunai']) ."\n");
        $printer -> text("BAYAR DEBIT     : ". number_format($data['total_debit']) ."\n");
        $printer -> text("BAYAR TRANSFER  : ". number_format($data['total_transfer']) ."\n");
        $printer -> text("BAYAR E-WALLET  : ". number_format($data['total_ewallet']) ."\n");
        $printer -> text("BAYAR KREDIT    : ". number_format($data['total_credit']) ."\n");
        $printer -> text("------------------------------------------------\n");
        $printer -> text("JAM CETAK       : ". Carbon::now()->format('Y-m-d H:i:s') ." \n");
        $printer->feed();
        $printer -> cut();
        /* Close printer */
        $printer -> close();
    }
}
