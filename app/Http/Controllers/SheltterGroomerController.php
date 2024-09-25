<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Models\SheltterGroomer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class SheltterGroomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return SheltterGroomer::all();
        // Récupérer tous les SheltterGroomer
        $sheltterGroomers = SheltterGroomer::with('seller')->get();

        // Retourner les SheltterGroomers en réponse JSON
        return response()->json($sheltterGroomers, 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Create Seller entry
        $seller = Seller::create([
            'user_id' => $user->id,
            'business_name' => $request->business_name,
            'address' => $request->address,
        ]);

        // Create ShelterGroomer entry
        $sheltterGroomer = SheltterGroomer::create([
            'user_id' => $user->id,
            'seller_id' => $seller->id,
        ]);
        return response()->json($sheltterGroomer, 201);

       /*  return response()->json([
            'sheltterGroomer' => $sheltterGroomer,
            'user' => $sheltterGroomer->user,
            'seller' => $sheltterGroomer->seller,
        ], 201); */


    }

    /**
     * Display the specified resource.
     */
    public function show(SheltterGroomer $sheltterGroomer)
    {

           // Charger les relations User et Seller avec les colonnes spécifiées
            $sheltterGroomer = SheltterGroomer::with(['user:id,first_name,last_name,ville', 'seller:id,business_name,address'])->get();

            // Retourner les données avec seulement les attributs souhaités
            $result = $sheltterGroomer->map(function($sheltterGroomer) {
                return [
                    'id' => $sheltterGroomer->id,
                    'user' => [
                        'first_name' => $sheltterGroomer->user->first_name,  // Utilisation correcte des attributs
                        'last_name' => $sheltterGroomer->user->last_name,
                        'ville' => $sheltterGroomer->user->ville,
                    ],
                    'seller' => [
                        'business_name' => $sheltterGroomer->seller->business_name,
                        'address' => $sheltterGroomer->seller->address,  // Utilisation correcte des attributs
                    ],
                ];
            });

            return response()->json($result, 200);





                // Charger toutes les données de SheltterGroomer avec les relations 'user' et 'seller'
            //$sheltterGroomers = SheltterGroomer::with(['user', 'seller'])->get();

            // Retourner les données avec toutes les informations des relations
           // return response()->json($sheltterGroomers, 200);



           /*      // Trouver le ShelterGroomer avec ses relations 'user' et 'seller'
            $sheltterGroomer = SheltterGroomer::with(['user', 'seller'])->find($sheltterGroomer);

            if (!$sheltterGroomer) {
                return response()->json(['message' => 'SheltterGroomer not found'], 404);
            }

            // Retourner toutes les informations de ShelterGroomer, User et Seller
            return response()->json([
                'sheltterGroomer' => $sheltterGroomer,
                'user' => $sheltterGroomer->user,
                'seller' => $sheltterGroomer->seller,
            ], 200);
 */


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SheltterGroomer $sheltterGroomer)
    {
        Gate::authorize('modify',$sheltterGroomer);

                    // Validation des données entrantes pour business_name et address
            $validated = $request->validate([
                'business_name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
            ]);

            // Trouver l'utilisateur connecté
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            // Trouver le SheltterGroomer associé à l'utilisateur
            $sheltterGroomer = SheltterGroomer::where('user_id', $user->id)->first();
            if (!$sheltterGroomer) {
                return response()->json(['error' => 'SheltterGroomer not found'], 404);
            }

            // Trouver le Seller associé
            $seller = Seller::find($sheltterGroomer->seller_id);
            if (!$seller) {
                return response()->json(['error' => 'Seller not found'], 404);
            }

            // Mise à jour des attributs du Seller
            $seller->business_name = $validated['business_name'];
            $seller->address = $validated['address'];

            // Sauvegarde des modifications²
            $seller->save();

            // Trouver les attributs modifiés
            $updatedAttributes = $seller->getChanges();

             // Retourner uniquement les attributs modifiés
            return response()->json($updatedAttributes, 200);

            /* return response()->json([
                'updated_seller' => $updatedAttributes,
                'sheltter_groomer' => $sheltterGroomer,
            ], 200); */

            // Retourner uniquement les attributs modifiés
          //  return response()->json($updatedAttributes, 200);

            // Retourner le SheltterGroomer mis à jour
           // return response()->json($sheltterGroomer, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SheltterGroomer $sheltterGroomer)
    {
        Gate::authorize('modify',$sheltterGroomer);

            // Trouver l'utilisateur connecté
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Trouver le SheltterGroomer associé à l'utilisateur
        $sheltterGroomer = SheltterGroomer::where('user_id', $user->id)->first();
        if (!$sheltterGroomer) {
            return response()->json(['error' => 'SheltterGroomer not found'], 404);
        }

        // Trouver le Seller associé
        $seller = Seller::find($sheltterGroomer->seller_id);
        if (!$seller) {
            return response()->json(['error' => 'Seller not found'], 404);
        }

        // Supprimer le SheltterGroomer
        $sheltterGroomer->delete();

        // Supprimer le Seller associé
        $seller->delete();

        // Retourner une réponse de succès
        return response()->json(['message' => 'SheltterGroomer and associated Seller deleted successfully'], 200);
        }

}
