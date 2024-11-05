<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PicturesBusiness extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_id',   // ID de l'entité (seller, sheltterGroomer ou veto)
        'entity_type', // Type d'entité (par exemple: Seller, SheltterGroomer, Veto)
        'path',        // Chemin de la photo
    ];

    // Définir la relation polymorphe
    public function entity()
    {
        return $this->morphTo(); // Utilise morphTo pour récupérer le modèle lié
    }
}
