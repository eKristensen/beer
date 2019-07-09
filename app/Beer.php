<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class Beer extends Model
{
    protected $fillable = ['room', 'amount', 'quantity', 'product', 'ipAddress'];

    public function getRoom()
    {
        return $this->belongsTo(Room::class, 'room', 'id');
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product', 'id');
    }

    public function getRefundAttribute()
    {
        // Don't allow refund for purchaes older than 30 minutes
        if ($this->created_at->addMinutes(30)->lt(Carbon::now())) {
            // If so, then return the current status
            return $this->refunded;
        }

        $this->refunded = true;
        $this->save();
        return true;
    }
}
