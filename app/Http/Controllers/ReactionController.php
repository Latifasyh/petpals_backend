<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    public function index()
    {
        $reactions = Reaction::with('Posts')->get();
       // $services = Service::all();
        return response()->json($reactions);
    }
    // Ajouter une réaction au post
    public function store(Request $request, $postId)
    {
        // Valider les données entrantes
        $request->validate([
            'reaction_type' => 'required|string|in:like,dislike,angry,love,laugh,sad',
        ]);

        // Récupérer le compte authentifié via le token
        $account = Auth::user();

        // Récupérer l'utilisateur associé à ce compte
        $user = $account->user;

        // Trouver le post par ID
        $post = Post::findOrFail($postId);

        // Créer la réaction
        $reaction = Reaction::create([
            'user_id' => $user->id, // Associer la réaction à l'utilisateur
            'post_id' => $post->id,
            'reaction_type' => $request->reaction_type, // Le type de réaction (like, love, etc.)
        ]);

        return response()->json($reaction, 201);
    }

    // Modifier une réaction existante
    public function update(Request $request, $reactionId)
    {
        $request->validate([
            'reaction_type' => 'required|string|in:like,dislike,angry,love,laugh,sad',
        ]);

        // Récupérer la réaction
        $reaction = Reaction::findOrFail($reactionId);

        // Vérifier que l'utilisateur authentifié est bien celui qui a posté la réaction
        $account = Auth::user();
        $user = $account->user;

        if ($reaction->user_id !== $user->id) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        // Mettre à jour la réaction
        $reaction->update([
            'reaction_type' => $request->reaction_type,
        ]);

        return response()->json($reaction, 200);
    }

    // Supprimer une réaction
    public function destroy($reactionId)
    {
        // Récupérer la réaction
        $reaction = Reaction::findOrFail($reactionId);

        // Vérifier que l'utilisateur authentifié est bien celui qui a posté la réaction
        $account = Auth::user();
        $user = $account->user;

        if ($reaction->user_id !== $user->id) {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        // Supprimer la réaction
        $reaction->delete();

        return response()->json(['message' => 'Réaction supprimée avec succès.'], 200);
    }
}
