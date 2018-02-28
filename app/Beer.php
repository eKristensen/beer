<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class Beer extends Model
{
    protected $fillable = ['room', 'amount', 'quantity', 'type', 'ipAddress'];

    public function room()
    {
    	return $this->belongsTo(Room::class);
    }

    public function getRefundAttribute() {
    	if ($this->created_at->addMinutes(30)->gt(Carbon::now())) 
    		{
    			$this->refunded = true;
    			$this->save();
    		}
    	return true;
	}
}
