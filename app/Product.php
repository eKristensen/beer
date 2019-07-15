<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'color', 'quantity', 'active'];

    public function quantities()
    {
        return explode(',', $this->quantity);
    }

    public function beers()
    {
        return $this->hasMany(Beer::class);
    }
}
