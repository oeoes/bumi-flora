<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\MasterData\Item;
use App\Model\Activity\GrosirItem;
use Illuminate\Support\Facades\DB;

class GrosirItemController extends Controller
{
    public function index () {
        $items = DB::table('items')
            ->join('units', 'units.id', '=', 'items.unit_id')
            ->select('items.*', 'units.unit')->get();
            
        $grosir_items = DB::table('items')
                    ->join('units', 'units.id', '=', 'items.unit_id')
                    ->join('grosir_items', 'items.id', '=', 'grosir_items.item_id')
                    ->select('grosir_items.id', 'items.name', 'units.unit', 'items.price', 'grosir_items.minimum_item', 'grosir_items.price as grosir_price')
                    ->get();
        return view('pages.activity.grosir')->with(['items' => $items, 'grosirs' => $grosir_items]);
    }

    public function store(Request $request) {
        if(GrosirItem::where('item_id', $request->item_id)->first()) {
            return response()->json(['status' => false, 'message' => 'Duplicate data not allowed.'], 400);
        } else {
            GrosirItem::create([
                'item_id' => $request->item_id,
                'minimum_item' => $request->minimum_item,
                'price' => $request->new_price,
            ]);

            return response()->json(['status' => true, 'message' => 'Grosir created']);
        }
    }

    public function update(Request $request, GrosirItem $grosir) {
        $grosir->update([
            'minimum_item' => $request->minimum_item,
            'price' => $request->price
        ]);
        return back();
    }

    public function destroy (GrosirItem $grosir) {
        $grosir->delete();
        return back();
    }
}
