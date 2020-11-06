<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

class PrintReceiptController extends Controller
{
    public function print_receipt () {
        $logo = EscposImage::load(public_path('images/logo.png'), false);
        try {
            $items = [
                [
                "name" => "Tali Pocong",
                "satuan" => "PCS",
                "price" => "123.000",
                "qty" => "5",
                "total" => "625.000",
                "discount" => "10"
                ],
                [
                "name" => "Tali Kolor",
                "satuan" => "PCS",
                "price" => "145.000",
                "qty" => "7",
                "total" => "689.000"
                ],
                [
                "name" => "Tali Silaturahmi",
                "satuan" => "PCS",
                "price" => "145.000",
                "qty" => "2",
                "total" => "79.000",
                "discount" => "30"
                ],
                [
                    "name" => "Tali Ta Latief",
                    "satuan" => "MAN",
                    "price" => "145.000",
                    "qty" => "2",
                    "total" => "79.000"
                    ]
            ];
            $calc = [
                "total_price" => "1.000.000",
                "fee" => "230.000",
                "bill" => "1.002.3000",
                "cash" => "1.500.000",
                "cashback" => "500.000"
            ];
            $connector = new WindowsPrintConnector("zahra");

            /* Print a "Hello world" receipt" */
            $printer = new Printer($connector);
            $printer->initialize();

            $printer->setTextSize(2, 2);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer -> bitImage($logo);
            $printer -> feed();

            /* Name of shop */
            // $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            // $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer->setTextSize(2, 1);
            // $printer -> text("BUMI FLORA 80 \n");
            // $printer -> feed();

            $printer->setTextSize(1, 1);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer -> text("JL. KH. MAULANA HASANUDIN NO. 80 CIPONDOH\nTANGERANG - BANTEN \n");
            $printer -> text("Telp: 085772386441 Fax: \n");
            $printer->feed();
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("No. : NomorUrut/KSR/Tanggal\n");
            $printer->text("Ksr. : Pevita Pearce (waktu: 16:45:12)\n");
            $printer -> text("------------------------------------------------\n");
            $printer->feed(); 
            
            // print items
            $n1 = '';
            $n2 = '';
            $n3 = '';
            for ($i=0; $i < count($items); $i++) { 
                
                // cek field discoun
                if(!array_key_exists("discount", $items[$i])) {
                    for ($j=0; $j < 48; $j++) { 
                        // item detail
                        if($j < 45) {
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
                        if($j < 15) {
                            $n2 .= isset($items[$i]["price"][$j]) == 1 ? $items[$i]["price"][$j] : ' ';
                        } 
                        
                        // print x sama qty
                        if ($j >= 15 && $j < 16) {
                            $n2 .= "x " . $items[$i]["qty"];
                        }
                        
                        // kasih space setelah qty
                        if($j >= (16+2+strlen($items[$i]["qty"])) && $j < 30) {
                            $n2 .= ' ';
                        }
    
                        // sama dengan setelah qty
                        if ($j == 30) {
                            $n2 .= "=";
                        }
    
                        // kasih space setelah samadengan
                        if ($j > 30 && $j < (48 - strlen($items[$i]["price"]))+1) {
                            $n2 .= " ";
                        }
                        
                    }
                } else {
                    for ($j=0; $j < 48; $j++) { 
                        // item detail
                        if($j < 45) {
                            if (isset($items[$i]["name"][$j])) {
                                $n1 .= $items[$i]["name"][$j];
                            } else {
                                $n1 .= ' ';
                            }
                        } else {
                            $n1 .= $items[$i]["satuan"];
                            $n3 .= $items[$i]["total"];
                            break;
                        }
    
                        // print harga
                        if($j < 15) {
                            $n2 .= isset($items[$i]["price"][$j]) == 1 ? $items[$i]["price"][$j] : ' ';
                        } 
                        
                        // print x sama qty
                        if ($j >= 15 && $j < 16) {
                            $n2 .= "x " . $items[$i]["qty"];
                        }
                        
                        // kasih space setelah qty sampe full kalo kolom 
                        if($j >= (16+2+strlen($items[$i]["qty"])) && $j < 45) {
                            $n2 .= ' ';
                        }

                        // print discount
                        if($j <1) $n3 .= "Pot.:". $items[$i]["discount"]. "%";
                        if($j > 1 && $j < 23) $n3 .= " ";
    
                        // sama dengan setelah qty
                        if ($j == 30) {
                            $n3 .= "=";
                        }
    
                        // kasih space setelah samadengan
                        if ($j > 30 && $j < (48 - strlen($items[$i]["price"]))+1) {
                            $n3 .= " ";
                        }
                        
                    }
                }

                // buat nyetak cek kolom discount
                if(!array_key_exists("discount", $items[$i])) {
                    $printer->text($n1."\n");
                    $printer->text($n2."\n");
                }else {
                    $printer->text($n1."\n");
                    $printer->text($n2."\n");
                    $printer->text($n3."\n");
                }
                
                $n1 = '';
                $n2 = '';
                $n3 = '';
                
            }
            $printer -> text("------------------------------------------------\n");
            $qty = "ITEM: 3; QTY: 12 "; // 18 col
            $fee = "Biaya Lain       =";
            $bill = "Total Akhir      =";
            $cash = "Tunai            =";
            $cashback = "Kembali          =";
            // qty
            for ($i=18; $i < (48 - strlen($calc["total_price"]))+1; $i++) { 
                $qty .= " ";
            }
            // fee
            for ($i=18; $i < (48 - strlen($calc["fee"])); $i++) { 
                $fee .= " ";
            }

            // bill
            for ($i=18; $i < (48 - strlen($calc["bill"])); $i++) { 
                $bill .= " ";
            }

            // cash
            for ($i=18; $i < (48 - strlen($calc["cash"])); $i++) { 
                $cash .= " ";
            }
            // cashback
            for ($i=18; $i < (48 - strlen($calc["cashback"])); $i++) { 
                $cashback .= " ";
            }
            $printer -> text($qty . $calc["total_price"] . "\n");
            $printer -> text($fee . $calc["fee"] . "\n");
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
        } catch (\Throwable $th) {
            echo "Couldn't print to this printer: " . $th -> getMessage() . "\n";
        }
    }
}