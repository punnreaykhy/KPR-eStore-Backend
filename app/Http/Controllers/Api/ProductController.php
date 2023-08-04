<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('size', 12);  // Default to 12 records per page if 'size' parameter is not provided
        $page = $request->input('page', 1);  // Default to the first page if 'page' parameter is not provided

        $products = Product::where('stock', '>', 0)->paginate($perPage, ['*'], 'page', $page);
        // $products = Product::all();
        if ($products->count() > 0) {
            return response()->json($products);
        } else {
            return response()->json(['error' => "You Don't Have Any Data Yet!"]);
        }
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('searchTerm');

        $products = Product::where('stock', '>', 0)->where('name', 'LIKE', "%$searchTerm%")->get();

        if ($products->count() > 0) {
            return response()->json($products);
        } else {
            return response()->json(['error' => "Item not found!"]);
        }
        // return response()->json($products);
    }

    public function outOfStock()
    {
        $products = Product::where('stock', '=', 0)->get();
        if ($products->count() > 0) {
            return response()->json($products);
        } else {
            return response()->json(['error' => "You Don't Have Any Out of Stock Yet!"]);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'stock' => 'required',
            'price' => 'required',
            'image_path' => 'required',
        ]);

        $images = null;

        if ($request->hasFile('image_path')) {
            $img = $request->file('image_path');
            $imageName = time() . $img->GetClientOriginalName();
            $img->move(public_path('product-images'), $imageName);
            $images = '/product-images/' . $imageName;
        }

        $product = new Product();
        $product->name = $validatedData['name'];
        $product->price = $validatedData['price'];
        $product->stock = $validatedData['stock'];
        $product->image_path = $images;
        $product->category_id = $validatedData['category_id'];
        $product->description = $validatedData['description'];

        $product->save();

        return response()->json($product, 201);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function listProductsByCategory($categoryName)
    {
        $category = Category::where('name', $categoryName)->with(['products' => function($query) {
            $query->where('stock', '>', 0);
        }])->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Get all products associated with the category
        $products = $category->products;

        // Return the products as a JSON response
        return response()->json($products);
    }

    public function showImg($filename)
    {
        $path = public_path('/product-images/' . $filename);
        return response()->file($path);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if ($request->hasFile('image_path')) {
            $img = $request->file('image_path');
            $imageName = time() . $img->GetClientOriginalName();
            $img->move(public_path('product-images'), $imageName);
            $images = '/product-images/' . $imageName;

            $imgPath['image_path'] = $images;
            $product->update($imgPath);
        }
        $data = $request->except('image_path');
        $product->update($data);
        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $imagePath = public_path($product->image_path);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
