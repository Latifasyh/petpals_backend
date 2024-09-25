<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        // Validation des données de la requête
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:accounts',
            'email' => 'required|string|email|max:255|unique:accounts',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Création du compte
        $account = Account::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Création de l'utilisateur avec l'ID du compte
        $user = User::create([
            'account_id' => $account->id, // Associe l'utilisateur au compte
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);

        // Création du token d'authentification
        $token = $account->createToken('Personal Access Token')->plainTextToken;

       // $token = $account->createToken($request->user);

        return response()->json([
            'account' => $account,
            'user' => $user,
            'token' => $token,
        ], 201);




    }

    public function login(Request $request){
        // Validate the request
        $request->validate([
            'email' => 'required|email|exists:accounts,email',  // specifying the column
            'password' => 'required'
        ]);

        // Retrieve the account by email
        $account = Account::where('email', $request->email)->first();

        // Check if account exists and the password is correct
        if (!$account || !Hash::check($request->password, $account->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        // Create a personal access token
        $token = $account->createToken('auth_token');

        // Return the account and the token
        return response()->json([
            'account' => $account,
            'token' => $token->plainTextToken
        ], 201);  // 201 Created status code for successful login
    }

    public function logout(Request $request){

      // $request->account()->Token()->delete();
      /* $request->user()->currentAccessToken()->delete();
        return [
            'message'=> 'you are logged out.'
        ]; */


         $user = $request->user();
        $user->tokens()->delete(); // Pour Laravel Sanctum
        return response()->json(['message' => 'Déconnexion réussie']);

    /* $request->user()->tokens()->delete();  // Revoke all tokens or specific token

    return response()->json(['message' => 'Logged out successfully']);
    } */
    }
}
