<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfessionTypes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class ProfessionTypesController extends Controller implements HasMiddleware
{

    public static function middleware(){


        return [
            new  Middleware('auth:sanctum', except:['index','show'])
        ];

    }
    // Afficher tous les types de profession
    public function index()
    {
        $professionTypes = ProfessionTypes::all();
        return response()->json($professionTypes);
    }

    public function getByType($type)
    {
        if (!in_array($type, ['seller', 'veto', 'sheltter'])) {
            return response()->json(['message' => 'Invalid profession type'], 400);
        }

        $professionTypes = ProfessionTypes::where('type', $type)->get();

        if ($professionTypes->isEmpty()) {
            return response()->json(['message' => 'No profession types found for the given type'], 404);
        }

        return response()->json($professionTypes, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:seller,veto,sheltter',
            'business_name' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'num_pro' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $professionType = ProfessionTypes::create(array_merge($request->all(), ['user_id' => $user->id]));
        return response()->json($professionType, 201);
    }

    public function show($id)
    {
        $professionType = ProfessionTypes::find($id);

        if (!$professionType) {
            return response()->json(['message' => 'Profession type not found'], 404);
        }

        return response()->json($professionType);
    }

    public function update($id, Request $request)
    {
        $professionType = ProfessionTypes::find($id);

        if (!$professionType) {
            return response()->json(['message' => 'Profession type not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:seller,veto,sheltter',
            'business_name' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'num_pro' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $professionType->update($request->all());
        return response()->json($professionType);
    }

    public function destroy($id)
    {
        $professionType = ProfessionTypes::find($id);

        if (!$professionType) {
            return response()->json(['message' => 'Profession type not found'], 404);
        }

        $professionType->delete();
        return response()->json(['message' => 'Profession type deleted successfully']);
    }
}
