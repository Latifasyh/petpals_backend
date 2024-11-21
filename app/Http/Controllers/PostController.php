<?php


namespace App\Http\Controllers;

use App\Models\Post;


use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class PostController extends Controller implements HasMiddleware
{

    public static function middleware(){


            return [
                new  Middleware('auth:sanctum', except:['index','show'])
            ];
      }

      // app/Http/Controllers/PostController.php

public function index(Request $request)
{
    // Récupérer l'utilisateur actuellement authentifié
    $account = Auth::user();



    // Vérifier si un type de post est passé dans la requête
    $type = $request->input('type');

    // Si un type est fourni, filtrer par type
    if ($type && in_array($type, ['normal', 'astuce', 'emergency'])) {
        // Récupérer les posts de l'utilisateur authentifié filtrés par type
        $posts = Post::with(['user' => function ($query) {
            $query->with(['account:id,username']); // Sélectionne uniquement l'ID et le username
            $query->select('id', 'account_id', 'picture'); // Sélectionne uniquement l'ID, l'account_id et la photo de profil
                    }])->where('type', $type)
                     ->orderBy('created_at', 'desc') // Trier par date de création décroissante
                     ->get();
    } else {
        // Sinon, récupérer tous les posts de l'utilisateur
        $posts = Post::with(['user' => function ($query) {
            $query->with(['account:id,username']);
            $query->select('id', 'account_id', 'picture');
                       }])->orderBy('created_at', 'desc')
                        ->get();
    }

    // Optionnel: Générer l'URL complète pour les fichiers attachés
    foreach ($posts as $post) {
        if ($post->file) {
            $post->file = asset('storage/' . $post->file);
        }
    }

    // Retourner les posts
   return response()->json($posts);
        /* $user = $account->user;
        return response()->json([
            'account' => $account,
            'user' => $user,
            'post'=> $post,
        ], 201); */


}


    /**
     * Display a listing of the resource.
     */
   /*  public function index()
    {
        // Récupérer l'utilisateur actuellement authentifié
        $account = Auth::user();

        // Récupérer les posts avec les utilisateurs et leurs comptes
        $posts = Post::with(['user.account'])->get();

        // Optionnel: Générer l'URL complète pour les fichiers attachés
        foreach ($posts as $post) {
            if ($post->file) {
                $post->file = asset('storage/' . $post->file);
            }
        }

        // Retourner les posts sous forme de JSON avec les relations
        return response()->json($posts);
    } */



   /*  public function getPostsByType($type)
    {
        // Validation du type de post (normal, astuce, emergency)
        if (!in_array($type, ['normal', 'astuce', 'emergency'])) {
            return response()->json(['error' => 'Invalid post type'], 400);
        }

        // Récupérer l'utilisateur authentifié via Sanctum
        $user = Auth::user();

        // Vérifier si l'utilisateur est authentifié
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Récupérer les posts de l'utilisateur authentifié et filtrer par type
        $posts = Post::where('type', $type)
                    ->where('user_id', $user->id)  // Assurer que seuls les posts de l'utilisateur sont récupérés
                    ->get(['body', 'file']);

        // Vérifier si des posts existent
        if ($posts->isEmpty()) {
            return response()->json(['message' => 'No posts found for this type'], 404);
        }

        // Ajouter l'URL publique pour chaque fichier (image ou vidéo) enregistré dans storage
        $posts->map(function ($post) {
            if ($post->file) {
                $post->file_url = asset('storage/' . $post->file);
            }
            return $post;
        });

        // Retourner les posts au format JSON
        return response()->json($posts, 200);
    }
 */


 public function store(Request $request)
 {
     // Validation des champs
     $fields = $request->validate([
         'body' => 'nullable|max:255',
         'file' => 'nullable|file|mimes:jpg,png,jpeg,gif,mp4,mov,avi',
         'type' => 'required|string|in:normal,astuce,emergency',
     ]);

     // Gestion du fichier uploadé
     if ($request->hasFile('file')) {
         $fields['file'] = $request->file('file')->store('POSTS', 'public');
     }

     // Récupérer le compte authentifié
     $account = Auth::user();

     // Récupérer l'utilisateur associé au compte
     $user = $account->user;

     // Créer le post pour cet utilisateur
     $post = $user->posts()->create($fields);

     return response()->json($post, 201);
 }

    /**
     * Display the specified resource.
     */
     public function show(post $post)
    {

     // return $post;
     $account = Auth::user();

      $user = $account->user;
      return response()->json([
        'post' => $post, // Renvoie tous les attributs du post
        'account' => [
            'username' => $account->username, // Le username de l'account
            'userpicture' => $user->picture, // La photo de profil de l'utilisateur
        ],
         ], 200); // 200 pour une requête réussie

    }

    /**
     * Update the specified resource in storage.
     */

    //  public function blabla(Request $request, Post $post)
    //  {
    //     return $post;
    //  }

    public function update(Request $request, Post $post)
    {
        // Vérifier si l'utilisateur a l'autorisation de modifier le post
        Gate::authorize('modify', $post);

        // Validation des champs
        $fields = $request->validate([
            'body' => 'nullable|max:255',
            'file' => 'nullable|file|mimes:jpg,png,jpeg,gif,mp4,mov,avi',
            'type' => 'required|string|in:normal,astuce,emergency',
        ]);
 
        // Gestion du fichier uploadé
        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier s'il existe
            if ($post->file) {
                // Vérifier si le fichier existe
                if (Storage::disk('public')->exists($post->file)) {
                    Storage::disk('public')->delete($post->file);
                }
            }

            // Stockage du nouveau fichier
            $fields['file'] = $request->file('file')->store('POSTS', 'public');
        }


        // Mise à jour du post avec les nouveaux champs
        $post->update($fields);

        // Retourner la publication mise à jour
        return response()->json($post, 200);
    }



    /**
     * Remove the specified resource from storage.
     */
     public function destroy(post $post)
    {

        Gate::authorize('modify',$post);

        if ($post->file) {
            Storage::disk('public')->delete($post->file);
        }

        $post->delete();
        return ['message'=>'post was deleted'];
        //return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
