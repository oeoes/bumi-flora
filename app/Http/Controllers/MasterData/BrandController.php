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
            'brand' => $request->brand,
            'description' => $request->description
        ]);
        return back();
    }

    public function update(Request $request, Brand $brand)
    {
        $brand->update([
            'brand' => $request->brand,
            'description' => $request->description
        ]);
        return back();
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return back();
    }
}
