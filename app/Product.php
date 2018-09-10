<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'active'];

    public function beers()
    {
        return $this->hasMany(Beer::class);
    }
}
