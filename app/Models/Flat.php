<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    use HasFactory;

    protected $fillable = [
        'wing_id',
        'flat_number',
        'floor_number',
        'flat_type',
        'occupancy_status',
        'area_sqft',
    ];

    public function wing()
    {
        return $this->belongsTo(Wing::class);
    }

    public function residents()
    {
        return $this->hasMany(User::class, 'flat_id');
    }

    public function maintenanceBills()
    {
        return $this->hasMany(MaintenanceBill::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

    public function getFullNumberAttribute()
    {
        return ($this->wing ? $this->wing->name . ' - ' : '') . $this->flat_number;
    }
}
