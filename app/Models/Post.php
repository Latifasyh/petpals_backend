<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable =[
        'body',
        'date_post',
        'file',
        'reaction',
        'user_id'
    ];

     public function user (){
        return $this->belongsTo(User::class);
    }
}
