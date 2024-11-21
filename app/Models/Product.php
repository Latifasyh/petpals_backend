<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable=[
         'product_name',
         'category',
         'description',
         'animal_type',
         'picture',
         'price',
         'profession_type_id'
    ];

 /*    public function seller (){
        return $this->belongsTo(Seller::class);
    }
 */
    public function professionType()
    {
        return $this->belongsTo(ProfessionTypes::class, 'profession_type_id');
    }
}
