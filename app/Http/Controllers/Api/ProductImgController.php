<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductImage;

class ProductImgController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $imgs = ProductImage::all();
        if ($imgs->count() > 0) {
            return response()->json($imgs);
        } else {
            return response()->json(['error' => "You Don't Have Any Data Yet!"]);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required',
            'image_path' => 'required',
        ]);

        

        $images = null;

        if ($request->hasFile('image_path')) {
            $img = $request->file('image_path');
            $imageName = time() . $img->GetClientOriginalName();
            $img->move(public_path('product-sub-images'), $imageName);
            $images = '/product-sub-images/' . $imageName;
        }
        // if($images){
            
        // }
        $product = new ProductImage();
            $product->product_id = $validatedData['product_id'];
            $product->image_path = $images;
            $product->save();
        
            return response()->json($product, 201);
        
    }

    public function show($id)
    {
        $img = ProductImage::findOrFail($id);
        return response()->json($img);
    }
    public function showImg($filename)
{
    $path = public_path('/product-sub-images/' . $filename);
    return response()->file($path);
}


    public function update(ProductImage $request, $id)
    {
        $img = ProductImage::findOrFail($id);
        $img->update($request->all());
        return response()->json($img);
    }

    public function destroy($id)
    {
        ProductImage::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
