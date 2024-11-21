<?php

namespace App\Http\Controllers;

use App\Models\CoverPic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CoverPicController extends Controller
{
    // Récupérer la couverture
    public function showCover()
    {
        $user = Auth::user();
        $coverPic = CoverPic::firstOrNew(['user_id' => $user->id]);

        /*  return $coverPic->cover
            ? response()->json(['cover' => asset('storage/' . $coverPic->cover)])
            : response()->json(['message' => 'No cover found'], 404); */

            return response()->json([
              //  'message' => 'Cover mise à jour avec succès.',
                'cover_url' => asset('storage/' . $coverPic->cover), // URL publique pour accéder à l'image
            ], 200);
    }



 public function showBio()
    {
    $user = Auth::user(); // Récupérer l'utilisateur authentifié

    // Récupérer directement la bio du CoverPic de l'utilisateur
    $bio = CoverPic::where('user_id', $user->id)->value('bio');

    return response()->json([$bio]);
    
    // Vérifier si une bio a été trouvée
   /*  if ($bio) {
        return response()->json(['bio' => $bio]);
    }

    return response()->json(['message' => 'No bio found'], 404); */
}

public function updateOrCreateCover(Request $request)
{
    // Validation du fichier image
    $request->validate([
        'cover' => 'nullable|file|mimes:jpeg,jpg,png,gif,svg|max:2048',
    ]);

    // Récupérer l'utilisateur authentifié
    $user = Auth::user();

    // Trouver ou créer un CoverPic pour cet utilisateur
    $coverPic = CoverPic::firstOrNew(['user_id' => $user->id]);

    // Si un fichier est téléchargé
    if ($request->hasFile('cover')) {
        // Si une photo de couverture existe déjà, supprimer l'ancienne photo
        if ($coverPic->cover) {
            Storage::delete($coverPic->cover);

        }

        // Stocker la nouvelle photo de couverture
        $coverPic->cover = $request->file('cover')->store('cover', 'public');
    }

    // Sauvegarder les modifications dans la base de données
    $coverPic->save();

    // Retourner une réponse avec l'URL de la nouvelle photo de couverture
    return response()->json([
        'message' => 'Cover mise à jour avec succès.',
        'cover_url' => asset('storage/' . $coverPic->cover), // URL publique pour accéder à l'image
    ], 200);
}

    // Mettre à jour ou créer la bio
    public function updateOrCreateBio(Request $request)
    {
        $request->validate([
            'bio' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $coverPic = CoverPic::firstOrNew(['user_id' => $user->id]);

        $coverPic->bio = $request->input('bio', $coverPic->bio);
        $coverPic->save();

        return response()->json([
            'message' => 'Bio mise à jour avec succès',
            'bio' => $coverPic->bio,
        ]);
    }

    // Supprimer la couverture
    public function deleteCover()
    {
        $user = Auth::user();
        $coverPic = CoverPic::firstOrNew(['user_id' => $user->id]);

        if ($coverPic->cover) {
            Storage::delete($coverPic->cover);
            $coverPic->cover = null;
            $coverPic->save();

            return response()->json(['message' => 'Cover supprimée avec succès']);
        }

        return response()->json(['message' => 'No cover found'], 404);
    }

    // Supprimer la bio
    public function deleteBio()
    {
        $user = Auth::user();
        $coverPic = CoverPic::firstOrNew(['user_id' => $user->id]);

        if ($coverPic->bio) {
            $coverPic->bio = null;
            $coverPic->save();

            return response()->json(['message' => 'Bio supprimée avec succès']);
        }

        return response()->json(['message' => 'No bio found'], 404);
    }
}
