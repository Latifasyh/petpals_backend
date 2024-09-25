<?php

namespace App\Models;
use App\Models\Post;
use App\Models\seller;
use App\Models\SheltterGroomer;
// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'birthday',
        'country',
        'phonecode',
        'ville',
        'number_phone',
        'family_situation',
        'gender',
        'picture',
        'account_id'

    ];

      public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function pets(){
        return $this->hasMany(Pet::class);
    }


    public function seller()
    {
        return $this->hasOne(Seller::class);
    }

    public function SheltterGroomer()
    {
        return $this->hasOne(SheltterGroomer::class);
    }



}
