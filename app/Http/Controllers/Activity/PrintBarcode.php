<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Illuminate\Support\Facades\Storage;


class PrintBarcode extends Controller
{
    public static function print_barcode_to_papper()
    {
        $profile = CapabilityProfile::load("simple");

        $barcode = EscposImage::load(storage_path('app/barcodes/barcode.png'), false);

        $connector = new WindowsPrintConnector("epson");
        // $connector = new FilePrintConnector("epson");


        /* initiate printer */
        $printer = new Printer($connector, $profile);
        $printer->initialize();

        // $printer->setTextSize(2, 2);
        // $printer->setJustification(Printer::JUSTIFY_LEFT);
        // $printer->bitImage($barcode);

        // $printer->feed();

        $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
        $printer->text("Height and bar width\n");
        $printer->selectPrintMode();
        $printer->text("Default look\n");
        $printer->setBarcodeHeight(48);
        $printer->setBarcodeWidth(8);
        $printer->barcode("20640629376", Printer::BARCODE_CODE39); 


        /* Height and width */
        // $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
        // $printer->text("Height and bar width\n");
        // $printer->selectPrintMode();
        // $heights = array(1, 2, 4, 8, 16, 32);
        // $widths = array(1, 2, 3, 4, 5, 6, 7, 8);
        // $printer->text("Default look\n");
        // $printer->setBarcodeHeight(48);
        // $printer->setBarcodeWidth(8);
        // $printer->barcode("7259", Printer::BARCODE_CODE39);        
        // foreach ($heights as $height) {
        //     $printer->text("\nHeight {$height}\n");
        //     $printer->setBarcodeHeight($height);
        //     $printer->barcode("7259", Printer::BARCODE_CODE39);
        // }
        // foreach ($widths as $width) {
        //     $printer->text("\nWidth {$width}\n");
        //     $printer->setBarcodeWidth($width);
        //     $printer->barcode("7259", Printer::BARCODE_CODE39);
        // }
        // $printer->feed();
        // // Set to something sensible for the rest of the examples
        // $printer->setBarcodeHeight(40);
        // $printer->setBarcodeWidth(2);
        /* Text position */
        // $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);

        $printer->cut();
        /* Close printer */
        $printer->close();

        // delete file
        Storage::delete('app/barcodes/barcode.png');
    }
}
