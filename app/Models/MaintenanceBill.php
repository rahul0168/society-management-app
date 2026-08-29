<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'flat_id',
        'bill_number',
        'month_year',
        'maintenance_amount',
        'utility_amount',
        'penalty_amount',
        'total_amount',
        'due_date',
        'status',
        'payment_date',
        'payment_method',
        'transaction_id',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'payment_date' => 'datetime',
        'maintenance_amount' => 'decimal:2',
        'utility_amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }
}
