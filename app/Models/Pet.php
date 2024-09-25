<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'birthday_pet',
        'family',
        'breed',
        'vaccination_agenda',
        'disease',
        'pet_picture',
        'user_id'

    ];

    public function user (){
        return $this->belongsTo(User::class);
    }
}
