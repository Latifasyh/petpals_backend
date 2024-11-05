<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PicturesBusiness;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PicturesBusinessController extends Controller
{
    public function getPictures($entityId, $entityType)
{
    // Récupérer les images en fonction de l'entité
    $pictures = PicturesBusiness::where('entity_id', $entityId)
        ->where('entity_type', $entityType)
        ->get();

    if ($pictures->isEmpty()) {
        return response()->json(['message' => 'No pictures found'], 404);
    }

    return response()->json($pictures);
}
/*
    public function getPictures($entityId, $entityType)
{
    // Récupérer l'utilisateur authentifié
    $account = Auth::user();

    // Vérifier si l'utilisateur est authentifié
    if (!$account) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Récupérer les images en fonction de l'entityType
    try {
        $pictures = null;

        switch ($entityType) {
            case 'seller':
                $pictures = PicturesBusiness::where('entity_id', $entityId)
                    ->where('entity_type', 'seller')
                    ->where('seller_id', $account->id) // Utiliser le bon champ
                    ->get();
                break;
            case 'sheltter':
                $pictures = PicturesBusiness::where('entity_id', $entityId)
                    ->where('entity_type', 'sheltter')
                    ->where('shelttergroomers_id', $account->id)
                    ->get();
                break;
            case 'veto':
                $pictures = PicturesBusiness::where('entity_id', $entityId)
                    ->where('entity_type', 'veto')
                    ->where('veto_id', $account->id)
                    ->get();
                break;
            default:
                return response()->json(['message' => 'Invalid entity type'], 400);
        }

        // Vérifier si des photos ont été trouvées
        if ($pictures->isEmpty()) {
            return response()->json(['message' => 'No pictures found'], 404);
        }

        return response()->json($pictures);
    } catch (\Exception $e) {
        // Capturer les exceptions et retourner une erreur 500
        return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
    }
} */

    // Ajouter des photos pour une entité (Seller, SheltterGroomer, Veto)
    public function addPictures(Request $request, $entityId, $entityType)
    {
        // Validation des fichiers
        $request->validate([
            'pictures.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Limite de taille à 2 Mo
        ]);

        // Vérifier si des fichiers sont téléchargés
        if ($request->hasFile('pictures')) {
            foreach ($request->file('pictures') as $file) {
                // Stocker le fichier
                $path = $file->store('business_pictures', 'public');

                // Enregistrer le chemin dans la base de données
                PicturesBusiness::create([
                    'entity_id' => $entityId,
                    'entity_type' => $entityType,
                    'path' => $path,
                ]);
            }
        }

        return response()->json(['message' => 'Pictures added successfully'], 201);
    }

    // Supprimer une photo
    public function deletePicture($pictureId)
    {
        $picture = PicturesBusiness::findOrFail($pictureId);

        // Supprimer le fichier du stockage
        if (Storage::disk('public')->exists($picture->path)) {
            Storage::disk('public')->delete($picture->path);
        }

        // Supprimer l'entrée de la base de données
        $picture->delete();

        return response()->json(['message' => 'Picture deleted successfully']);
    }
}
