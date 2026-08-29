<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'max_capacity',
        'fee_per_slot',
        'status',
        'icon',
    ];

    public function bookings()
    {
        return $this->hasMany(AmenityBooking::class);
    }
}
