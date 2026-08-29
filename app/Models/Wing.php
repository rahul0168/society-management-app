<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wing extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'total_floors'];

    public function flats()
    {
        return $this->hasMany(Flat::class);
    }
}
