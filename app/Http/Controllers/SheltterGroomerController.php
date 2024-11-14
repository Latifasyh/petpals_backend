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
    public function show( $id)
    {
        // Récupère le SheltterGroomer avec l'id spécifié et charge la relation seller
        $sheltterGroomer = SheltterGroomer::with('seller')->findOrFail($id);

        // Si le seller est null, cela signifie qu'il n'y a pas de seller associé à ce SheltterGroomer
        if (!$sheltterGroomer->seller) {
            return response()->json(['error' => 'Seller not associated with this SheltterGroomer'], 404);
        }

        // Retourner le SheltterGroomer avec les informations de seller
        return response()->json($sheltterGroomer);
    }


    /**
     * Update the specified Seller and SheltterGroomer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SheltterGroomer  $shelterGroomer
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, SheltterGroomer $sheltterGroomer)
    {
        // Récupérer le Seller associé au ShelterGroomer via la relation seller
        $seller = $sheltterGroomer->seller;

        // Vérifier si le Seller existe
        if (!$seller) {
            return response()->json(['error' => 'Seller not found.'], 404);
        }

        // Validation des données envoyées pour mettre à jour les informations du Seller
        $validatedData = $request->validate([
            'business_name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'number_phone_pro' => 'sometimes|string|max:20',
            'city' => 'sometimes|string|max:255',
        ]);

        // Mettre à jour les informations du Seller
        $seller->update([
            'business_name' => $validatedData['business_name'] ?? $seller->business_name,
            'address' => $validatedData['address'] ?? $seller->address,
            'number_phone_pro' => $validatedData['number_phone_pro'] ?? $seller->number_phone_pro,
            'city' => $validatedData['city'] ?? $seller->city,
        ]);

        // Retourner la réponse avec les informations mises à jour
        return response()->json([
            'message' => 'Seller updated successfully.',
            'sheltergroomer' => $sheltterGroomer->load('seller'),  // Charger le seller mis à jour
            'seller' => $seller,  // Retourner le seller mis à jour
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
