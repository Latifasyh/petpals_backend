<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PicturesBusiness extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',            // ID de l'utilisateur
        'profession_types_id', // ID du type de profession
     //   'entity_id',   // ID de l'entité (seller, sheltterGroomer ou veto)
        'entity_type', // Type d'entité (par exemple: Seller, SheltterGroomer, Veto)
        'path',        // Chemin de la photo
    ];

    // Définir la relation polymorphe
    /* public function entity()
    {
        return $this->morphTo(); // Utilise morphTo pour récupérer le modèle lié
    } */

    public function professionType()
    {
        return $this->belongsTo(ProfessionTypes::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
