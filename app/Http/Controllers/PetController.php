<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;



class PetController extends Controller implements HasMiddleware
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
        return Pet::orderBy('created_at', 'desc')->get();
        //return Pet::all( );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields=$request->validate([
            'name'=>'nullable|max:225',
            'birthday_pet'=>'nullable|date',
            'family'=>'nullable',
            'breed'=>'nullable',
            'vaccination_agenda'=>'nullable|date',
            'disease'=>'nullable',
            'pet_picture'=> 'nullable|image|mimes:jpg,png,jpeg'
        ]);

        if ($request->hasFile('pet_picture')) {
            $fields['pet_picture'] = $request->file('pet_picture')->store('Pet_Pic', 'public');
            }

            $account = Auth::user();
            $user = $account->user;
            $pet = $user->pets()->create($fields);

            return response()->json($pet, 201);


    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet)
    {
        return $pet;

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pet $pet)
    {
        Gate::authorize('modify',$pet);


        $fields=$request->validate([
            'name'=>'nullable|max:225',
            'birthday_pet'=>'nullable|date',
            'family'=>'nullable',
            'breed'=>'nullable',
            'vaccination_agenda'=>'nullable|date',
            'disease'=>'nullable',
            'pet_picture'=> 'nullable|file|mimes:jpg,png,jpeg'
        ]);

        if ($request->hasFile('pet_picture')) {
            // Supprimer l'ancienne photo si elle existe
            if ($pet->pet_picture) { // Assurez-vous que c'est la bonne propriété
                Storage::disk('public')->delete($pet->pet_picture);
            }

            // Stockage du nouveau fichier
            $fields['pet_picture'] = $request->file('pet_picture')->store('Pet_Pic', 'public');
        }




            $pet->update($fields);

            return response()->json($pet, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet)
    {
        Gate::authorize('modify',$pet);


        if ($pet->file) {
            Storage::disk('public')->delete($pet->file);
        }

        $pet->delete();
        return ['message'=>'pet was deleted'];
    }
}
