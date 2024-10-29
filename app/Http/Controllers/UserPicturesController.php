<?php

namespace App\Http\Controllers;

use App\Models\UserPictures;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserPicturesRequest;
use App\Http\Requests\UpdateUserPicturesRequest;

class UserPicturesController extends Controller
{
   /* public function uploadPhotos(Request $request)
    {
        $request->validate([
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Gestion des photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('user_photos', 'public');
                $user->photos()->create(['path' => $path]);
            }
        }

        return response()->json($user->load('photos'), 201);
    }

    // Méthode pour récupérer les photos d'un utilisateur
    public function getPhotos()
    {
        $user = Auth::user();
        return response()->json($user->photos);
    }

    // Méthode pour supprimer une photo
    public function destroy(UserPhoto $userPhoto)
    {
        // Assurez-vous que la photo appartient à l'utilisateur authentifié
        if ($userPhoto->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Supprimez le fichier de disque
        \Storage::disk('public')->delete($userPhoto->path);

        // Supprimez l'entrée de la base de données
        $userPhoto->delete();

        return response()->json(['message' => 'Photo deleted successfully']);
    } */
}
