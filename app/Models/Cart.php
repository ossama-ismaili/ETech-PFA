<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Cart extends Model
{
    protected $table = 'carts';
    protected $fillable = ['user_id','total','items_count'];

    public function cart_items()
    {
        return $this->hasMany(CartItem::class,'cart_id','id');
    }
}

