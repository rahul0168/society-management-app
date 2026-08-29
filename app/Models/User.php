<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'flat_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function flat()
    {
        return $this->belongsTo(Flat::class, 'flat_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function bookings()
    {
        return $this->hasMany(AmenityBooking::class);
    }

    public function notices()
    {
        return $this->hasMany(Notice::class, 'posted_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isResident(): bool
    {
        return $this->role === 'resident';
    }

    public function isGuard(): bool
    {
        return $this->role === 'guard';
    }
}
