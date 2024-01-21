<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Room extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['number', 'type', 'price_per_night', 'status'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
