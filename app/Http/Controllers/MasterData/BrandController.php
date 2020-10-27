<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\MasterData\Brand;

class BrandController extends Controller
{
    public function store(Request $request)
    {
        Brand::create([
            'brand' => strtolower($request->brand),
            'description' => $request->description
        ]);

        session()->flash('message', 'Yeay! Merek berhasil ditambahkan.');
        return back();
    }

    public function update(Request $request, Brand $brand)
    {
        $brand->update([
            'brand' => strtolower($request->brand),
            'description' => $request->description
        ]);
        session()->flash('message', 'OK! Data berhasil diperbarui.');
        return back();
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return back();
    }
}
