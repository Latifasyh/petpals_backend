<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(){


        return [
            new  Middleware('auth:sanctum', except:['index','show'])
        ];

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


            // Validate request
            $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:225',
                'price' => 'required|numeric',
                'description' => 'required|string',
                'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Check if user is authenticated
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            // Ensure the seller exists for this user
            $seller = Seller::where('user_id', $user->id)->first();

            if (!$seller) {
                return response()->json(['error' => 'Seller not found'], 404);
            }

            // Handle picture upload
            $fields = $request->only(['name', 'category', 'price', 'description']);

            if ($request->hasFile('picture')) {
                $fields['picture'] = $request->file('picture')->store('Product_picture', 'public');
            }

            // Create product
            $product = $seller->products()->create($fields);

            return response()->json($product, 201);


    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {

             Gate::authorize('modify',$product);

                // Validate request
                $request->validate([
                    'name' => 'required|string|max:255',
                    'category' => 'required|string|max:225',
                    'price' => 'required|numeric',
                    'description' => 'required|string',
                    'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                ]);

                // Check if user is authenticated
                $user = Auth::user();
                if (!$user) {
                    return response()->json(['error' => 'User not authenticated'], 401);
                }

                // Ensure the seller exists for this user
                $seller = Seller::where('user_id', $user->id)->first();
                if (!$seller) {
                    return response()->json(['error' => 'Seller not found'], 404);
                }

                // Ensure the product belongs to the authenticated seller
                if ($product->seller_id !== $seller->id) {
                    return response()->json(['error' => 'Unauthorized to update this product'], 403);
                }

                // Handle picture upload
                $fields = $request->only(['name', 'category', 'price', 'description']);

                if ($request->hasFile('picture')) {
                    // Optionally delete the old file if necessary
                    if ($product->picture) {
                        Storage::disk('public')->delete($product->picture);
                    }

                    // Store the new file
                    $fields['picture'] = $request->file('picture')->store('Product_picture', 'public');
                }

                // Update the product
                $product->update($fields);

                return response()->json($product, 200);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Gate::authorize('modify',$product);
        if ($product->file) {
            Storage::disk('public')->delete($product->file);
        }

        $product->delete();
        return ['message'=>'post was deleted'];
    }
}
