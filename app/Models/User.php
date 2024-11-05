<?php

namespace App\Models;
use App\Models\Post;
use App\Models\seller;
// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Reaction;
use App\Models\SheltterGroomer;
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

    public function reaction(){
        return $this->hasMany(Reaction::class);
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

    public function Veto()
    {
        return $this->hasOne(Veto::class);
    }

    public function Discussion()
    {
        return $this->hasMany(Discussion::class);
    }
    public function CoverPic()
    {
        return $this->hasMany(CoverPic::class);
    }

    public function UserPictures()
{
    return $this->hasMany(UserPictures::class);
}

}
