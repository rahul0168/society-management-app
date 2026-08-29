<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmenityBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'amenity_id',
        'user_id',
        'flat_id',
        'booking_date',
        'start_time',
        'end_time',
        'total_fee',
        'status',
        'purpose',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_fee' => 'decimal:2',
    ];

    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }
}
