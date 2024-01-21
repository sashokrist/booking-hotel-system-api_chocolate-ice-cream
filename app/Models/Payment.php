<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Payment extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['booking_id', 'amount', 'payment_date', 'status'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
