<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\MasterData\Category;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        Category::create([
            'category' => $request->category,
            'description' => $request->description
        ]);
        session()->flash('message', 'Yeay! Kategori berhasil ditambahkan.');
        return back();
    }

    public function update(Request $request, Category $category)
    {
        $category->update([
            'category' => $request->category,
            'description' => $request->description
        ]);
        session()->flash('message', 'OK! Data berhasil diperbarui.');
        return back();
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back();
    }
}
