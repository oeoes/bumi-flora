<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class PrintReceiptController extends Controller
{
    public function print_receipt () {
        try {
            $connector = new WindowsPrintConnector("namaprinter");

            /* Print a "Hello world" receipt" */
            $printer = new Printer($connector);
            $printer -> text("Hello World!\n");
            $printer -> cut();


            /* Name of shop */
            $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $printer -> text("BUMI FLORA 80\n");
            $printer -> feed();

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer -> text("JL. KH. MAULANA HASANUDIN NO. 80 CIPONDOH TANGERANG \n Telp: 085772386441 Fax: ");
            
            /* Close printer */
            $printer -> close();
        } catch (\Throwable $th) {
            echo "Couldn't print to this printer: " . $th -> getMessage() . "\n";
        }
    }
}
