<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $table = 'products';
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class,'category_id','id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class,'product_id','id');
    }
    public function promotion()
    {
        return $this->hasOne(Promotion::class,'product_id','id');
    }
}

