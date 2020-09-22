<?php

namespace App\Http\Controllers\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Relation\StakeHolder;

class StakeHolderController extends Controller
{
    public function index() {
        $suppliers = StakeHolder::where('type', 'supplier')->get();
        return view('pages.stake-holder.index', ['suppliers' => $suppliers, 'units' => [], 'categories' => []]);
    }

    public function store (Request $request) {
        StakeHolder::create($request->all());
        return back();
    }

    public function update(Request $request, StakeHolder $stake_holder) {
        $stake_holder->update($request->all());
        return back();
    }
}
