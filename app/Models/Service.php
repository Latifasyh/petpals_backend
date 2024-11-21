<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'picture',
        'category',
        'description',
        'price',
        'price_type',
        'profession_type_id', // ID de profession_type
   ];

   public function professionType()
   {
       return $this->belongsTo(ProfessionTypes::class);
   }
}
