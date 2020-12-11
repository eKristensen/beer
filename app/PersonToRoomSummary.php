<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonToRoomSummary extends Model
{
    protected $fillable = ['id', 'room_id'];

    public $incrementing = false;

    public function room()
    {
        return $this->belongsTo('App\Room');
    }
}
