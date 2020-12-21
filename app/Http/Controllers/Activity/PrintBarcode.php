<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\CapabilityProfile;

// zebra libs
use Zebra\Client;
use Zebra\Zpl\Image;
use Zebra\Zpl\Builder;
use Zebra\Zpl\GdDecoder;

class PrintBarcode extends Controller
{
    public function zpl_printing()
    {
        $decoder = GdDecoder::fromPath(storage_path('app/barcodes/barcode.png'));
        $image = new Image($decoder);

        $zpl = new Builder();
        $zpl->fo(50, 50)->gf($image)->fs();

        $client = new Client('127.0.0.1');
        $client->send($zpl);
    }

    public static function print_barcode_to_papper()
    {
        $profile = CapabilityProfile::load("simple");

        $logo = EscposImage::load(storage_path('app/barcodes/barcode.png'), false);

        $connector = new WindowsPrintConnector("xprinter");
        // $connector = new FilePrintConnector("epson");


        /* initiate printer */
        $printer = new Printer($connector, $profile);
        $printer->initialize();

        $printer->setTextSize(2, 2);
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->bitImage($logo);

        $printer->feed();


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
        $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);

        $printer->cut();
        /* Close printer */
        $printer->close();
    }
}
