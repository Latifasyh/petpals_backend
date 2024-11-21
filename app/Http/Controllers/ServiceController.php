<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ProfessionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    // Affiche la liste de tous les services
    public function index()
    {
        $services = Service::with('professionType')->orderBy('created_at', 'desc')->get();
       // $services = Service::all();
        return response()->json($services);
    }

    // Affiche les détails d'un service spécifique
    public function show($id)
    {
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
        return response()->json($service);
    }

    // Crée un nouveau service
    public function store(Request $request)
    {
        // Validation des données entrantes
        $fields = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'price_type' => 'required|in:par séance,par jour',
                //'picture'=>'array',
                'picture.*' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation de l'image
                
            ]);


        // Vérification de l'authentification de l'utilisateur
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        $account = Auth::user();
        $user = $account->user;

        // Vérifiez si l'utilisateur a un professionType
        if (!$user->professionType) {
            return response()->json(['message' => 'User does not have a profession type.'], 400);
        }

        if ($request->hasFile('picture')) {
            $fields['picture'] = $request->file('picture')->store('service_pictures', 'public');
        }

        // Utilisez le professionType pour créer le service
        $professionType = $user->professionType;

        // Gérer l'upload de l'image
        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->store('service_pictures', 'public');
          //  $pictureUrl = Storage::url($picturePath);
        }

        // Créer le service
        $service = $professionType->services()->create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'price_type' => $request->price_type,
            'picture' => $picturePath, // Stockage de l'URL de l'image
        ]);

        return response()->json($service, 201);
    }

    // Met à jour un service existant
    public function update(Request $request, $id)
    {
        // Validation des données entrantes
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'price_type' => 'required|in:par séance,par jour',
            'picture' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation de l'image
        ]);

        // Trouver le service
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        // Mettre à jour les informations du service
        $service->update([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'price' => $request->price,
            'price_type' => $request->price_type,
        ]);

        if ($request->hasFile('picture')) {
            // Supprimez l'ancien fichier si nécessaire
            if ($service->picture) {
                $oldImagePath = 'service_pictures/' . basename($service->picture);
                Storage::disk('public')->delete($oldImagePath);
            }

            // Stockez le nouveau fichier et mettez à jour directement
            $picturePath = $request->file('picture')->store('service_pictures', 'public');
            $service->update([
                'picture' => $picturePath, // Met uniquement le chemin du fichier
            ]);
        }
        // Si une nouvelle image est fournie, la mettre à jour
        /* if ($request->hasFile('picture')) {
            // Supprimer l'ancienne image si elle existe
            if ($service->picture && Storage::exists('public/' . $service->picture)) {
                Storage::delete('public/' . $service->picture);
            }

            // Enregistrer la nouvelle image
            $picturePath = $request->file('picture')->store('service_pictures', 'public');
            $pictureUrl = Storage::url($picturePath);

            // Mettre à jour l'URL de l'image
            $service->update([
                'picture' => $pictureUrl,
            ]); */

            return response()->json([
                'service' => $service,
                'picture_url' => Storage::url($service->picture), // Génère l'URL publique
            ]);

       // return response()->json($service);
    }

    // Supprime un service
    public function destroy($id)
    {
        // Trouver le service
        $service = Service::find($id);
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        // Supprimer l'image du service, si elle existe
        if ($service->picture && Storage::exists('public/' . $service->picture)) {
            Storage::delete('public/' . $service->picture);
        }

        // Supprimer le service
        $service->delete();

        return response()->json(['message' => 'Service deleted successfully']);
    }


    public function filterByCategory(Service $service,Request $request)
    {
        // Récupérer les produits qui correspondent à la catégorie donnée
        $service = Service::where('category', $request)->get();

        // Retourner la réponse sous forme de JSON
        return response()->json($service);
    }


    public function filterByVille(Request $request)
    {
        $ville = $request->query('ville'); // Récupérer la ville via la requête

        // Filtrer les services par ville, si la ville est définie
        $services = Service::with('professiontype')
            ->when($ville, function ($query, $ville) {
                return $query->whereHas('professiontype', function ($query) use ($ville) {
                    $query->where('ville', 'like', '%' . $ville . '%');
                });
            })
            ->get()
            ->map(function ($service) {
                // Inclure la ville de professiontype dans la réponse
                $service->ville = $service->professiontype ? $service->professiontype->ville : null;
                return $service;
            });

        return response()->json($services);
    }


}



