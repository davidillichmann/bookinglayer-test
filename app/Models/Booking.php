<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // /**
    //  * The attributes that should be cast.
    //  *
    //  * @var array
    //  */
    // protected $casts = [
    //     'starts_at' => 'date:Y-m-d',
    //     'ends_at' => 'date:Y-m-d',
    // ];

    /**
     * @var array
     */
    protected $fillable = [
        'room_id',
        'starts_at',
        'ends_at',
    ];

    /**
     * Get the room that owns the booking.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
