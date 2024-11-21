<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionTypes extends Model
{
    use HasFactory;
    // Attributs fillables
    protected $fillable = [
        'type',
        'business_name',
        'adresse',
        'ville',
        'num_pro',
        'user_id'
    ];

    // Relation avec l'utilisateur (un type de profession appartient à un utilisateur)
    public function user()
    {
        return $this->belongsTo(User::class); // Chaque profession type appartient à un utilisateur
    }

   /*  public function pictures()
    {
        return $this->morphMany(PicturesBusiness::class, 'entity'); // Relation polymorphe
    } */

    public function pictures()
    {
        return $this->hasMany(PicturesBusiness::class, 'profession_types_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'profession_type_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'profession_type_id');
    }
}
