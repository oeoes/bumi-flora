<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Carbon\Carbon;

class PrintReceiptController extends Controller
{
    public static function print_receipt ($items, $calc) {
        $logo = EscposImage::load(public_path('images/logo.png'), false);
        // total qty seluruh item
        $sum_qty = 0;
        foreach ($items as $item) {
            $sum_qty += $item['qty'];
        }


        $connector = new WindowsPrintConnector("epson");

        /* initiate printer */
        $printer = new Printer($connector);
        $printer->initialize();

        $printer->setTextSize(2, 2);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer -> bitImage($logo);
        $printer -> feed();

        $printer->setTextSize(1, 1);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text("JL. KH. MAULANA HASANUDIN NO. 80 CIPONDOH\nTANGERANG - BANTEN \n");
        $printer -> text("Telp: 085772386441 Fax: \n");
        $printer->feed();
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text(" No.   : ". $calc['transaction_number'] ." \n");
        $printer->text(" Ksr.  : ". auth()->user()->name . " (waktu: " . Carbon::now()->format('H:i:s') . ")\n");
        $printer->text(" Cust. : ". $calc['customer'] ."\n");
        $printer -> text("------------------------------------------------\n");
        $printer->feed(); 
        
        // print items
        $n1 = ' ';
        $n2 = ' ';
        $n3 = ' ';
        for ($i=0; $i < count($items); $i++) { 
            
            // cek field discoun
            if($items[$i]['discount'] == 0) {
                for ($j=0; $j < 47; $j++) { 
                    // item detail
                    if($j < 44) {
                        if (isset($items[$i]["name"][$j])) {
                            $n1 .= $items[$i]["name"][$j];
                        } else {
                            $n1 .= ' ';
                        }
                    } else {
                        $n1 .= $items[$i]["satuan"];
                        $n2 .= $items[$i]["total"];
                        break;
                    }

                    // print harga
                    if($j < 14) {
                        $n2 .= isset($items[$i]["price"][$j]) == 1 ? $items[$i]["price"][$j] : ' ';
                    } 
                    
                    // print x sama qty
                    if ($j >= 14 && $j < 15) {
                        $n2 .= "x " . $items[$i]["qty"];
                    }
                    
                    // kasih space setelah qty
                    if($j >= (15+1+strlen($items[$i]["qty"])) && $j < 29) {
                        $n2 .= ' ';
                    }

                    // sama dengan setelah qty
                    if ($j == 29) {
                        $n2 .= "=";
                    }

                    // kasih space setelah samadengan
                    if ($j > 29 && $j < (47 - strlen($items[$i]["total"]))) {
                        $n2 .= " ";
                    }
                    
                }
            } else {
                for ($j=0; $j < 47; $j++) { 
                    // item detail
                    if($j < 44) {
                        if (isset($items[$i]["name"][$j])) {
                            $n1 .= $items[$i]["name"][$j];
                        } else {
                            $n1 .= ' ';
                        }
                    } else {
                        $n1 .= $items[$i]["satuan"];
                        $n3 .= floor($items[$i]["total"]);
                        break;
                    }

                    // print harga
                    if($j < 14) {
                        $n2 .= isset($items[$i]["price"][$j]) == 1 ? $items[$i]["price"][$j] : ' ';
                    } 
                    
                    // print x sama qty
                    if ($j >= 14 && $j < 15) {
                        $n2 .= "x " . $items[$i]["qty"];
                    }
                    
                    // kasih space setelah qty sampe full kalo kolom 
                    if($j >= (15+1+strlen($items[$i]["qty"])) && $j < 44) {
                        $n2 .= ' ';
                    }

                    // print discount
                    if($j < 1) $n3 .= "Pot.:". $items[$i]["discount"]. "%";
                    if($j > 1 && $j < 22) $n3 .= " ";

                    // sama dengan setelah qty
                    if ($j == 29) {
                        $n3 .= "=";
                    }

                    // kasih space setelah samadengan
                    if ($j > 29 && $j < (47 - strlen($items[$i]["total"]))) {
                        $n3 .= " ";
                    }
                    
                }
            }

            // buat nyetak cek kolom discount
            if($items[$i]['discount'] == 0) {
                $printer->text($n1."\n");
                $printer->text($n2."\n");
            }else {
                $printer->text($n1."\n");
                $printer->text($n2."\n");
                $printer->text($n3."\n");
            }
            
            $n1 = ' ';
            $n2 = ' ';
            $n3 = ' ';
            
        }
        $printer -> text("------------------------------------------------\n");
        $qty = " ITEM: ". count($items). "; QTY: ". $sum_qty ." "; // 18 col
        $discount = " Diskon           =";
        $fee = " Biaya Lain       =";
        $tax = " Pajak            =";
        $bill = " Total Akhir      =";
        $cash = " Tunai            =";
        $cashback = " Kembali          =";
        // qty
        for ($i=18; $i < (47 - strlen($calc["total_price"]))+1; $i++) { 
            $qty .= " ";
        }
        // discount
        for ($i=18; $i < (47 - strlen($calc["discount"])); $i++) { 
            $discount .= " ";
        }

        // fee
        for ($i = 18; $i < (47 - strlen($calc["fee"])); $i++) {
            $fee .= " ";
        }

        // overall_discount
        for ($i=18; $i < (48 - strlen($calc["overall_discount"])); $i++) { 
            $overall_discount .= " ";
        }

        // tax
        for ($i = 18; $i < (47 - strlen($calc["tax"])); $i++) {
            $tax .= " ";
        }

        // bill
        for ($i=18; $i < (47 - strlen($calc["bill"])); $i++) { 
            $bill .= " ";
        }

        // cash
        for ($i=18; $i < (47 - strlen($calc["cash"])); $i++) { 
            $cash .= " ";
        }
        // cashback
        for ($i=18; $i < (47 - strlen($calc["cashback"])); $i++) { 
            $cashback .= " ";
        }
        $printer -> text($qty . $calc["total_price"] . "\n");
        $printer -> text($discount . $calc["discount"] . "\n");
        $printer -> text($fee . $calc["fee"] . "\n");
        $printer -> text($tax . $calc["tax"] . "\n");
        $printer -> text($bill . $calc["bill"] . "\n");
        $printer->feed();
        $printer -> text($cash . $calc["cash"] . "\n");
        $printer->feed();
        $printer -> text($cashback . $calc["cashback"] . "\n");
        $printer->feed();

        // warning
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("[Perhatian]\nBarang yang telah dibeli tidak dapat \n dikembalikan kecuali ada perjanjian.\n");
        $printer->feed();
        $printer -> cut();
        /* Close printer */
        $printer -> close();
    }
}