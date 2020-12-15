<?php

namespace App\Http\Controllers\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Relation\StakeHolder;

class StakeHolderController extends Controller
{
    public function index() {
        $suppliers = StakeHolder::where('type', 'supplier')->get();
        $customers = StakeHolder::where('type', 'customer')->get();
        $sales = StakeHolder::where('type', 'sales')->get();
        return view('pages.stake-holder.index', ['suppliers' => $suppliers, 'customers' => $customers, 'sales' => $sales]);
    }

    public function store (Request $request) {
        StakeHolder::create([
            'name' => $request->name,
            'address' => $request->address,
            'country' => $request->country,
            'province' => $request->province,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
            'email' => $request->email,
            'card_number' => $request->card_number,
            'owner' => $request->owner,
            'bank' => $request->bank,
            'type' => $request->type,
        ]);

        session()->flash('message', 'Yeay! Data berhasil ditambahkan.');
        return back();
    }

    public function update(Request $request, StakeHolder $entity) {
        $entity->update([
            'name' => $request->name,
            'address' => $request->address,
            'country' => $request->country,
            'province' => $request->province,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
            'email' => $request->email,
            'card_number' => $request->card_number,
            'owner' => $request->owner,
            'bank' => $request->bank,
            'type' => $request->type,
        ]);

        session()->flash('message', 'OK! Data berhasil diperbarui.');
        return back();
    }

    public function destroy(StakeHolder $entity) {
        $entity->delete();
        return back();
    }
}
