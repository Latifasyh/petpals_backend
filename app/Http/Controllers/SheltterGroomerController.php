<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Models\SheltterGroomer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SheltterGroomerController extends Controller
{
    /**
     * Store a newly created Seller and SheltterGroomer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SheltterGroomer $shelterGroomer,Request $request)
    {
        // Valider les données entrantes
        $validatedData = $request->validate([
            'business_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'number_phone_pro' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id' // Assurez-vous que l'utilisateur existe
        ]);

        // Créer un Seller
        $seller = Seller::create([
            'business_name' => $validatedData['business_name'],
            'address' => $validatedData['address'],
            'number_phone_pro' => $validatedData['number_phone_pro'],
            'city' => $validatedData['city'],
            'user_id' => $validatedData['user_id'],
        ]);

        // Créer un SheltterGroomer en utilisant l'ID du Seller
        $shelterGroomer = SheltterGroomer::create([
            'user_id' => $validatedData['user_id'],
            'seller_id' => $seller->id,
        ]);

        return response()->json([
            'message' => 'SheltterGroomer created successfully.',
            'sheltergroomer' => $shelterGroomer,
            'seller' => $seller
        ], 201);
    }

    /**
     * Display a listing of the shelter groomers.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $shelters = SheltterGroomer::with('seller')->get(); // Récupère tous les groomers avec leurs sellers
        return response()->json($shelters);
    }

    /**
     * Display the specified shelter groomer.
     *
     * @param  \App\Models\SheltterGroomer  $shelterGroomer
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(SheltterGroomer $shelterGroomer)
    {
        return response()->json($shelterGroomer->load('seller')); // Charge le seller associé
    }

    /**
     * Update the specified Seller and SheltterGroomer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SheltterGroomer  $shelterGroomer
     * @return \Illuminate\Http\JsonResponse
     */

     public function update(SheltterGroomer $shelterGroomer, Request $request)
     {
         // Authentification de l'utilisateur
         $account = Auth::user();

         // Vérifiez si l'utilisateur a un vendeur associé
         $seller = $account->seller; // Récupérez le seller associé à l'utilisateur

         // Vérifiez si le seller est bien associé à ce shelterGroomer
         if ($shelterGroomer->seller_id !== $seller->id) {
             return response()->json(['error' => 'No associated Seller found for this SheltterGroomer.'], 404);
         }

         // Validation des données entrantes
         $validatedData = $request->validate([
             'business_name' => 'sometimes|string|max:255',
             'address' => 'sometimes|string|max:255',
             'number_phone_pro' => 'sometimes|string|max:20',
             'city' => 'sometimes|string|max:255',
         ]);

         // Mise à jour des informations du seller
         $seller->update(array_filter([
             'business_name' => $validatedData['business_name'] ?? $seller->business_name,
             'address' => $validatedData['address'] ?? $seller->address,
             'number_phone_pro' => $validatedData['number_phone_pro'] ?? $seller->number_phone_pro,
             'city' => $validatedData['city'] ?? $seller->city,
         ]));

         // Retourne le response avec les informations mises à jour
         return response()->json([
             'message' => 'SheltterGroomer updated successfully.',
             'shelter_groomer' => $shelterGroomer->load('seller'),
         ]);
     }


    /**
     * Remove the specified shelter groomer from storage.
     *
     * @param  \App\Models\SheltterGroomer  $shelterGroomer
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(SheltterGroomer $shelterGroomer)
    {
        // Supprimer le Seller associé si nécessaire
        if ($shelterGroomer->seller) {
            $shelterGroomer->seller->delete();
        }

        $shelterGroomer->delete();

        return response()->json(['message' => 'SheltterGroomer deleted successfully.']);
    }
}
