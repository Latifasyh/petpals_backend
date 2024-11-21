<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PicturesBusiness;
use App\Models\ProfessionTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PicturesBusinessController extends Controller
{
    public function getUserPicturesByBusiness(Request $request, $businessType, $businessId)
    {
        $account = Auth::user();

        if (!$account) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $pictures = PicturesBusiness::where('user_id', $account->id)
            ->where('entity_type', $businessType)
            ->where('profession_types_id', $businessId)
            ->get();

        if ($pictures->isEmpty()) {
            return response()->json(['message' => 'No pictures found'], 404);
        }

        // Formatage des images pour inclure l'URL publique
         $pictures->transform(function ($picture) {
            $picture->url = url('storage/' . $picture->path);  // Génère l'URL complète
            return $picture;
        });

        return response()->json($pictures, 200);
    }

    public function uploadPicturesForBusiness(Request $request, $professionTypesId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $professionType = ProfessionTypes::find($professionTypesId);
        if (!$professionType) {
            return response()->json(['message' => 'Profession type not found'], 404);
        }

        $request->validate([
            'pictures.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('pictures')) {
            foreach ($request->file('pictures') as $file) {
                $path = $file->store("business_pictures/{$professionType->type}", 'public');

                PicturesBusiness::create([
                    'user_id' => $user->id,
                    'entity_type' => $professionType->type,
                    'profession_types_id' => $professionType->id,
                    'path' => $path,
                ]);
            }

            return response()->json(['message' => 'Pictures uploaded successfully'], 200);
        }

        return response()->json(['message' => 'No files uploaded'], 400);
    }


    public function deletePictureById($pictureId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $picture = PicturesBusiness::where('id', $pictureId)->where('user_id', $user->id)->first();

        if (!$picture) {
            return response()->json(['message' => 'Picture not found or not authorized'], 404);
        }

            if (Storage::disk('public')->exists($picture->path)) {
                Storage::disk('public')->delete($picture->path);
            }

        $picture->delete();
        return response()->json(['message' => 'Picture deleted successfully'], 200);
    }

    public function deleteAllPicturesByBusinessType($businessType, $professionTypeId)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Récupérer toutes les photos associées à cet utilisateur, businessType et professionTypeId
    $pictures = PicturesBusiness::where('user_id', $user->id)
                                ->where('entity_type', $businessType)
                                ->where('profession_types_id', $professionTypeId)
                                ->get();

    if ($pictures->isEmpty()) {
        return response()->json(['message' => 'No pictures found for this business type'], 404);
    }

    // Supprimer chaque photo du stockage
    foreach ($pictures as $picture) {
        if (Storage::disk('public')->exists($picture->path)) {
            Storage::disk('public')->delete($picture->path);
        }
        $picture->delete();
    }

    return response()->json(['message' => 'All pictures deleted successfully'], 200);
}

}
