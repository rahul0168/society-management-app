<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'flat_id',
        'created_by',
        'visitor_name',
        'visitor_phone',
        'purpose',
        'vehicle_number',
        'entry_code',
        'expected_at',
        'checked_in_at',
        'checked_out_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'expected_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
