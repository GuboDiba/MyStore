<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Fetch all products
    public function index()
    {
        $products = Product::all();
    
        return view('home', compact('products'));
        // return response()->json(['products' => $products]);

    }

    // Fetch a single product
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        // return response()->json(['products' => $products]);
        return view('productdetails', compact('product'));
    }

    // Create a new product
    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'stock' => 'required|integer|min:0',
            ]);
        
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image_url')) {
                $imagePath = $request->file('image_url')->store('product_images', 'public'); // Store in storage/app/public/product_images
            }
        
            // Create the product
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image_url' => $imagePath ? Storage::url($imagePath) : null, // Save URL in database
                'stock' => $request->stock,
            ]);
        
            // Return success response
            return response()->json(['message' => 'Product created successfully!', 'product' => $product], 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'message' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Product creation failed', 'message' => $e->getMessage()], 500);
        }
    }
    
    

    // Update a product
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        // Handle image update
        if ($request->hasFile('image')) {
            if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
                Storage::disk('public')->delete($product->image_url);
            }

            $imagePath = $request->file('image')->store('product_images', 'public');
        }

        $product->update([
            'name' => $request->name ?? $product->name,
            'description' => $request->description ?? $product->description,
            'price' => $request->price ?? $product->price,
            'image_url' => $imagePath ?? $product->image_url,
            'stock' => $request->stock ?? $product->stock,
        ]);

        return response()->json(['message' => 'Product updated successfully!', 'product' => $product]);
    }

    // Delete a product
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
            Storage::disk('public')->delete($product->image_url);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully!']);
    }
}
