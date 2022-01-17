<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Command extends Model
{
    protected $table = 'commands';
    protected $fillable = ['user_id','product_id','quantity','status','paid_at'];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
