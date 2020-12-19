<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class PrintBarcode extends Controller
{
    public static function print_barcode () {
        $connector = new WindowsPrintConnector("epson");

        /* initiate printer */
        $printer = new Printer($connector);
        $printer->initialize();

        /* Height and width */
        $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
        $printer->text("Height and bar width\n");
        $printer->selectPrintMode();
        $heights = array(1, 2, 4, 8, 16, 32);
        $widths = array(1, 2, 3, 4, 5, 6, 7, 8);
        $printer->text("Default look\n");
        $printer->barcode("ABC", Printer::BARCODE_CODE39);
        foreach ($heights as $height) {
            $printer->text("\nHeight {$height}\n");
            $printer->setBarcodeHeight($height);
            $printer->barcode("ABC", Printer::BARCODE_CODE39);
        }
        foreach ($widths as $width) {
            $printer->text("\nWidth {$width}\n");
            $printer->setBarcodeWidth($width);
            $printer->barcode("ABC", Printer::BARCODE_CODE39);
        }
        $printer->feed();
        // Set to something sensible for the rest of the examples
        $printer->setBarcodeHeight(40);
        $printer->setBarcodeWidth(2);
        /* Text position */
        $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
    }
}
