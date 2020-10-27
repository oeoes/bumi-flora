<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DNS1D;

class BarcodeGenerator extends Controller
{
    public function index() {
        $items = DB::table('items')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('balances', 'balances.item_id', '=', 'items.id')
                ->where('balances.dept', 'utama')
                ->select('items.name', 'items.id', 'items.price', 'items.main_cost', 'units.unit')->get();
        return view('pages.admin.barcode-page')->with('items', $items);
    }

    public function generate () {
        $item = DB::table('items')->select('name', 'barcode', 'price')->where('id', request('item_id'))->first();
        $barcode_img = DNS1D::getBarcodePNG($item->barcode, 'C39');
        return response()->json(['item' => $item, 'barcode' => $barcode_img]);
    }
}
