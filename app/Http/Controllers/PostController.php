<?php


namespace App\Http\Controllers;

use App\Models\Post;


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


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::all();
        //pagination has been added here
    }


    public function store(Request $request)
    {



         $fields=$request->validate([
            'body' => 'required|max:255',
            'file' => 'nullable|file|mimes:jpg,png,jpeg,gif,mp4,mov,avi',
            'reaction' => 'nullable|string|max:10',
       ]);

        $fields['date_post'] = now();

             // Gestion du fichier uploadé
        if ($request->hasFile('file')) {
           $fields['file'] = $request->file('file')->store('POSTS', 'public');
           }

               // Récupérer le compte authentifié
              $account = Auth::user();  // Cela récupère le compte actuellement authentifié

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
        return $post;
    }

    /**
     * Update the specified resource in storage.
     */

     public function update(Request $request, Post $post)
     {


         // Vérifier si l'utilisateur a l'autorisation de modifier le post
         Gate::authorize('modify', $post);

         // Validation des champs
         $fields = $request->validate([
             'body' => 'required|max:255',
             'file' => 'nullable|file|mimes:jpg,png,jpeg,gif,mp4,mov,avi',
             'reaction' => 'nullable|string|max:10',
         ]);

         // Mise à jour de la date de publication
         $fields['date_post'] = now();

         // Gestion du fichier uploadé
         if ($request->hasFile('file')) {
             // Optionnel : Supprimer l'ancien fichier si nécessaire
             if ($post->file) {
                 Storage::disk('public')->delete($post->file);
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

