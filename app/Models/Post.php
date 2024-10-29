<?php

namespace App\Models;


use App\Models\Reaction;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable =[
        'body',
        'file',
        'type',
        'user_id'
    ];

     public function user (){
        return $this->belongsTo(User::class);
    }

    public function reaction (){
        return $this->hasMany(Reaction::class);
    }
}
