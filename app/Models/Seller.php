<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable=[
        'business_name',
        'address',
        'number_phone_pro',
        'city',
        'user_id'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products (){
        return $this->hasMany(Product::class);
    }

    public function sheltterGroomer()
    {
        return $this->hasOne(SheltterGroomer::class);
    }

    public function Veto()
    {
        return $this->hasOne(Veto::class);
    }
    public function pictures()
    {
        return $this->morphMany(PicturesBusiness::class, 'entity'); // Relation polymorphe
    }


}
