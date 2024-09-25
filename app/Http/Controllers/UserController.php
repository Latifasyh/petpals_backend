<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\File;


class UserController extends Controller implements HasMiddleware
{
    public static function middleware(){


        return [
            new  Middleware('auth:sanctum')
        ];

  }


    /* public function index()
    {
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();

        if ($user) {
            return response()->json($user, 200); // Retourner les données utilisateur
        } else {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404); // Gestion de l'erreur
        }
    } */

    public function index(Request $request)
    {
        // Assuming the user is authenticated
      /*   $user = $request->user();
        return response()->json($user); */
        $account = Auth::user();
        $user = $account->user;
        //  return $seller;
      return response()->json([
            'account' => $account,
             'user' => $user,

            //'token' => $token,
        ], 201);
    }

    /* public function index (Request $request){

        $request->validate([
            'number_phone' => 'nullable|string|max:255',
            'phonecode' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'country' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'family_situation' => 'nullable|string',
            'gender' => 'nullable|string',
            'picture' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

             // Gestion du fichier uploadé
          if ($request->hasFile('picture')) {
           $fields['picture'] = $request->file('picture')->store('profil_picture', 'public');
           }



           $user=$request->account()->user()->update($fields);

        return response()->json([
            'message' => 'Profil complété avec succès.',
            'user' => $user,
        ], 200);

    } */
    public function getUser(Request $request) {
           // $user = $request->user(); // Assurez-vous que l'utilisateur est authentifié
            // Assurez-vous que l'utilisateur est authentifié
          //  $user = $request->user();
            $account = Auth::user();
            if (!$account) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }


            $user = $account->user;
            // Récupérez l'instance Account liée à cet utilisateur
          //  $account = $user->account();

            return response()->json([

                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'username' => $user->username,
                'Number_phone' => $user->Number_phone,
                'phonecode' =>$user->phonecode,
                'birthday' => $user->birthday,
                'country' =>  $user->country,
                'ville' =>  $user->ville,
                'family_situation' => $user->family_situation,
                'gender' =>  $user->gender,
                'picture' =>  $user->picture,
                'token' => $request->bearerToken(), // Si nécessaire pour l'authentification

                 // Récupérer le token à partir du modèle Account
                 // 'token' => $account->token, // Assurez-vous que 'token' est bien défini dans le modèle Account
                    //'token' => $account ? $account->token : null, // Si l'utilisateur n'a pas de compte, renvoyer null
            ]);
    }


    public function show(User $user){

        return $user ;

    }


    public function update(Request $request)
    {
        $account = Auth::guard('sanctum')->user();
        if (!$account) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = $account->user;

        // Valider les données reçues, y compris la photo
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'number_phone' => 'nullable|string|max:255',
            'phonecode' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'country' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'family_situation' => 'nullable|string',
            'gender' => 'nullable|string',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Préparer les données utilisateur sans toucher à la photo
        $user_data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phonecode' => $request->input('phonecode'),
            'number_phone' => $request->input('number_phone'),
            'birthday' => $request->input('birthday'),
            'country' => $request->input('country'),
            'ville' => $request->input('ville'),
            'family_situation' => $request->input('family_situation'),
            'gender' => $request->input('gender')
        ];

        // Gérer la mise à jour de la photo de profil
        if ($request->hasFile('picture')) {
            $image_name = time() . '.' . $request->picture->extension();
            $request->picture->move(public_path('storage/profil_picture'), $image_name);

            // Supprimer l'ancienne image si elle existe dans 'public/storage/profil_pictures'
            $old_path = public_path('storage/profil_picture/' . $user->picture);
            if (File::exists($old_path)) {
                File::delete($old_path);
            
            }

            // Mettre à jour la nouvelle photo dans la base de données
            $user_data['picture'] = $image_name;
        }

        // Mettre à jour les autres informations de l'utilisateur
        $user->update($user_data);

        return response()->json([
            'message' => 'Profil mis à jour avec succès.',
            'user' => $user,
        ], 200);
    }







    public function destroy(user $user){

        $user->delete();
        return ['message'=>'user was deleted'];


    }
}
