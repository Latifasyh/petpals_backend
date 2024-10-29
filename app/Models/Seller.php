<?php

namespace App\Models;

use App\Models\SheltterGroomer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seller extends Model
{
    use HasFactory;

    protected $fillable=[
        'business_name',
        'address',
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
    public function PicturesBusiness()
    {
        return $this->hasMany(PicturesBusiness::class);
    }

}
