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
        $products = Product::with('professionType')->get();

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $fields = $request->validate([
                    'product_name' => 'required|string|max:255',
                    'category' => 'required|string|max:225',
                    'price' => 'required|numeric',
                    'description' => 'required|string',
                    'picture' => 'nullable|file|mimes:jpg,png,jpeg,gif,mp4,mov,avi',
                    'animal_type' => 'required|string|max:255',

                ]);

                if ($request->hasFile('picture')) {
                    $fields['picture'] = $request->file('picture')->store('Product_Pic', 'public');
                }


            // Check if user is authenticated
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $account = Auth::user();
            $user = $account->user;

            // Vérifiez si l'utilisateur a un professionType
            if (!$user->professionType) {
                return response()->json(['message' => 'User  does not have a profession type.'], 400);
            }

            // Utilisez le professionType pour créer le produit
            $professionType = $user->professionType;

            // Créez le produit en utilisant la relation avec professionType
            $product = $professionType->products()->create($fields);

            return response()->json($product, 201);


    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Récupère un produit spécifique par son ID
        $product = Product::with('professionType')->find($product);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        Gate::authorize('modify', $product);

           //  Gate::authorize('modify',$product);

              // Valider les champs
        $fields = $request->validate([
            'product_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'animal_type' => 'required|string|max:255',
            'description' => 'required|string',
            'picture' => 'nullable|image|mimes:jpg,png,jpeg',
            'price' => 'required|numeric|min:0',
        ]);

        // Vérifiez si un fichier a été téléchargé
        if ($request->hasFile('picture')) {
            // Supprimez l'ancien fichier si nécessaire
            if ($product->picture) {
                $oldImagePath = 'Product_Pic/' . basename($product->picture); // Récupère le nom de fichier
                Storage::disk('public')->delete($oldImagePath);
            }
            // Stockez le nouveau fichier
            $fields['picture'] = $request->file('picture')->store('Product_Pic', 'public');
        }

        // Mettez à jour le produit
        $product->update($fields);

        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Gate::authorize('modify', $product);
       // Vérifiez si le produit a une image associée
    if ($product->picture) {
        // Supprimez l'image du stockage
        Storage::disk('public')->delete($product->picture); // Assurez-vous que le chemin est correct
    }

    // Supprimez le produit de la base de données
    $product->delete();

    return response()->json(['message' => 'Product was deleted'], 200);
    }
}
