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
        return back();
    }

    public function update(Request $request, Category $category)
    {
        $category->update([
            'category' => $request->category,
            'description' => $request->description
        ]);
        return back();
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back();
    }
}
