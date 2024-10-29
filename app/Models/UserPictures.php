<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPictures extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Lien avec l'utilisateur
        'path',    // Chemin de la photo
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
