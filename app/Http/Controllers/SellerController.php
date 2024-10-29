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
            'address' => $request->input('address')

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
         Gate::authorize('modify',$seller);


         $fields=$request->validate([
        'business_name'=>'required|max:225',
        'address'=>'required|max:225'
        ]);


        $seller-> update($fields);
        return response()->json($seller, 201);

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
