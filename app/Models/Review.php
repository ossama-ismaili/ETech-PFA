<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class Review extends Model
{
    protected $table = 'reviews';
    protected $fillable = ['user_id','product_id','rating','comment'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}

