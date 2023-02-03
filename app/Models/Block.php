<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    /**
     * Get the room that owns the booking.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
