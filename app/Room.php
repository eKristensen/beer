<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
	protected $fillable = ['id', 'name', 'active'];
	//protected $guarded = [];

	public $incrementing = false;

    public function beers()
    {
        return $this->hasMany(Beer::class, 'room');
    }

    public function getSumAttribute(){
    	$beer = new \App\Beer();
    	return $beer->where('room','=',$this->id)->where('refunded','=',0)->sum('amount');
	}
}
