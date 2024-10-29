<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PicturesBusiness extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'sheltter_groomers_id',
        //'veterinarian_id',
        'path',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function sheltterGroomer()
    {
        return $this->belongsTo(SheltterGroomer::class);
    }

    /* public function veterinarian()
    {
        return $this->belongsTo(Veterinarian::class);
    } */
}
