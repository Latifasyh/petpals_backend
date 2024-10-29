<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use Illuminate\Http\Request; // Importer la classe Request de Laravel
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class DiscussionController extends Controller implements HasMiddleware
{
    public static function middleware(){


        return [
            new  Middleware('auth:sanctum', except:['index','show'])
        ];

  }

  public function index(Discussion $discussion)
  {
      // Récupérer l'utilisateur actuellement authentifié
      $account = Auth::user();

      // Récupérer toutes les discussions avec les informations de l'utilisateur et du compte associé
      $discussion = Discussion::with(['user' => function ($query) {
          $query->with(['account:id,username']); // Inclure uniquement l'ID et le username du compte
          $query->select('id', 'account_id', 'picture'); // Inclure uniquement l'ID, l'account_id, et la photo de profil de l'utilisateur
      }])
      ->orderBy('created_at', 'desc') // Trier par date de création décroissante
      ->get();

      // Retourner les discussions
      return response()->json($discussion);
  }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'message' => 'nullable|max:255', // Validation pour le champ discussion
            'file_msg'=>'nullable|file|mimes:jpg,png,jpeg'
        ]);
        if ($request->hasFile('file_msg')) {
            $fields['file_msg'] = $request->file('file_msg')->store('MSG_File', 'public');
            }


        $account = Auth::user();
            $user = $account->user;
            $discussion = $user->discussion()->create($fields);

            return response()->json($discussion, 201);
    }

    // Récupérer tous les messages
    public function show(Discussion $discussion)
    {
        return $discussion ;

       /*  // Récupération de l'utilisateur authentifié
        $account = Auth::user();

        // Vérification que l'utilisateur a un profil
        $user = $account->user; // Assurez-vous que cette relation est définie correctement dans votre modèle Account

        // Vérification si l'utilisateur a une photo de profil
        $userPicture = $user->picture ? url('storage/' . $user->picture) : null; // Ajustez ce chemin si nécessaire

        return response()->json([
            'discussion' => $discussion, // Renvoie tous les attributs de la discussion
            'account' => [
                'username' => $account->username, // Le username de l'account
                'userpicture' => $userPicture, // La photo de profil de l'utilisateur
            ],
        ], 200); // Code de réponse 200 OK */
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discussion $discussion)
    {
        // Autoriser uniquement si l'utilisateur a les droits
        Gate::authorize('modify', $discussion);

        // Valider les champs de la requête
        $fields = $request->validate([
            'message' => 'nullable|max:255',
            'file_msg' => 'nullable|file|mimes:jpg,png,jpeg'
        ]);

        // Gérer le fichier si présent
        if ($request->hasFile('file_msg')) {
            // Supprimer l'ancienne photo si elle existe
            if ($discussion->file_msg) {
                Storage::disk('public')->delete('MSG_File/' .$discussion->file_msg);
            }

            // Stockage du nouveau fichier
            $fields['file_msg'] = $request->file('file_msg')->store('MSG_File', 'public');
        }

        // Mise à jour des champs de la discussion
        $discussion->update($fields);

        // Retourner la réponse JSON avec les données mises à jour
        return response()->json($discussion, 201);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discussion $discussion)
    {
        Gate::authorize('modify',$discussion);


        if ($discussion->file_msg) {
            Storage::disk('public')->delete($discussion->file_msg);
        }

        $discussion->delete();
        return ['message'=>'message  was deleted'];
    }

}
