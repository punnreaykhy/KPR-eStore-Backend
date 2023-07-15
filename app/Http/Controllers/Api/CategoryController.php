<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        if($categories->count()>0){
            return response()->json($categories);
        }else{
            return response()->json(['error'=>"You Don't Have Any Data Yet!"]);
        }
        
    }

    public function store(Request $request)
    {
        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return response()->json($category);
    }

    public function destroy($id)
    {
        try {
            Category::findOrFail($id)->delete();
        return response()->json(['status'=>"Category Deleted!"]);
        }catch (\Exception $e){
            return response()->json(['status'=>"Category fail to Delete!"]);
        }
        
    }
}
