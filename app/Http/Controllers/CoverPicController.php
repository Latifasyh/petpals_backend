<?php

namespace App\Http\Controllers;

use App\Models\CoverPic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;




class CoverPicController extends Controller implements HasMiddleware
{
    public static function middleware(){


        return [
            new  Middleware('auth:sanctum', except:['index','show'])
        ];

  }
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CoverPic $coverPic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


        // Mettre à jour la photo de couverture
    public function update(Request $request, CoverPic $coverPic)
        {
            Gate::authorize('modify',$coverPic);
            $request->validate([
                'cover_photo' => 'required|file|mimes:jpg,png,jpeg|max:2048',
            ]);

            $account = Auth::user();
            if (!$account) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $user = $account->user;

            // Vérifier si une photo de couverture existe déjà
            if ($user->coverPic) {
                // Supprimer l'ancienne photo de couverture
                Storage::disk('public')->delete('cover_photos/' . $user->coverPic);
            }

            // Stocker la nouvelle image et récupérer son chemin
            $coverPic_name = $request->file('cover_photo')->store('cover_photos', 'public');

            // Mettre à jour le champ 'cover_photo' avec le nom de l'image
            $user->coverPic = basename($coverPic_name);
            $user->save();

            return response()->json([
                'message' => 'Photo de couverture mise à jour avec succès.',
                'cover_photo_url' => asset('storage/cover_photos/' . $user->coverPic),
            ], 200);
        }

        // Supprimer la photo de couverture
       /*  public function delete(Request $request)
        {
            $account = Auth::user();
            if (!$account) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $user = $account->user;

            // Vérifier si une photo de couverture existe
            if ($user->cover_photo) {
                // Supprimer la photo de couverture
                Storage::disk('public')->delete('cover_photos/' . $user->cover_photo);
                $user->cover_photo = null; // Réinitialiser le champ cover_photo
                $user->save();

                return response()->json(['message' => 'Photo de couverture supprimée avec succès.'], 200);
            }

            return response()->json(['error' => 'Aucune photo de couverture à supprimer.'], 404);
        } */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CoverPic $coverPic)
    {
        Gate::authorize('modify',$coverPic);

        Gate::authorize('modify',$coverPic);


        if ($coverPic->file) {
            Storage::disk('public')->delete($coverPic->file);
        }

        $coverPic->delete();
        return ['message'=>'pet was deleted'];

    }
}
