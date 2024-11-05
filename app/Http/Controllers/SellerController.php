<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class SellerController extends Controller  implements HasMiddleware
{
    public static function middleware(){


        return [
            new  Middleware('auth:sanctum', except:['index','show'])
        ];

  }

    public function index(Seller $seller)
    {
        $account = Auth::user();
        //return Seller::all();
        $seller=  Seller::with('user.account')->get();
        return response()->json($seller);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $account = Auth::user();
        $user = $account->user;
        $seller = $user->seller()->create([
            'business_name' => $request->input('business_name'),
            'address' => $request->input('address'),
            'city' =>  $request->input('city'), // Validation de la ville
            'number_phone_pro' =>  $request->input('number_phone_pro'), // Validation de la ville

        ]);

/*         $fields=$request->validate([
        'business_name'=>'required|max:225',
        'address'=>'required|max:225'
        ]);

         $account = Auth::user();
        $user = $account->user;
        $seller = $user->sellers()->create($fields); */
        return response()->json($seller, 201);


    }

    /**
     * Display the specified resource.
     */
    public function show(Seller $seller)
    {
          $account = Auth::user();
        $user = $account->user;
        //  return $seller;
      return response()->json([
            'account' => $account,
            'user' => $user,
            'seller'=>$seller,
            //'token' => $token,
        ], 201);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seller $seller)
    {
        // Autoriser uniquement l'utilisateur ayant les droits de modification
        Gate::authorize('modify', $seller);

        // Valider les données d'entrée
        $fields = $request->validate([
            'business_name' => 'nullable|max:225',
            'address' => 'nullable|max:225',
            'city' => 'nullable|string|max:100',
            'number_phone_pro' => 'nullable|string|max:100',
        ]);

        // Mettre à jour les informations du vendeur
        $seller->update($fields); // Utilisez l'instance $seller directement

        // Retourner une réponse JSON avec le vendeur mis à jour
        return response()->json($seller, 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seller $seller)
    {
        Gate::authorize('modify',$seller);
        $seller->delete();
        return ['message'=>'seller was deleted'];
    }
}
