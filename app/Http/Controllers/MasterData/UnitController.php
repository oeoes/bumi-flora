<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\MasterData\Unit;

class UnitController extends Controller
{
    public function store(Request $request)
    {
        Unit::create([
            'unit' => strtolower($request->unit),
            'description' => $request->description
        ]);

        session()->flash('message', 'Yeay! Satuan berhasil ditambahkan.');
        return back();
    }

    public function update(Request $request, Unit $unit)
    {
        $unit->update([
            'unit' => strtolower($request->unit),
            'description' => $request->description
        ]);

        session()->flash('message', 'OK! Data berhasil diperbarui.');
        return back();
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return back();
    }
}
