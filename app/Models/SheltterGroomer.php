<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SheltterGroomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seller_id'

    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* public function seller()
    {
        return $this->belongsTo(Seller::class);
    } */
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }


    // Accessoires pour les attributs de Seller
    public function getBusinessNameAttribute()
    {
        return $this->seller ? $this->seller->business_name : null;
    }

    public function getAddressAttribute()
    {
        return $this->seller ? $this->seller->address : null;
    }

    public function getNumberPhoneProAttribute()
    {
        return $this->seller ? $this->seller->number_phone_pro : null;
    }

    public function getCityAttribute()
    {
        return $this->seller ? $this->seller->city : null;
    }



    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id', 'seller_id');
    }

   /*  public function PicturesBusiness()
    {
        return $this->hasMany(PicturesBusiness::class);
    } */

    public function pictures()
    {
        return $this->morphMany(PicturesBusiness::class, 'entity'); // Relation polymorphe
    }

   /*  public function groomingServices()
    {
        // Suppose you have a GroomingService model
        return $this->hasMany(GroomingService::class, 'shelter_groomer_id', 'id');
    } */
}
