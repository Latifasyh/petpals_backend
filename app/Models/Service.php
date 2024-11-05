<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'category',
        'description',
        'picture',
        'price',
        'shelttergroomer_id'
   ];

   public function SeltterGroomer (){
       return $this->belongsTo(SheltterGroomer::class);
   }
}
